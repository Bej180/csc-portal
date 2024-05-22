<?php

namespace App\Mail;

use App\Mail\BaseMail;
use App\Models\Course;
use App\Models\User;
use Illuminate\Mail\Mailable;

class LabScoreAddeNotification extends Mailable
{
    public $user;
    public $subject;
    public $course_code;
    public function __construct(Course $course)
    {
        $this->user = $course->cordinator;
        $this->subject = 'Lab Scores for '.$course->code. ' have been submitted';
        $this->course_code = $course->code;
    }


    public function build()
    {
        return $this->markdown('emails.added-lab-score');
    }
}
