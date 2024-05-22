<?php 

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\NexmoMessage;


class SMSNotification extends Notification
{
    use Queueable;

    protected $messageContent;

    public function __construct($messageContent)
    {
        $this->messageContent = $messageContent;
    }

    public function via($notifiable)
    {
        return ['nexmo'];
    }

    public function toNexmo($notifiable)
    {
        return (new NexmoMessage)
                    ->content($this->messageContent);
    }
}
