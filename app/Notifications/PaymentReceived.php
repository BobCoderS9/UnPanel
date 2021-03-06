<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class PaymentReceived extends Notification implements ShouldQueue
{
    use Queueable;

    private $amount;
    private $sn;

    public function __construct($sn, $amount)
    {
        $this->amount = $amount;
        $this->sn = $sn;
    }

    public function via($notifiable)
    {
        return sysConfig('payment_received_notification');
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('Payment Received'))
            ->line(__('Payment for #:sn has been received! Total amount: Â¥:amount.', ['sn' => $this->sn, 'amount' => $this->amount]))
            ->action(__('Invoice Detail'), route('invoiceInfo', $this->sn));
    }

    public function toDataBase($notifiable)
    {
        return [
            'sn'     => $this->sn,
            'amount' => $this->amount,
        ];
    }

    /**
     * @param $notifiable
     * @return TelegramMessage|\NotificationChannels\Telegram\Traits\HasSharedLogic
     */
    public function toTelegram($notifiable)
    {
        foreach (User::role('Super Admin')->get() as $admin) {
            $message = sprintf(
                "ð°æåæ¶æ¬¾%så\nâââââââââââââââ\nè®¢åå·ï¼%s",
                $this->amount,
                $this->sn
            );

            return TelegramMessage::create()
                ->to($admin->telegram_id)
                ->token(sysConfig('telegram_token'))
                ->content($message);
        }
    }
}
