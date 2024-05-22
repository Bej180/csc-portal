<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentsAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'reg_no',
        'attendance_id'
    ];
}
