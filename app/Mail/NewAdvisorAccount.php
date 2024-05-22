<?php

namespace App\Mail;

use App\Mail\BaseMail;
use App\Models\User;

class NewAdvisorAccount extends BaseMail
{
    public function __construct(User $user, string $class)
    {
        $data = [
            'user' => $user,
            'class' => $class,
            'verificationUrl' => route('activate-account', ['token' => generateToken()]), 
        ];

        $subject = 'Welcome to ' . config('app.name');

        parent::__construct($data, $subject);
    }

    public function build()
    {
        return $this->view('emails.add-advisor-email');
    }
}
