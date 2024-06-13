<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseRegistrationStatus extends Model
{
    use HasFactory;

    protected $fillable = ['semester', 'session', 'close_on'];

    protected $table = 'course_registration_statuses';

    public static function extendWeek(string $session, string $semester, mixed $weeks) {
        $now = Carbon::now();
        if (!is_numeric($weeks)) {
            $weeks = 2;
        }

        $record = CourseRegistrationStatus::where('session', '=', $session)
            ->where('semester', '=', $semester);

        $close_on = $now->addWeeks($weeks);
        
        if ($record) {
            $record->close_on = $close_on;
        } else {
            $record = new CourseRegistrationStatus(compact('semester', 'session'));
        }
        $record->save();

        
    }

    public static function openSessions() {

        return CourseRegistrationStatus::where('close_on', '>', time())->get();

    }


    public static function isOpen(string $session, string $semester) : bool {
        
        $record = CourseRegistrationStatus::where('session', $session)
            ->where('semester', $semester)
            ->first();
        
        if (!$record || $record->close_on->isPast()) {
            if ($record) {
                $record->delete();
            }
            return false;
        }

        return true;
    }




    
}
