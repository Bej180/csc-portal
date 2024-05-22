<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApprovedResultNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $date;
    public $course_code;
    public $subject;


    public function __construct(User $user, $course_code, $date)
    {
        $this->user = $user;
        $this->course_code = $course_code;
        $this->date = $date;
        $this->subject = "$course_code Approved";
    }

    public function build()
    {
        return $this->markdown('emails.email.approved-results');
    }
}
