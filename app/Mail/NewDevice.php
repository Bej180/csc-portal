<?php

namespace App\Mail;

use App\Mail\BaseMail;
use App\Models\User;
use Illuminate\Mail\Mailable;

class NewDevice extends Mailable
{
    public $ip_address;
    public $user;
    public $user_agent;
    public $subject;

    public function __construct(User $user)
    {
        $this->ip_address = request()->ip(); 
        $this->user_agent = request()->userAgent();
        $this->user = $user;

        $this->subject = 'Unusual Activity Detected on Your Account - ' . config('app.name');

    }

    public function build()
    {
        return $this->view('emails.new-device');
    }
}
