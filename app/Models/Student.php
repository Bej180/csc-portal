<?php

namespace App\Models;

use App\Models\AcademicSet;
use Illuminate\Support\Arr;
use App\Models\AcademicRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';


    protected $fillable = [
        'birthdate',
        'address',
        'level',
        'gender',
        'image',
        'set_id',
        'phone',
        'id',
        'reg_no',
        'lga',
        'country',
        'state',
        'religion',
    ];

    private $gradingSystem = [
        'A' => 5,
        'B' => 4,
        'C' => 3,
        'D' => 2,
        'E' => 1,
        'F' => 0,
    ];






    
    public function notifications() {
        $class = $this->class;

        $notifications = Announcement::where('target', 'everyone')
                ->orWhere('target', 'students')
                ->orWhere(function($query) {
                    $query->where('target', 'student')
                        ->where('target_id', $this->id);
                })
                ->orWhere(function($query)  use ($class) {
                    $query->where('target', 'class')
                        ->where('target_id', $class->id);

                })
            ->get();

            return $notifications;
    } 
    
    
    
    // Function to calculate overall CGPA
    function calculateCGPA(?string $retrieve = 'GPA') {

        // fetch all approved results
        $results = $this->results()->where('status', 'approved');
        if (!$results->exists()) {
            return 0.0;
        }
        $totalUnits = $results->sum('units');
        $totalGradePoints = $results->sum('grade_points');
        return round($totalGradePoints / $totalUnits, 2);



        $totalCredits = 0;
        $totalQualityPoints = 0;
        
        foreach ($results as $result) {
            $grade = $this->scoreToPoints($result->score);
            $course = $result->course;
            $credits = $course->units;
            
            
            $qualityPoints = $grade * $credits;
            $totalCredits += $credits;
            $totalQualityPoints += $qualityPoints;
        }
        $cgpa = 0;
        if ($totalCredits > 0) {
            $cgpa = $totalQualityPoints / $totalCredits;
        }
        $data = [
            'TGP' => $totalQualityPoints,
            'TNU' => $totalCredits,
            'GPA' => round($cgpa, 2)   
        ];
        if ($retrieve && array_key_exists($retrieve, $data)){
            return $data[$retrieve];
        }
        return $data;
    }









    public function scoreToPoints(int $score)
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

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id');
    }

    public static function auth()
    {
        return Student::where('id', auth()->id())->with('user')->first();
    }

    public function picture() {
        return $this->user->picture();
    }

    public function class()
    {
        return $this->belongsTo(AcademicSet::class, 'set_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'reg_no', 'reg_no')->orderby('enrollments.course_id');
    }

    public function courseRegistrationPerSemester() {
        return $this->hasMany(Enrollment::class, 'reg_no', 'reg_no')->orderBy('level', 'asc')->orderBy('semester', 'desc')->groupBy('request_id');
    }

    public function results() {
        return $this->hasMany(Result::class, 'reg_no', 'reg_no');
    }


    public static function myClass()
    {
        return self::active()->set;
    }

    public static function allStudents()
    {
        $students = self::myClass();
        return $students?->students;
    }

   
    public static function myAdvisor()
    {
        return Staff::where('id', self::myClass()?->advisor_id)->first();
    }

    public static function active()
    {
        return self::where('id', auth()->id())->first();
    }


    public function courses() {
        return $this->hasMany(Enrollment::class, 'reg_no', 'reg_no');
    }


    public static function _create($data) {
        $obj = new Student();
        $data = Arr::only($data, $obj->fillable);
        return self::create($data);
    }
    

    public function carryoverCourses() : array {

        $results = Result::where('reg_no', $this->reg_no)
            ->where('remark', 'FAILED')
            ->get();

        $failed = [];
        
        foreach($results as $result) {
            $fetchPassedResults = Result::where('course_id', $result->course_id)
                ->where('reg_no', $result->reg_no)
                ->where('remark', 'PASSED')
                ->first();

            if (!$fetchPassedResults) {
                $failed[] = $result;
            }

        }
        
        return $failed;
    }



    public function getMaterials() {
        $materials = Enrollment::where('reg_no', $this->reg_no)
            ->join('courses', 'courses.id', '=', 'enrollments.course_id')
            ->join('materials', 'materials.course_code', '=', 'courses.code')
            ->orderBy('materials.created_at', 'DESC')
            
            ->paginate(4);
            
        return $materials;

    }


   



}
