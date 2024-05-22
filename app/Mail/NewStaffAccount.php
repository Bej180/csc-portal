<?php

namespace App\Mail;

use App\Mail\BaseMail;
use App\Models\User;
use Illuminate\Mail\Mailable;

class NewStaffAccount extends Mailable
{
    public $user;
    public $subject;
    public $verificationUrl;
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->verificationUrl = $user->generateActivationLink();
        $this->subject = 'Welcome to ' . config('app.name');
    }


    public function build()
    {
        return $this->markdown('emails.add-staff');
    }
}
