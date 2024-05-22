<?php

namespace App\Mail;

use App\Mail\BaseMail;
use App\Models\User;

class NewStudentAccount extends BaseMail
{
    private $user;
    private $verificationUrl;

    public function __construct(User $user)
    {

        $subject = 'Welcome to ' . config('app.name');
        $this->user = $user;
        $verificationUrl = $user->generateActivationLink();
        $this->verificationUrl = $verificationUrl;
        
        parent::__construct(compact('user', 'verificationUrl'), $subject);
    }
    
    public function build()
    {
        return $this->view('emails.add-studentx')
            ->with([
                'user' => $this->user,
                'verificationUrl' => $this->verificationUrl
            ]);
    }
}
