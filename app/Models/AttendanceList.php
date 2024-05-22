<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceList extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id',
        'title',
        'session',
        'created_by'
    ];


    public function course() {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    // public function students() {
    //     return $this->hasMany(StudentsAttendance::class, 'reg_no', 'reg_no');
    // }
}
