<?php


namespace App\Http\Controllers\Api\V1;


use App\Components\Helpers;
use App\Http\Controllers\PaymentController;
use App\Models\Config;
use App\Models\Coupon;
use App\Models\Goods;
use App\Models\GoodsCategory;
use App\Models\Invite;
use App\Models\Order;
use App\Models\User;
use App\Models\Verify;
use App\Models\VerifyCode;
use App\Notifications\AccountActivation;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShopController extends BaseController
{
    /**
     * @param Request $request
     * @return ShopController|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $category = GoodsCategory::query()->orderByDesc('sort')->get()->toArray();
        $list = [];
        foreach ($category as $cate) {
            $list[$cate['name']] = Goods::with(['category'])
                ->where('category_id', $cate['id'])
                ->where('status', 1)
                ->orderByDesc('sort')
                ->get()
                ->toArray();
        }
        $payment = [];
        if ($alipay = sysConfig('is_AliPay')) {
            $payment['alipay'] = $alipay;
        }
        if ($alipay = sysConfig('is_WeChatPay')) {
            $payment['wechat'] = $alipay;
        }
        if ($crypto = sysConfig('is_otherPay')) {
            $payment['crypto'] = $crypto;
        }

        return $this->sendJson(compact('list', 'category', 'payment'));
    }

    public function checkCoupon(Request $request)
    {
        $coupon_sn = $request->input('coupon_sn');
        $good_price = $request->input('price');

        if (empty($coupon_sn)) {
            return $this->sendError(trans('validation.required', ['attribute' => trans('user.coupon.attribute')]));
        }

        $coupon = Coupon::whereSn($coupon_sn)->whereIn('type', [1, 2])->first();
        if (!$coupon) {
            return $this->sendError(trans('user.unknown') . trans('user.coupon.attribute'));
        }

        if ($coupon->status === 1) {
            return $this->sendError(trans('user.coupon.attribute') . trans('user.status.used'));
        }

        if ($coupon->status === 2) {
            return $this->sendError(trans('user.coupon.attribute') . trans('user.status.expired'));
        }

        if ($coupon->getRawOriginal('end_time') < time()) {
            $coupon->status = 2;
            $coupon->save();

            return $this->sendError(trans('user.coupon.attribute') . trans('user.status.expired'));
        }

        if ($coupon->start_time > date('Y-m-d H:i:s')) {
            return $this->sendError(trans('user.coupon.wait_active', ['time' => $coupon->start_time]));
        }

        if ($good_price < $coupon->rule) {
            return $this->sendError(trans('user.coupon.higher', ['amount' => $coupon->rule]));
        }

        $data = [
            'name' => $coupon->name,
            'type' => $coupon->type,
            'value' => $coupon->value,
        ];

        return $this->sendJson($data);
    }

    public function submitOrder(Request $request)
    {
        $goods_id = (int)$request->post('shop_id');
        $aff = (string)$request->post('aff');
        $email = (string)$request->post('email', '');
        $password = (string)$request->post('password', '');
        $device = $request->post('device');
        $pay_type = (int)$request->post('pay_type');
        $coupon_sn = (string)$request->post('couponCode');
        $method = (string)$request->post('method');
        $user = auth('api')->user();
        $is_new = $user ? false : true;
        $goods = Goods::find($goods_id);
        $token = '';
        if (!$user) {
            if (User::query()->where('email', $email)->exists()) {
                return $this->sendError(trans('auth.email_null'));
            }
            // 如果需要邀请注册
            if (sysConfig('is_invite_register')) {
                // 校验邀请码合法性
                if ($aff) {
                    if (Invite::whereCode($aff)->whereStatus(0)->doesntExist()) {
                        return $this->sendError(trans('auth.invite.error.unavailable'));
                    }
                } elseif ((int)sysConfig('is_invite_register') === 2) { // 必须使用邀请码
                    return $this->sendError(trans('validation.required', ['attribute' => trans('auth.invite.attribute')]));
                }
            }
            // 获取aff
            $affArr = $this->getAff($aff, '');
            $inviter_id = $affArr['inviter_id'];
            $transfer_enable = MB * ((int)sysConfig('default_traffic') + ($inviter_id ? (int)sysConfig('referral_traffic') : 0));
            // 创建新用户
            $user = Helpers::addUser($email, $password, $transfer_enable, sysConfig('default_days'), $inviter_id, $email);
            // 注册失败，抛出异常
            if (! $user) {
                return $this->sendError(trans('auth.register.failed'));
            }
            // 更新邀请码
            if ($affArr['code_id'] && sysConfig('is_invite_register')) {
                $invite = Invite::find($affArr['code_id']);
                if ($invite) {
                    $invite->update(['invitee_id' => $user->id, 'status' => 1]);
                }
            }
            // 注册后发送激活码
            if ((int)sysConfig('is_activate_account') === 2) {
                // 生成激活账号的地址
                $token = $this->addVerifyUrl($user->id, $user->email);
                $activeUserUrl = route('activeAccount', $token);
                $user->notifyNow(new AccountActivation($activeUserUrl));
            } else {
                // 则直接给推荐人加流量
                if ($inviter_id) {
                    $referralUser = User::find($inviter_id);
                    if ($referralUser && $referralUser->expired_at >= date('Y-m-d')) {
                        $referralUser->incrementData(sysConfig('referral_traffic') * MB);
                    }
                }
                if ((int)sysConfig('is_activate_account') === 1) {
                    $user->update(['status' => 1]);
                }
            }
            // 直接创建token并设置有效期
            $token = auth('api')->attempt($request->only('email', 'password'));
        }
        if (!$goods || !$goods->status) {
            if ($is_new){
                $user->delete();
            }
            return $this->sendError('订单创建失败：商品已下架');
        }
        $amount = $goods->price;
        // 是否有生效的套餐
        $activePlan = Order::userActivePlan()->doesntExist();
        //　无生效套餐，禁止购买加油包
        if ($goods->type === 1 && $activePlan) {
            if ($is_new){
                $user->delete();
            }
            return $this->sendError('购买加油包前，请先购买套餐');
        }
        // 单个商品限购
        if ($goods->limit_num) {
            $count = Order::uid()->where('status', '>=', 0)->whereGoodsId($goods_id)->count();
            if ($count >= $goods->limit_num) {
                if ($is_new){
                    $user->delete();
                }
                return $this->sendError('此商品限购' . $goods->limit_num . '次，您已购买' . $count . '次');
            }
        }
        // 使用优惠券
        if ($coupon_sn) {
            $coupon = Coupon::whereStatus(0)->whereIn('type', [1, 2])->whereSn($coupon_sn)->first();
            if (!$coupon) {
                return $this->sendError('订单创建失败：优惠券不存在');
            }

            // 计算实际应支付总价
            $amount = $coupon->type === 2 ? $goods->price * $coupon->value / 100 : $goods->price - $coupon->value;
            $amount = $amount > 0 ? round($amount, 2) : 0; // 四舍五入保留2位小数，避免无法正常创建订单
        }
        //非余额付款下，检查在线支付是否开启
        if ($method !== 'credit') {
            // 判断是否开启在线支付
            if (!sysConfig('is_onlinePay')) {
                if ($is_new){
                    $user->delete();
                }
                return $this->sendError('订单创建失败：系统并未开启在线支付功能');
            }

            // 判断是否存在同个商品的未支付订单
            if (Order::uid()->whereStatus(0)->exists()) {
                if ($is_new){
                    $user->delete();
                }
                return $this->sendError('订单创建失败：尚有未支付的订单，请先去支付');
            }
        } elseif ($user->credit < $amount) { // 验证账号余额是否充足
            return $this->sendError('您的余额不足，请先充值');
        }

        // 价格异常判断
        if ($amount < 0) {
            if ($is_new){
                $user->delete();
            }
            return $this->sendError('订单创建失败：订单总价异常');
        }

        if ($amount === 0 && $method !== 'credit') {
            if ($is_new){
                $user->delete();
            }
            return $this->sendError('订单创建失败：订单总价为0，无需使用在线支付');
        }

        // 生成订单
        try {
            $newOrder = Order::create([
                'sn' => date('ymdHis') . random_int(100000, 999999),
                'user_id' => $user->id,
                'goods_id' => $goods_id,
                'coupon_id' => $coupon->id ?? null,
                'origin_amount' => $goods->price ?? 0,
                'amount' => $amount,
                'status' => 0,
                'pay_type' => $pay_type,
                'pay_way' => $method,
            ]);

            // 使用优惠券，减少可使用次数
            if (!empty($coupon)) {
                if ($coupon->usable_times > 0) {
                    $coupon->decrement('usable_times', 1);
                }

                Helpers::addCouponLog('订单支付使用', $coupon->id, $goods_id, $newOrder->id);
            }
            $newOrder->user['uuid'] = $user->vmess_id;
            $newOrder->user['hmac'] = $token;

            return $this->sendJson($newOrder);
        } catch (\Exception $e) {
            if ($is_new){
                $user->delete();
            }
            Log::error('订单生成错误：' . $e->getMessage());
        }

        if ($is_new){
            $user->delete();
        }
        return $this->sendError('订单创建失败');
    }

    /**
     * 获取AFF.
     *
     * @param string|null $code 邀请码
     * @param int|null $aff URL中的aff参数
     *
     * @return array
     */
    private function getAff($code = null, $aff = null): array
    {
        $data = ['inviter_id' => null, 'code_id' => 0]; // 邀请人ID 与 邀请码ID

        // 有邀请码先用邀请码，用谁的邀请码就给谁返利
        if ($code) {
            $inviteCode = Invite::whereCode($code)->whereStatus(0)->first();
            if ($inviteCode) {
                $data['inviter_id'] = $inviteCode->inviter_id;
                $data['code_id'] = $inviteCode->id;
            }
        }

        // 没有用邀请码或者邀请码是管理员生成的，则检查cookie或者url链接
        if (!$data['inviter_id']) {
            // 检查一下cookie里有没有aff
            $cookieAff = \request()->cookie('register_aff');
            if ($cookieAff) {
                $cookieAff = $this->affConvert($cookieAff);
                $data['inviter_id'] = $cookieAff && User::find($cookieAff) ? $cookieAff : null;
            } elseif ($aff) { // 如果cookie里没有aff，就再检查一下请求的url里有没有aff，因为有些人的浏览器会禁用了cookie，比如chrome开了隐私模式
                $aff = $this->affConvert($aff);
                $data['inviter_id'] = $aff && User::find($aff) ? $aff : null;
            }
        }

        return $data;
    }

    private function affConvert($aff)
    {
        if (is_numeric($aff)) {
            return $aff;
        } else {
            $decode = (new Hashids(sysConfig('aff_salt'), 8))->decode($aff);
            if (isset($decode)) {
                return $decode[0];
            }
        }

        return false;
    }

    // 生成申请的请求地址
    private function addVerifyUrl($uid, $email)
    {
        $token = md5(sysConfig('website_name') . $email . microtime());
        $verify = new Verify();
        $verify->user_id = $uid;
        $verify->token = $token;
        $verify->save();

        return $token;
    }
}
