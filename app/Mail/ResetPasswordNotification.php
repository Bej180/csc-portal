<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $resetLink;
    public $subject;


    public function __construct(User $user, $resetLink)
    {
      
        
        $this->resetLink = $resetLink;
        $this->user = $user;
        $this->subject = 'Reset Your Password';
    }

    public function build()
    {
        return $this->markdown('emails.password_reset');
    }
}
