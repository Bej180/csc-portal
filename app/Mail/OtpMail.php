<?php

namespace App\Mail;

use App\Mail\BaseMail;
use App\Models\User;


class OtpMail extends BaseMail
{
    public function __construct(User $user, string $otp)
    {
        $subject = 'Your One-Time Password (OTP)'; 

        parent::__construct(compact('user', 'otp'), $subject);
    }

    public function build()
    {
        return $this->view('emails.otp-mail');
    }
}