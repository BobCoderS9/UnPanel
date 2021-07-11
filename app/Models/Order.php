<?php

namespace App\Models;

use Auth;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * 订单.
 */
class Order extends Model
{
    protected $table = 'order';
    protected $dates = ['expired_at'];
    protected $guarded = [];

    protected $appends = ['status_label'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function goods(): BelongsTo
    {
        return $this->belongsTo(Goods::class)->withTrashed();
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class)->withTrashed();
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function scopeUid($query, $uid = null)
    {
        return $query->whereUserId($uid ?: Auth::id());
    }

    public function scopeRecentUnPay($query)
    {
        return $query->whereStatus(0)->where('created_at', '<=', date('Y-m-d H:i:s', strtotime('-'.config('tasks.close.order').' minutes')));
    }

    public function scopeUserPrepay($query, $uid = null)
    {
        return $query->uid($uid)->whereStatus(3);
    }

    public function scopeActive($query)
    {
        return $query->whereIsExpire(0)->whereStatus(2);
    }

    public function scopeActivePlan($query)
    {
        return $query->active()->with('goods')->whereHas('goods', static function ($query) {
            $query->whereType(2);
        });
    }

    public function scopeActivePackage($query)
    {
        return $query->active()->with('goods')->whereHas('goods', static function ($query) {
            $query->whereType(1);
        });
    }

    public function scopeUserActivePlan($query, $uid = null)
    {
        return $query->uid($uid)->activePlan();
    }

    public function scopeUserActivePackage($query, $uid = null)
    {
        return $query->uid($uid)->activePackage();
    }

    public function close() // 关闭订单
    {
        return $this->update(['status' => -1]);
    }

    public function paid() // 完成订单
    {
        return $this->update(['status' => 1]);
    }

    public function complete() // 完成订单
    {
        return $this->update(['status' => 2]);
    }

    public function prepay() // 预支付订单
    {
        return $this->update(['status' => 3]);
    }

    // 订单状态
    public function getStatusLabelAttribute(): string
    {
        switch ($this->attributes['status']) {
            case -1:
                $status_label = '<span class="badge badge-default">'.trans('user.status.closed').'</span>';
                break;
            case 0:
                $status_label = '<span class="badge badge-danger">'.trans('user.status.waiting_payment').'</span>';
                break;
            case 1:
                $status_label = '<span class="badge badge-info">'.trans('user.status.waiting_confirm').'</span>';
                break;
            case 2:
                if ($this->attributes['goods_id'] === 0) {
                    $status_label = '<span class="badge badge-default">'.trans('user.status.completed').'</span>';
                } elseif ($this->attributes['is_expire']) {
                    $status_label = '<span class="badge badge-default">'.trans('user.status.expired').'</span>';
                } else {
                    $status_label = '<span class="badge badge-success">'.trans('user.status.using').'</span>';
                }
                break;
            case 3:
                $status_label = '<span class="badge badge-info">'.trans('user.status.prepaid').'</span>';
                break;
            default:
                $status_label = trans('user.unknown');
        }

        return $status_label;
    }

    public function getOriginAmountAttribute($value)
    {
        return $value / 100;
    }

    public function setOriginAmountAttribute($value)
    {
        return $this->attributes['origin_amount'] = $value * 100;
    }

    public function getAmountAttribute($value)
    {
        return $value / 100;
    }

    public function setAmountAttribute($value)
    {
        return $this->attributes['amount'] = $value * 100;
    }

    // 支付渠道
    public function getPayTypeLabelAttribute(): string
    {
        return [
            0 => trans('common.payment.credit'),
            1 => trans('common.payment.alipay'),
            2 => 'QQ',
            3 => trans('common.payment.wechat'),
            4 => trans('common.payment.crypto'),
            5 => 'PayPal',
            6 => 'Stripe',
        ][$this->attributes['pay_type']] ?? '';
    }

    // 支付图标
    public function getPayTypeIconAttribute(): string
    {
        return '/assets/images/payment/'.config('common.payment.icon')[$this->attributes['pay_type']] ?? 'coin.png';
    }

    // 支付方式
    public function getPayWayLabelAttribute(): string
    {
        return config('common.payment.labels')[$this->attributes['pay_way']] ?? '未知';
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }
}
