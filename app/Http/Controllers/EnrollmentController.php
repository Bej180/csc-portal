<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Result;
use App\Models\Student;
use App\Models\ActivityLog;
use App\Models\Enrollment;
use App\Models\AcademicSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EnrollmentController extends Controller
{

    // public function list_of_enrolled_students(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'session' => 'required',
    //         'semester' => 'required',
    //         'course_id' => 'required|exists:enrollments',
    //     ], [
    //         'session.required' => 'Session must be provided',
    //         'semester.required' => 'Session must be provided',
    //         'course.required' => 'Course must be provided',
    //         'course_id.exists' => 'No student registered for this course',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'errors' => $validator->errors(),
    //         ], 400);
    //     }
    //     $semester = $request->get('semester');
    //     $session = $request->get('session');
    //     $course_id = $request->course_id;

    //     $result = Result::where('course_id', $course_id)
    //         ->where('semester', $semester)
    //         ->where('session', $session)
    //         ->with('uploader')->first();

    //     if ($result && !in_array($result->status, ['incomplete', 'ready'])) {
    //         $uploader = $result->uploader?->name;
    //         if ($result->uploader->id === auth()->id()) {
    //             $uploader = 'you';
    //         }

    //         return response()->json([
    //             'error' => 'Result has already been uploaded by ' . $uploader,
    //         ], 400);
    //     }


    //     $enrolledStudents = Enrollment::students($semester, $session, $request->course_id);

    //     if (!count($enrolledStudents)) {
    //         return response()->json([

    //             'error' => "No student found to have enrolled in the course in $semester semeseter of $session academic session",
    //         ], 400);
    //     }

    //     return $enrolledStudents;
    // }






    /***
     * Handles Student Course Registration
     */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'courses' => 'required|array',
            'level' => 'required',
            'semester' => 'required|in:HARMATTAN,RAIN'
        ], [
            'courses.required' => 'Courses to be registered were not provided',
            'level.level' => 'Your level is required',
            'semester' => 'Academic semester not provided',
            'courses.array' => 'Invalid course selection'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $requestedCourses = $request->input('courses');
        $session = $request->input('session');
        $semester = $request->input('semester');
        $level = $request->input('level');

        // get the instances of all the to courses to be enrolled in
        $courses = Course::whereIn('id', $requestedCourses)->get();



        $courses_ = [];
        $user = auth()->user();

        // Student Reg Number
        $reg_no = $user->student->reg_no;



        $request_id = generateToken('enrollments.request_id');

        foreach ($courses as $course) {

            // Check if course has prerequisites courses
            // if yes, check if he has passed them 

            if ($course->prerequisites) {
                $slitPrerequisites = implode(',', $course->prerequisites);
                $prerequisitesCourses = Course::whereIn('id', $slitPrerequisites)->get();

                foreach ($prerequisitesCourses as $preCourse) {

                    $result = Result::where('course_id', $preCourse->id)
                        ->where('reg_no', $reg_no)
                        ->where('remark', 'PASSED')->first();

                    if (!$result) {
                        return response()->json([
                            'error' => 'You are not allowed to register ' . $course->name . ' until you settle ' . $preCourse->name
                        ], 400);
                    }
                }
            }


            $courses_[] = [
                'course_id' => $course->id,
                'reg_no' => $reg_no,
                'level' => $level,
                'semester' => $semester,
                'session' => $session,
                'request_id' => $request_id
            ];
        }


        if (count($requestedCourses) !== count($courses_)) {
            return response()->json([
                'error' => 'Failed to register courses',
            ], 400);
        }


        Enrollment::insert($courses_);

        // Log Activity 
        ActivityLog::logCourseRegistrationActivity($user, "registered $session $semester courses");

        // Get the inserted enrollment records
        $enrollments = Enrollment::where('request_id', $request_id)
            ->join('courses', 'courses.id', '=', 'enrollments.course_id')
            ->orderBy('enrollments.level', 'asc')
            ->orderBy('enrollments.semester', 'desc')
            ->get([
                'enrollments.request_id',
                'enrollments.level',
                'enrollments.semester',
                'courses.code',
                'courses.option',
                'courses.name',
                'session',
                'units'
            ]);

        $data = [
            'semester' => $semester,
            'session' => $session,
            'level' => $level,
            'courses' => $enrollments,
            'totalUnits' => $enrollments->sum('units')
        ];

        return response()->json([
            'data' => $data,
            'success' =>  "You have successfully registered " . count($courses_) . " courses for $session $semester Semester",

        ]);
    }









    public function index_courses_for_registration(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'level' => 'required',
            'semester' => 'required',
            'session' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 401);
        }
        $level = $request->level;
        $semester = $request->semester;
        $session = $request->session;


        $findSession = AcademicSession::where('name', $session)->first();

        if ($findSession) {
            $semester_column = strtolower($semester) . '_course_registration_status';
            $status = $findSession->$semester_column;



            if ($status === 'OPEN') {
                $courses = Course::active()->where('level', '=', $level)
                    ->where('semester', '=', $semester)
                    ->orderBy('option')
                    ->get();


                $minUnits = config("courseunits.$level.$semester.min", 18);
                $maxUnits = config("courseunits.$level.$semester.max", 24);

                $student = $request->user()->student;

                // add borrowed units to maximum units
                $maxUnits += (int) $student->borrowed_units;

                return response()->json(compact('maxUnits', 'minUnits', 'courses'));

                return response()->json($courses);
            }
            return response()->json([
                'error' => 'Course registration has closed for the session',
            ], 401);
        }
        return response()->json([
            'error' => 'Academic Session not found'
        ], 401);
    }




    public function enrollments(Request $request)
    {
        $student = $request->user()->student;

        return  Enrollment::where('reg_no', $student->reg_no)
            ->join('courses', 'courses.id', '=', 'enrollments.course_id')
            ->orderBy('enrollments.level', 'asc')
            ->orderBy('enrollments.semester', 'desc')
            ->get([
                'enrollments.request_id',
                'enrollments.level',
                'enrollments.semester',
                'courses.code',
                'courses.option',
                'courses.name',
                'session',
                'units'
            ])
            ->groupBy('request_id')
            ->map(function ($enrollments) {
                $first = $enrollments->first();

                return [
                    'level' => $first->level,
                    'semester' => $first->semester,
                    'enrollment_id' => $first->request_id,
                    'session' => $first->session,
                    'totalUnits' => $enrollments->sum('units'),
                    'courses' => $enrollments
                ];
            });
    }



    public function index(Request $request)
    {
        $user = $request->user();
        $account = $user->account();

        $enrollments = Enrollment::enrollments($user->student);

        if (!count($enrollments)) {
            return response()->json([
                'error' => 'You are not enrolled to any course yet',
            ], 400);
        }




        return compact('enrollments', 'account');
    }


    public function getStudentEnrollments(Request $request)
    {

        if ($reg_no = $request->reg_no) {

            return Enrollment::getStudentEnrollments($reg_no);
            
        } else {
            return response()->json([
                'error' => "Student's Registration Number not provided"
            ], 400);
        }
    }




    public function list_of_enrolled_students(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session' => 'required',
            'course_id' => 'required|exists:enrollments',
        ], [
            'session.required' => 'Session must be provided',
            'course.required' => 'Course must be provided',
            'course_id.required' => 'Course is missing',
            'course_id.exists' => 'No student registered for this course',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }
        
        $session = $request->get('session');
        $course_id = $request->course_id;
        $course = Course::where('id', $course_id)->whereNull('deleted_at')->first();
        $semester = $course->semester;

        

        $result = Result::where('course_id', $course_id)
            // ->where('semester', $semester)
            ->where('session', $session)
            ->first();




        $enrolledStudents = Enrollment::students($semester, $session, $request->course_id);
        
        
        if ($first = $enrolledStudents->first()) {
            
            $units = $first->course->units;

            return response()->json([
                'session' => $session,
                'semester' => $semester,
                'level' => $first->level,
                'code' => $first->course->code,
                'units' => $units,
                'course_id' => $first->course_id,
                'has_practical' => !(!$first->course->has_practical),
                'unitWord' => $units . ' ' . str_plural('unit', $units),
                'name' => $first->course->name,
                'students' => $enrolledStudents,
                'status' => $result ? $result->status : 'NEW'
            ]);
        }

        return response()->json([

            'error' => "No student found to have enrolled in the course in $semester semeseter of $session academic session",
        ], 400);
    }
}
