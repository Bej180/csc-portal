<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Student;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Result extends Model
{
    use HasFactory;



    protected $fillable = [
        'semester',
        'session',
        'uploaded_by',
        'updated_by',
        'course_id',
        'reg_no',
        'remark',
        'score',
        'level',
        'grade',
        'exam',
        'test',
        'lab',
        'reference_id'
    ];



    private $standard_grading = ["A" => 70, "B" => 60, "C" => 50, "E" => 45, "D" => 40, "F" => 0];




    public function course()
    {
        return $this->hasOne(Course::class, 'id', 'course_id');
    }





    public function student()
    {
        return $this->hasOne(Student::class, 'reg_no', 'reg_no');
    }




    public function grading()
    {
        return $this->hasOne(Grading::class, 'id', 'grading_id');
    }





    public function getGrading()
    {
        $score = $this->score;

        $grading = $this->standard_grading;

        try {

            if ($grading_system = $this->grading?->grading_system) {
                $grading = json_decode($grading_system, true);
            }
        } catch (\Exception $e) {
        }

        $n = count($grading) - 1;

        foreach ($grading as $grade => $range) {
            if ($score >= $range) {
                return [
                    'alphaGrade' => $grade,
                    'grade' => $n,
                    'score' => $score,
                    'exam' => $this->exam,
                    'test' => $this->test,
                    'lab' => $this->lab,
                    'remark' => $grade == 'F' ? 'Failed' : 'Passed'
                ];
            }
            $n--;
        }

        return $grading;
    }

    public function updateCGPA()
    {

        $this->student->cgpa = $this->student->calculateCGPA();
        $this->student->save();
    }



    public static function xcalculateGPA($records, $semester, $session)
    {

        $totalCredits = 0;
        $totalQualityPoints = 0;

        foreach ($records as $course) {
            $result = Result::where('semester', '=', $semester)
                // ->with('grading', 'course')
                ->where('session', '=', $session)
                ->where('course_id', '=', $course->course_id)
                ->where('reg_no', '=', $course->reg_no)
                ->get()->last();

            $credits = $result->course->units;

            $gradingSystem = $result->getGrading();

            $grade = $gradingSystem['grade'];

            $qualityPoints = $grade * $credits;
            $totalCredits += $credits;
            $totalQualityPoints += $qualityPoints;
        }
        $gpa = 0;
        if ($totalCredits > 0) {
            $gpa = $totalQualityPoints / $totalCredits;
        }
        return [
            'TGP' => $totalQualityPoints,
            'TNU' => $totalCredits,
            'GPA' => round($gpa, 2)
        ];
    }


    public static function studentGPA_($reg_no, $semester, $session)
    {



        $results = Result::where('reg_no', $reg_no)
            ->with('course')
            ->where('semester', $semester)
            ->where('session', $session);

        $gradePoints = $results->sum('grade_points');
        $units = $results->sum('units');
        $gpa = $units === 0 ? 0 : round($gradePoints / $units, 2);


        // return [
        //     'TGP' => $gradePoints,
        //     'TNU' => $units,
        //     'GPA' => $gpa
        // ];

        $result = $results->first();

        $level = $result?->course?->level;

        $semester_swap = [
            'HARMATTAN' => 'RAIN',
            'RAIN' => 'HARMATTAN',
        ];

        if ($semester === 'HARMATTAN') {
            $level -= 100;
        }

        $previous_semester = $semester_swap[$semester];
        $current = ['TGP' => $gradePoints, 'TNU' => $units, 'GPA' => $gpa];
        $previous = ['TGP' => 0, 'TNU' => 0, 'GPA' => 0.0];

        $findResults = Result::where('reg_no', $reg_no)
            ->where('semester', $previous_semester)
            ->where('level', $level)->get();



        // dd($level, $previous_semester);
        if ($findResults) {
            $previous['TGP'] = $findResults->sum('grade_points');
            $previous['TNU'] = $findResults->sum('units');
            if ($previous['TNU'] > 0) {
                $previous['GPA'] = round($previous['TGP'] / $previous['TNU'], 2);
            }
        }




        return compact('current', 'previous');


        $enrollments = Enrollment::where('enrollments.reg_no', $reg_no)
            ->where('enrollments.semester', $semester)
            ->where('enrollments.session', $session)
            ->get()
            ->map(function ($enrollment) {
                $result = Result::where('semester', $enrollment->semeser)
                    ->where('session', $enrollment->session)
                    ->where('reg_no', $enrollment->reg_no)

                    ->first();
                $enrollment->result = '-';

                if ($result) {
                    $enrollment->result = match ($result->status) {
                        'approved' => $result,
                        default => 'IN',
                    };
                }




                return $enrollment;
            });


        // $enrollments = Enrollment::leftJoin('results', function ($join) {
        //     $join->on('results.reg_no', '=', 'enrollments.reg_no')
        //         ->on('results.semester', '=', 'enrollments.semester')
        //         ->on('results.session', '=', 'enrollments.session')
        //         ->where('results.status', '=', 'approved');
        // })
        //     ->where('enrollments.reg_no', $reg_no)
        //     ->where('enrollments.semester', $semester)
        //     ->where('enrollments.session', $session)->get();

        // $results = $enrollments->map(function ($sessions) {
        //     return $sessions->map(function ($semesterResults) {
        //         $totalUnits = $semesterResults->sum('course.units');
        //         $totalGradePoints = $semesterResults->sum('grade_points'); // Assuming grade_points is a field in Result model
        //         return [
        //             'results' => $semesterResults,
        //             'totalUnits' => $totalUnits,
        //             'totalGradePoints' => $totalGradePoints
        //         ];
        //     })->put('sessionTotals', [
        //         'totalUnits' => $sessions->flatten(1)->sum('course.units'),
        //         'totalGradePoints' => $sessions->flatten(1)->sum('grade_points')
        //     ]);
        // });

        dd(self::class);


        return self::calculateGPA($enrollments, $semester, $session);
    }

    public static function studentGPA($reg_no, $semester, $session)
    {
        // Fetch results for the current semester and session
        $currentResults = Result::where('reg_no', $reg_no)
            ->where('semester', $semester)
            ->where('session', $session)
            ->get();

        $currentTGP = $currentResults->sum('grade_points');
        $currentTNU = $currentResults->sum('units');
        $currentGPA = $currentTNU ? round($currentTGP / $currentTNU, 2) : 0;

        $current = [
            'TGP' => $currentTGP,
            'TNU' => $currentTNU,
            'GPA' => $currentGPA,
        ];

        // Determine the previous semester
        $previousSemester = $semester === 'HARMATTAN' ? 'RAIN' : 'HARMATTAN';
        $previousLevel = $semester === 'HARMATTAN' ? $currentResults->first()?->course?->level - 100 : $currentResults->first()?->course?->level;

        // Fetch results for the previous semester and level
        $previousResults = Result::where('reg_no', $reg_no)
            ->where('semester', $previousSemester)
            ->where('session', $session)
            ->where('level', $previousLevel)
            ->get();

        $previousTGP = $previousResults->sum('grade_points');
        $previousTNU = $previousResults->sum('units');
        $previousGPA = $previousTNU ? round($previousTGP / $previousTNU, 2) : 0;

        $previous = [
            'TGP' => $previousTGP,
            'TNU' => $previousTNU,
            'GPA' => $previousGPA,
        ];

        return compact('current', 'previous');
    }


    public static function studentPreviousSemesterGPA($reg_no, $semester, $session)
    {
        $splitSession = explode('/', $session);
        $mapToInt = array_map(fn ($year) => (int) $year, $splitSession);
        list($start, $end) = $mapToInt;

        if ($semester === 'HARMATTAN') {
            $start--;
            $end--;
            $semester = 'RAIN';
        } else {
            $semester = 'HARMATTAN';
        }

        $enrollments = Enrollment::join('results', function ($join) {
            $join->on('results.reg_no', '=', 'enrollments.reg_no')
                ->where('results.status', '=', 'approved');
        })
            ->where('enrollments.reg_no', $reg_no)
            ->where('enrollments.semester', $semester)
            ->where('enrollments.session', $session)->get();

        return self::calculateGPA($enrollments, $semester, $session);
    }




    public static function studentPreviousSessionGPA($reg_no, $semester, $session)
    {
        $splitSession = explode('/', $session);
        $mapToInt = array_map(fn ($year) => (int) $year, $splitSession);
        list($start, $end) = $mapToInt;


        if ($semester === 'HARMATTAN') {
            $start--;
            $end--;
        } else {
            $semester = 'HARMATTAN';
        }

        $enrollments = Enrollment::where('reg_no', $reg_no)
            ->where('semester', $semester)
            ->where('session', $session)->get();

        return self::calculateGPA($enrollments, $semester, $session);
    }

    /**
     * Get Awaiting results for a given level
     * @param int $level
     * @return Result
     */



    public static function getLevelAwaitingResults(int $level)
    {

        return self::where('results.level', $level)
            ->where('results.status', 'pending')
            ->join('courses', 'courses.id', '=', 'results.course_id')
            ->leftJoin('students', 'students.reg_no', '=', 'results.reg_no')
            ->leftJoin('users', 'users.id', '=', 'students.id')
            ->orderBy('courses.code')->get(['results.*', 'courses.code', 'users.name']);
    }



    /**
     * pending results awaiting admin approval
     */
    public static function awaitingResults($semester = null, $session = null)
    {
        $results = self::where('results.status', 'pending')
            ->join('courses', 'courses.id', '=', 'results.course_id')
            ->groupBy(['session', 'courses.level', 'courses.code']);

        if ($session && $semester) {
            return $results->where('session', $session)->where('semester', $semester);
        }
        return $results;
    }






    public function groupResultsByLevelSemesterSession($reg_no = null)
    {
        $reg_no ??= auth()->user()->student->reg_no;
        // Fetch all results for the given student ID
        $results = Result::where('reg_no', $reg_no)->get();

        $groupedResults = $results->groupBy(['level', 'semester', 'session']);

        $groupedResults = DB::table('results')
            ->select('level', 'semester', 'session', DB::raw('count(*) as count'))
            ->where('reg_no', $reg_no)
            ->groupBy('level', 'semester', 'session')
            ->get();


        $records = [];




        foreach ($groupedResults as $group) {
            $level = $group->level;
            $semester = $group->semester;
            $session = $group->session;

            Arr::set($records, "$session.$semester", '');

            $records[$session][$semester] = $this->calculateGPAForGroup($reg_no, $level, $semester, $session);
        }

        return $records;
    }

    public function calculateGPAForGroup($reg_no, $level, $semester, $session)
    {
        // Fetch results for the student for the specified level and session
        $results = Result::join('courses', 'courses.id', '=', 'results.course_id')

            ->where('results.reg_no', $reg_no)
            ->where('results.session', $session)
            ->where('results.semester', $semester);

        $TGP = $results->sum('results.grade_points');
        $TNU = $results->sum('results.units');
        $GPA = round($TGP / $TNU, 2);

        return compact('TGP', 'TNU', 'GPA');
    }

    public function uploader()
    {
        return $this->hasOne(User::class, 'id', 'uploaded_by');
    }
    public function updater()
    {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }

    public function setGradings()
    {
        $course = $this->course;

        if ($course) {
            $this->units = $course->units;
            if ($course->has_practical) {

                if (!$this->lab) {
                    $this->grade = 'F';
                    $this->grade_points = 0;
                    $this->remark = 'FAILED';
                    return $this;
                }
            }

            $this->score = $this->getScore();
            $this->grade_points = $this->getGradePoints();
            $this->grade = $this->getGradeText();
            $this->remark = $this->getRemark();
        }
        return $this;
    }

    public function getGradeText()
    {
        return match (true) {
            $this->score > 69 => 'A',
            $this->score > 59 => 'B',
            $this->score > 49 => 'C',
            $this->score > 44 => 'D',
            $this->score > 39 => 'E',
            default => 'F',
        };
    }

    public function getGrade()
    {
        return match (true) {
            $this->score > 69 => 5,
            $this->score > 59 => 4,
            $this->score > 49 => 3,
            $this->score > 44 => 2,
            $this->score > 39 => 1,
            default => 0,
        };
    }

    public function getGradePoints()
    {
        return $this->getGrade() * $this->units;
    }


    public function getRemark()
    {
        if ($this->course->has_practical && !$this->lab) {
            return 'FAILED';
        }
        return $this->score > 39 ? 'PASSED' : 'FAILED';
    }

    public function getScore()
    {
        $score = (int) $this->exam;
        $score += (int) $this->test;
        $score += (int) $this->lab;

        return $score;
    }


    /**
     * Save results to the database
     *
     * @param $session (string) - session of the result to be saved 
     * @param $students (array) - list of students whose results are to be saved 
     * @param $course_id (int) - course id of the result to be saved 
     * @param $status (string) - status of the result 

     * @return string|boolean - string - error message, boolean success of update/upload
     **/


    public static function uploadOrUpdateResults(
        string $session,
        array $students,
        int $course_id,
        string $status = 'PENDING'
    ) {
        $user = request()->user();
        if (!$user) {
            $user = auth()->user();
        }


        $reference_id = generateToken('results.reference_id');
        $course = Course::find($course_id);


        foreach ($students as $student) {

            $result = Result::firstOrNew([
                'course_id' => $course_id,
                'session' => $session,
                'reg_no' => $student['reg_no']
            ]);

            if ($course->has_practical && $result->status === 'INCOMPLETE') {
                return 'A technologist has uploaded lab score results for this course. You need to approve it before continuing';
            } else if ($result->status === 'APPROVED') {
                return 'You cannot update or add this result because it is already approved previously uploaded ones';
            } else if (in_array($result->status ?? 'new', ['READY', 'INCOMPLETE'])) {
                return 'Results have already been uploaded';
            }

            if ($result->uploaded_by) {
                // existing records
                $result->updated_by = $user->id;
            } else {
                // new records
                $result->reference_id = $reference_id;
                $result->uploaded_by = $user->id;

                $result->units = $course->units;
                $result->level = $course->level;
                $result->reference_id = $reference_id;
                $result->semester = $course->semester;
            }

            $result->status = $status;
            $lab = $student['lab'] ?? 0;
            $exam = $student['exam'] ?? 0;
            $test = $student['test'] ?? 0;

            $result->lab = (int) $lab;
            $result->exam = (int) $exam;
            $result->test = (int) $test;
            $result
                ->setGradings()
                ->save();
        }

        return true;
    }

    public static function scoreToGradeText(int $score)
    {
        return match (true) {
            $score > 69 => 'A',
            $score > 59 => 'B',
            $score > 49 => 'C',
            $score > 44 => 'D',
            $score > 39 => 'E',
            default => 'F',
        };
    }




    public static function changeStatus(string $reference_id, string $status): Course
    {

        $results = Result::where('reference_id', '=', $reference_id)->with('course');

        $results->update(compact('status'));


        $getResults = $results->get();


        // re-calculate the cpga of the results uploaded
        foreach ($getResults as $result) {
            $result->updateCGPA();
        }
        if ($status === 'APPROVED') {
            //Email(ApprovedResultNotification($uploader, $firstResult->course->code, $date->format('d/m/Y')), $uploader);
        }

        return $results->first()->course;
    }


    public static function approveResults(string $reference_id): ?Course
    {
        return self::changeStatus($reference_id, 'APPROVED');
    }


    public static function disapproveResults(string $reference_id): ?Course
    {
        return self::changeStatus($reference_id, 'PENDING');
    }
}
