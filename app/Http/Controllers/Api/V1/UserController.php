<?php


namespace App\Http\Controllers\Api\V1;


use App\Components\Helpers;
use App\Models\Goods;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends BaseController
{
    /**
     * @param Request $request
     * @return UserController|\Illuminate\Http\JsonResponse
     */
    public function checkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:user',
        ], [
            'email.required' => trans('auth.email_null'),
            'email.email' => trans('auth.email_legitimate'),
            'email.unique' => trans('auth.email_exist'),
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->toArray();
            return $this->sendError($error['email'][0]);
        }

        return $this->sendSuccess('OK');
    }

    public function getService(Request $request)
    {
        $user = $request->user('api');
        $order = $user->orders()->active()->first();
        $shop = [];
        if ($order) {
            $shop = Goods::query()->with(['category'])->find($order->goods_id);
        }
        $totalTransfer = $user->transfer_enable;
        $usedTransfer = $user->usedTraffic();
        $unusedTraffic = $totalTransfer - $usedTransfer > 0 ? $totalTransfer - $usedTransfer : 0;
        $referral_traffic = flowAutoShow(sysConfig('referral_traffic') * MB);
        $referral_percent = sysConfig('referral_percent');
        $aff_text = trans('user.invite.promotion', ['traffic' => $referral_traffic, 'referral_percent' => $referral_percent * 100]);

        return $this->sendJson([
            'shop_id' => $shop ? $shop->id : '',
            'shop_name' => $shop ? $shop->category->name . ' - ' . $shop->name : '',
            'expired_at' => strtotime($user->expired_at) * 1000,
            'also_traffic' => sprintf('%.2f', flowAutoShow($unusedTraffic)),   // 剩余流量
            'reset_time' => date('Y-m-d', strtotime($user->reset_time)),  // 重置日期
            'speed_limit' => $user->speed_limit,
            'money' => $user->credit,
            'subscriptionUrl' => $user->subUrl(),
            'aff_url' => sysConfig('website_url') . '/?aff=' . $user->invite_code,
            'aff_text' => $aff_text,
        ]);
    }

    /**
     * @param Request $request
     * @return UserController|\Illuminate\Http\JsonResponse
     */
    public function getInfo(Request $request)
    {
        $user = $request->user();

        return $this->sendJson($user);
    }

    /**
     * @param Request $request
     * @return UserController|\Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $user = $request->user();
        $old_password = $request->input('password');
        $new_password = $request->input('new_password');
        $new_password2 = $request->input('new_password2');
        if (! Hash::check($old_password, $user->password)) {
            return $this->sendError(trans('auth.password.reset.error.wrong'));
        }
        if (Hash::check($new_password, $user->password)) {
            return $this->sendError(trans('auth.password.reset.error.same'));
        }
        if (strlen($new_password) < 6) {
            return $this->sendError(trans('auth.password.reset.error.length'));
        }
        if ($new_password != $new_password2) {
            return $this->sendError(trans('auth.password.reset.error.new_same'));
        }
        $user->update(['password' => $new_password]);

        return $this->sendSuccess('修改成功');
    }


    /**
     * 账单列表
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function invoices(Request $request)
    {
        $user = $request->user();
        $pageSize = (int)$request->get('pageSize', 10);

        $data['orderList'] = $user->orders()->with(['goods', 'payment'])->orderByDesc('id')->paginate($pageSize);
        $data['prepaidPlan'] = Order::userPrepay()->exists();

        return $this->sendJson($data);
    }

    /**
     * @param Request $request
     * @return UserController|JsonResponse
     */
    public function closePlan(Request $request)
    {
        $user = $request->user('api');
        $activePlan = Order::userActivePlan($user->id)->first();
        $activePlan->is_expire = 1;
        if ($activePlan->save()) {
            // 关闭先前套餐后，新套餐自动运行
            if (Order::userActivePlan($user->id)->exists()) {
                return $this->sendSuccess(trans('common.active_item', ['attribute' => trans('common.success')]));
            }

            return $this->sendSuccess(trans('common.close'));
        }

        return $this->sendError(trans('common.close_item', ['attribute' => trans('common.failed')]));
    }

    /**
     * 更换订阅地址
     *
     * @param Request $request
     * @return JsonResponse|null
     */
    public function resetSubscribe(Request $request): ?JsonResponse
    {
        try {
            DB::beginTransaction();

            // 更换订阅码
            $request->user()->subscribe->update(['code' => Helpers::makeSubscribeCode()]);

            // 更换连接信息
            $request->user()->update(['passwd' => Str::random(), 'vmess_id' => Str::uuid()]);

            DB::commit();

            return $this->sendSuccess(trans('common.replace').trans('common.success'));
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error(trans('user.subscribe.error').'：'.$e->getMessage());

            return $this->sendError(trans('common.replace').trans('common.failed').$e->getMessage());
        }
    }
}
