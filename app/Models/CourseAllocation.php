<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'staff_id',
    ];

    public function user() {
        return $this->hasOne(User::class, 'id', 'staff_id');
    }

    public function course() {
        return $this->hasOne(Course::class, 'id', 'course_id');
    }
    public function lecturer() {
        return $this->hasOne(Staff::class, 'id', 'staff_id');
    }

    public function lecturers() {
        return $this->hasMany(Staff::class, 'staff_id', 'id');
    }


    public function practical() {
        return $this->lecturers->where('role', 'technologies');
    }


    public function technologists()
    {
        $technologist = Staff::where('designation', 'technologist')->get();


        $ids = $technologist->pluck('id');
       
        return  CourseAllocation::whereIn('staff_id', $ids)
            ->where('course_id', '=', $this->course_id)
            ->with('user')
            ->get();

    }


}
