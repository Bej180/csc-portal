<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TwoFAMail extends Mailable
{
    use Queueable, SerializesModels;

    public $twoFactorEnabled;

    public function __construct($twoFactorEnabled)
    {
        $this->twoFactorEnabled = $twoFactorEnabled;
    }

    public function build()
    {
        return $this->view('emails.2fa');
    }
}