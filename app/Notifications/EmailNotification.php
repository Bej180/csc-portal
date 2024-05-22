<?php 

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;

class EmailNotification extends Notification
{
    use Queueable;

    protected $messageContent;

    public function __construct($messageContent)
    {
        $this->messageContent = $messageContent;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line($this->messageContent);
    }
}
