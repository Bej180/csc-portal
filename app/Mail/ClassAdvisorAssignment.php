<?php

namespace App\Mail;

use App\Models\AcademicSet;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClassAdvisorAssignment extends Mailable
{
    
    use Queueable, SerializesModels;
    public $user;
    public $subject;
    public $class;


    public function __construct(User $user, AcademicSet $class)
    {
        $this->user = $user;
        $this->class = $class;
        $this->subject = 'Congratulations! You have been assigned as a class advisor';
    }

    public function build()
    {
        return $this->markdown('emails.class-advisor-assignment');
    }
}
