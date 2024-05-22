<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;

class LoginMail extends Mailable
{
    public $ipAddress;
    public $userAgent;
    public $user;
    public $loginTime;
    public $subject;

    public function __construct(User $user)
    {
        $this->ipAddress = request()->ip();
        $this->userAgent = request()->userAgent();
        $this->user = $user;
        $this->loginTime = now()->format('Y-m-d H:i:s (P)');;

        $this->subject = 'Login Notification for ' . config('app.name');

    }

    public function build()
    {
        return $this->markdown('emails.login-mail');
    }
}
