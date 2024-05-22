<?php

namespace App\Mail;

use Illuminate\Bus\Queueable; 
use Illuminate\Contracts\Queue\ShouldQueue; 
use Illuminate\Mail\Mailable;

class BaseMail extends Mailable implements ShouldQueue 
{
    use Queueable;

    public $data;
    public $subject;

    public function __construct(array $data, string $subject)
    {
        $this->data = $data;
        $this->subject = $subject;
    }

    public function build()
    {

        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject($this->subject) 
                    ->view('emails.layouts.base')
                    ->with($this->data);
                    
    }
}
