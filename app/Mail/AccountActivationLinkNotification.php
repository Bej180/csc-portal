<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountActivationLinkNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $activationLink;
    public $subject;


    public function __construct(User $user, $activationLink)
    {
        $this->user = $user;
        $this->activationLink = $activationLink;
        $this->subject = 'New Activation Link for Your '.config('app.name').' Account';
    }

    public function build()
    {
        return $this->markdown('emails.activate-account');
    }
}
