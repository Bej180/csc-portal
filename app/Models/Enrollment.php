<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\Result;

class Enrollment extends Model
{
    use HasFactory;

    protected $table = 'enrollments';
    protected $fillable = [
        'user_id',
        'course_id',
        'reg_no',
        'reference_id',
        'session',
        'semester',
        'unit',
        'level',
        'code',
        'request_id',
        'approved',
        'name'
    ];





    public static function getEnrollmentsByRequestID(string $request_id) {

    }
    



    public static function getFillables(array $data = [])
    {
        $class = __CLASS__;
        $obj = new $class;

        $fillables = $obj->fillable;

        if (count($data) === 0) {
            return $fillables;
        }

        return Arr::only($data, $fillables);
    }

    public function gradeToPoints($grade)
    {

        $grade = strtoupper($grade);

        $gradingPointsMapping = [
            'A' => 5,
            'B' => 4,
            'C' => 3,
            'D' => 2,
            'E' => 1,
            'F' => 0
        ];


        return $gradingPointsMapping[$grade] ?? 0;
    }

    public static function scoreToPoints(int $score)
    {
        
        return match (true) {
            $score >= 70 => 5,
            $score >= 60 => 4,
            $score >= 50 => 3,
            $score >= 45 => 2,
            $score >= 40 => 1,
            default => 0,
        };
    }

    public static function scoreToGrade(int $score) {
        $grades = ['F','E','D','C','B','A'];
        $points = self::scoreToPoints($score);

        return $grades[$points];
    }


    public static function calculateCGPA(Student $student)
    {
        $results = Result::where('reg_no', $student->reg_no)->with('course')->get();

        $academicRecords = [];
        $totalCredits = 0;
        $totalGradePoints = 0;
        foreach($results as $result) {
            $course = $result->course;

            // Calculate the grade points for each course
            $gradePoints = self::scoreToPoints($result->score);
            $totalGradePoints += $gradePoints;

            // Sum the total credits and grade points
            $totalCredits += $course->unit;

            
        }


        if ($totalCredits > 0) {
            // Calculate GPA by dividing total grade points by total credits
            $gpa = $totalGradePoints / $totalCredits;
            return number_format($gpa, 2);
        }

        return 0.0; // Return 0 GPA if there are no academic records
    }

    public static function previousGPA(Student $student, string $semester, int $level) {
        $currentlevel = $level;
        $currentsemester = $semester;
        $prev = [
            'HARMATTAN' => 'RAIN',
            'RAIN' => 'HARMATTAN',
        ];
        
        
        if ($semester === 'HARMATTAN') {
            $level -= 100;
        }
        
        
        
        $semester = $prev[$semester];
       

        return self::calculateGPA($student, $semester, $level);

    }




    public static function calculateGPA(Student $student, string $semester, int $level) 
    {
        if ($level <= 0) {
            return [
                'gpa' => 0.0,
                'unit' => 0,
                'points' => 0
            ];
        }

        $results = Result::where('reg_no', $student->reg_no)
                        ->where('level', $level)
                        ->where('semester', $semester)
                        ->with('course')
                        ->get();

        $totalCredits = 0;
        $totalGradePoints = 0;
        foreach($results as $result) {
            $course = $result->course;

            
            // Calculate the grade points for each course
            $gradePoints = self::scoreToPoints($result->score);
            $totalGradePoints += $gradePoints;
           

            // Sum the total credits and grade points
            $totalCredits += $course->unit;

            
        }

        $gpa = 0.0; // Return 0 GPA if there are no academic records

        if ($totalCredits > 0) {
            // Calculate GPA by dividing total grade points by total credits
            $gpa = number_format($totalGradePoints / $totalCredits, 2);
        }

        return [
            'gpa' => $gpa,
            'unit' => $totalCredits,
            'points' => $totalGradePoints
        ];
    }

    public static function active() {
        return self::where('reg_no', auth()->user()->student->reg_no);
    }
  

    public function student() {
        return $this->hasOne(Student::class, 'reg_no', 'reg_no');
    }



    public function course() {
        return $this->hasOne(Course::class, 'id', 'course_id');
    }

    public function _course() {
        return $this->belongsTo(Course::class);
    }

    public static function result(int|string $reg_no, int $course_id, string $semester, string $session) {
        return Result::with('course')->where('reg_no', $reg_no)
                    ->where('course_id', $course_id)
                    ->where('semester', $semester)
                    ->where('session', $session)->first();
    }

    public static function students($semester, $session, $course_id) {
        return self::where('session', $session)
            ->with(['student.user', 'course'])
            ->where('semester', $semester)
            ->where('course_id', $course_id)
            ->get()
            ->map(function($enrollment) {
                $enrollment->results = Result::where('session', $enrollment->session)
                    ->where('semester', $enrollment->semester)
                    ->where('course_id', $enrollment->course_id)
                    ->where('reg_no', $enrollment->reg_no)
                    ->first();
                if ($enrollment->results && $enrollment->results->status !=='ready') {
                    // $enrollment->results = [];
                }
                    return $enrollment;
            });

        return self::join('students', 'enrollments.reg_no', 'students.reg_no')
            ->join('courses', 'enrollments.course_id', 'courses.id')
            ->join('users', 'students.id', 'users.id')
            ->where('enrollments.semester', $semester)
            ->where('enrollments.session', $session)
            ->where('enrollments.course_id', $course_id)
            ->select('courses.has_practical','users.name', 'students.reg_no','courses.code', 'courses.units', 'courses.name as course_name', 'enrollments.level')
            ->get()
            ->unique('reg_no');
    }

    


}
