<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicSession extends Model
{
    use HasFactory;
    protected $table = 'sessions';

    protected $fillable = [
        'name',
        'active_semester',
        'harmattan_course_registration_status',
        'rain_course_registration_status'
    ];


    public static function semestersOpenForCourseRegistration() {
        $sessions = AcademicSession::where('harmattan_course_registration_status', 'OPEN')
            ->orWhere('rain_course_registration_status', 'OPEN')->get();
            $open = [];
        foreach($sessions as $session) {
            if ($session->rain_course_registration_status == 'OPEN') {
                $open [] = ['semester' => 'RAIN', 'session' => $session->name];
            }
            if ($session->harmattan_course_registration_status == 'OPEN') {
                $open [] = ['semester' => 'HARMATTAN', 'session' => $session->name];
            }
        }
        return $open;
    }


    public static function currentSession() {
        return self::latest()->first();
    }
}