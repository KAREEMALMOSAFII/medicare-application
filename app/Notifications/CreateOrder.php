<?php

namespace App\Notifications;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CreateOrder extends Notification
{
    use Queueable;
//private $order_id;
private $user_create;
    /**
     * Create a new notification instance.
     */
    public function __construct($user_create)
    {
      //  $this->order_id=$order_id;
        $this->user_create=$user_create;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via( $notifiable): array
    {
        return ['database'];
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray( $notifiable): array
    {
        return [
           // 'order_id'=>$this->order_id,
            'user_create'=>$this->user_create,
        ];
    }
}
