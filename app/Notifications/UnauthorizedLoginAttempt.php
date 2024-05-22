<?php

// app/Notifications/UnauthorizedLoginAttempt.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UnauthorizedLoginAttempt extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail']; // Send notification via email
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Unauthorized Login Attempt')
            ->line('There was an unauthorized login attempt on your account.')
            ->action('Change Your Password', route('password.reset')); // Link to password reset page
    }
}
