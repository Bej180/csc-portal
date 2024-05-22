<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdatedPasswordNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $subject;


    public function __construct(User $user)
    {
        $this->user = $user;
        $this->subject = 'Important Security Notification - Your Password Has Been Updated';
    }

    public function build()
    {
        return $this->markdown('emails.password_updated');
    }
}
