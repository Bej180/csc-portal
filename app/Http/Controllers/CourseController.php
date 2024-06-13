<?php
/*
    create
    index
    show
    delete
    update




*/

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Course;
use App\Models\Result;
use App\Models\Student;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\AcademicRecord;
use App\Models\AcademicSession;
use App\Models\ActivityLog;
use App\Models\CourseAllocation;
use App\Models\Enrollment;
use App\Models\Staff;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function __constructor()
    {
        $this->middleware('role:admin,staff');
    }
    protected $fillable = [
        'name',
        'code',
        'semester',
        'units',
        'exam',
        'test',
        'lab',
        'prerequisites',
        'level',
        'reference_id',
        'mandatory',
        'image',
        'outline'
    ];






































    public function edit(Request $request)
    {
        $request->validate([
            'course_id' => 'required'
        ]);
        $course = Course::find($request->course_id)?->get()?->first();
        if (!$course) {
            return redirect()->back()->with('error', 'Course not found');
        }

        return view('pages.course.edit', [
            'course' => $course
        ]);
    }



    public function index()
    {
        $courses = Course::where('id', '>', 1)->paginate(15);
        return view('courses.index', compact('courses'));
    }

    

    /**
     * Display form for course registration
     */

    public function course_form()
    {
        return view('pages.student.course-registration-borrow-courses');
    }







    public function registerCourse()
    {
        return view("student.register-courses");
    }




    public function listRegisteredCourses()
    {
        $student = Student::auth();
        $user = $student->user;
        $courses = $student->courses;
        return view('courses.list-of-registered-courses', compact('courses', 'student', 'user'));
    }



    public function show(Course $course)
    {

        $result = Result::where('course_id', $course->id)->where('reg_no', auth()->user()->student->reg_no)->first();
        return view('pages.student.view-course', compact('course', 'result'));
    }



    public function addCourseForm()
    {

        return view('pages.courses.insert');
    }


    public function createCourse(Request $request)
    {

        $validator =  Validator::make($request->all(), [
            'name' => 'required',
            'code' => ['required', Rule::unique('courses'), 'regex:/([a-zA-Z]+){3,3}\s*([0-9]+){3,3}/'],
            'semester' => 'required|in:RAIN,HARMATTAN',
            'level' => 'required|in:100,200,300,400,500,600',
            'lab' => 'sometimes|numeric',
            'exam' => 'required|numeric',
            'test' => 'sometimes|numeric',
            'prerequisites' => 'sometimes',
            //'check' => 'required',
            'option' => 'nullable|in:ELECTIVE,COMPULSARY'
        ], [
            'name.required' => 'Course Title is required',
            'code.required' => 'Couse Code is required',
            'semester.required' => 'Semester is required',
            'level.required' => 'Level is required',
            'lab.required' => 'Lab Units is required',
            'code.unique' => 'Course Code already exists',
            'code.numeric' => 'Invalid course code',
            'semester.in' => 'Semester must be either RAIN or HARMATTAN',
            'level.in' => 'Invalid level',
            //'check.required' => 'You need to confirm that the data you provided are valid',
            'option.in' => 'Select option',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'errors' => $validator->errors()], 400);
        }

        $formData = $validator->validated();


        if ($image_path = UploaderController::uploadFile('image')) {
            $formData['image'] = $image_path;
        }


        $data = Arr::only($formData, $this->fillable);
        $data['units'] = $request->lab + $request->exam + $request->test;
        $data['code'] = trim(strtoupper($data['code']));
        $data['name'] = ucfirst(trim($data['name']));
        $data['reference_id'] = $request->level + ($request->semester === 'RAIN' ? 2 : 1);
        $course = Course::create($data);

        return response()->json($course);
    }


    private function cloneCourse(Course $course, array $data)
    {

        $course_data = array_merge([
            "name" => $course->name,
            "code" => $course->code,
            "outline" => $course->outline,
            "mandatory" => $course->mandatory,
            "reference_id" => $course->reference_id,
            "semester" => $course->semester,
            "status" => $course->status,
            "level" => $course->level,
            "exam" => $course->exam,
            "test" => $course->test,
            "lab" => $course->lab,
            "units" => $course->units,
            "prerequisite" => $course->prerequisite,
        ], $data);

        return Course::create($course_data);
    }


    public function updateCourse(Request $request)
    {


        $formData =  $request->validate([
            'id' => 'required',
            'name' => 'required',
            'code' => ['required', 'regex:/([a-zA-Z]+){3,3}\s*([0-9]+){3,3}/'],
            'semester' => 'required|in:RAIN,HARMATTAN',
            'level' => 'required|in:100,200,300,400,500,600',
            'lab' => 'required|regex:/^(\d+)$/',
            'exam' => 'required|regex:/^(\d+)$/',
            'test' => 'required|regex:/^(\d+)$/',
            'prerequisites' => 'sometimes',
            'check' => 'required',
            'mandatory' => 'required|in:1,0',
            'outline' => 'sometimes'
        ], [
            'name.required' => 'Course Title is required',
            'code.required' => 'Couse Code is required',
            'code.unique' => 'Course Code alread exists',
            'code.regex' => 'Invalid course code',
            'semester.in' => 'Semester must be either RAIN or HARMATTAN',
            'level.in' => 'Invalid level',
            'check.required' => 'You need to confirm that the data you provided are valid',
            'mandatory.in' => 'Select option',
            'image' => 'sometimes|image|max:2048', // Ensure 'image' is present and is an image file (up to 2MB)
        ]);
        $course = Course::find($request->id);

        $image_path = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $uploadedImage = $request->file('image');
            $filename = Str::random(10) . '.' . $uploadedImage->getClientOriginalExtension();
            $image_path = "public/images/$filename";
            $uploadedImage->storeAs('public/images', $filename);
        } elseif ($request->has('image')) {
            // The image data is in base64 format
            $base64Image = $request->input('image');
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
            $filename = Str::random(10) . '.png'; // You can adjust the file extension according to your image format
            $image_path = storage_path('app/public/images/' . $filename);
            file_put_contents($image_path, $imageData);
        }
        if ($image_path) {
            $formData['image'] = $image_path;
        }



        $data = Arr::only($formData, $this->fillable);

        $columnsToCompare = ['units', 'code', 'test', 'lab', 'exam'];
        $data['units'] = $request->lab + $request->exam + $request->test;
        $data['code'] = trim(strtoupper($data['code']));
        $data['name'] = ucfirst(trim($data['name']));
        $data['reference_id'] = $request->level + ($request->semester === 'RAIN' ? 2 : 1);

        $columnChanged = false;

        foreach ($columnsToCompare as $column) {
            if ($course->$column !== $data[$column]) {
                $columnChanged = true;
                break;
            }
        }




        if ($columnChanged) {
            $course->status = 'inactive';
            $course->update();
            $course = $this->cloneCourse($course, $data);
        } else {
            $course->update($data);
        }



        if ($course) {
            return redirect("/admin/courses?level={$request->level}&semester={$request->semester}&course_id={$course->id}")->with('success', "Course has been updated successfully");
        } else {
            return redirect()->back()->with('error', 'Failed to update course');
        }
    }





    public function api_getCourses(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'level' => 'required|exists:courses',
            'semester' => 'required|exists:courses'
        ], [
            'level.required' => 'Level is required',
            'semester.required' => 'Semester is required',
            'level.exists' => 'No course for level found',
            'semester.exists' => 'No course for semester found'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 401);
        }

        $level = $request->level;
        $semester = $request->semester;

        $courses =  Course::getCourses($level, $semester);

        return response()->json($courses);
    }


    public function api_scan(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'semester' => 'required',
        ], [
            'code.required' => 'Course Code is required',
            'semester.required' => 'Semester not provided',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 401);
        }

        $code = $request->code;
        $semester = $request->semester;

        // this normalizes the code: eg CSC202 normalizes to CSC 202
        $code = preg_replace('/(\D+)(\d+)/', "$1 $2", $code);

        $courses = Course::where('code', $code)->get();

        if (count($courses) === 0) {
            return response()->json(['error' => 'Course not found'], 404);
        }
        $saved_semester = $courses->first()->semester;
        $level = $courses->first()->level;

        if ($saved_semester !== $semester) {
            return response()->json([
                'error' => "You cannot borrow '$code' this semester. It's a '$saved_semester' semester course."
            ], 404);
        }
        return response()->json($courses);
    }



    public function api_getEnrolledCourses(Request $request)
    {
        $session = $request->session;
        $semester = $request->semester;
        $request->validate([
            'session' => 'required',
            'semester' => 'required|in:RAIN,HARMATTAN'
        ]);

        return Enrollment::where('session', $session)
            ->where('semester', '=', $semester)->with('course')->get()->unique('course_id');
    }

    public function getCourseById(Request $request)
    {
        if (!$request->course_id) {
            return response()->json([
                'error' => 'Course not found',
            ], 400);   
        }
        $course_id = $request->get('course_id');

        return Course::find($course_id);
    }







    // New

    public function viewEnrollments(Request $request)
    {

        $semester = $request->semester;
        $level = $request->level;
        $session = $request->session;
        $user = auth()->user();
        $student = $user->student;



        if (!$session || !$semester) {
            return redirect('/courses');
        }
        $enrollments = Course::getEnrollments($semester, $session);

        if (!count($enrollments)) {
            return redirect('/courses')->with('error', 'Enrollment history doesnt exist');
        }

        $level = $enrollments->first()->level;


        return view('pages.student.course-registration-details', compact('semester', 'level', 'student', 'user', 'session', 'enrollments'));
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
                    ->get()
                    ->groupBy('option');

                    
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


    /**
     * 
     * Returns arrays of prerequisite courses
     */
    public function index_prerequisites(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'prerequisites' => 'required',
            'semester' => 'required',
            'level' => 'required',
        ], [
            'prerequisites.required' => 'Prerequisite course code or course title required',
            'level.required' => 'Course Level is required to determine prerequisite',
            'semester.required' => 'Semester is required to determine prerequisite',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 401);
        }

        $prerequisites = $request->prerequisites;
        $semester = $request->semester;

        // this normalizes the code: eg CSC202 normalizes to CSC 202
        $prerequisites = preg_replace('/(\D+)(\d+)/', "$1 $2", $prerequisites);
        $prerequisites = preg_replace('/\s+/', '%', $prerequisites);

        $courses = Course::where('code', 'LIKE', "%$prerequisites%")
            ->where('level', '<', $request->level)
            ->where('semester', $request->semester)
            ->where('status', 'active')
            ->get();
        // ->orWhere('name', 'LIKE', "%$prerequisites%")
        //->get();

        if (count($courses) === 0) {
            return response()->json([
                'error' => 'No prerequisite course found.'
            ], 404);
        }

        return response()->json($courses);
    }



    /**
     * @Route("/app/admin/courses/index")
     * 
     * Shows all courses
     */
    public function index_courses(Request $request)
    {
        

        $courses_query = Course::query();
        $courses_query->with('cordinator.user');
        $courses_query->where('status', 'active');

        if ($semester = $request->semester) {
            $courses_query->where('semester', $semester);
        }
        if ($level = $request->level) {
            $courses_query->where('level', $level);
        }
        if ($search = $request->search) {
            $courses_query->where(function($query) use ($search) {
                $search = preg_replace('/\s+/', '%', $search);
                $query->where('name', 'LIKE', "%$search%")
                    ->orWhere('code', 'LIKE', "%$search%");
            });
        }

        $courses = $courses_query->paginate(10);
       

        if (!$courses) {
            return response()->json([
                'error' => 'Courses not found',
            ], 401);
        }


        return response()->json($courses);
    }





    /**
     * @Route("/app/admin/courses/search")
     * 
     * Shows all courses
     */
    public function search_courses(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'search' => 'required'
        ], [
            'search.required' => 'Course code or title is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $search = $request->search;

        // this normalizes the code: eg CSC202 normalizes to CSC 202
        $search = preg_replace('/(\D+)(\d+)/', "$1 $2", $search);
        $search = preg_replace('/\s+/', '%', $search);

        $courses_query = Course::where('status', 'active')
            ->where(function ($query) use ($search) {
                $query->where('code', 'LIKE', "%$search%");
                $query->orWhere('name', 'LIKE', "%$search%");
            });

        $courses = $courses_query->paginate(10);
        if (count($courses) === 0) {
            return response()->json([
                'error' => 'Course not found',
            ], 404);
        }

        return response()->json($courses);


        return;
        $courses = $courses_query->paginate(10);

        if (!$courses) {
            return response()->json([
                'error' => 'Courses not found',
            ], 401);
        }

        return response()->json($courses);
    }


    public function delete_course(Request  $request) {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
        ], [
            'course_id.required' => 'Course ID required',
            'course_id.exists' => 'Course was not found, may it has already been deleted',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        if ($request->user()->role !== 'admin') {
            return response()->json([
                'error' => 'Only admin can delete course',
            ], 400);
        }

        $course = Course::find($request->course_id);
        $course->delete();
        return response()->json([
            'success' => "Course deleted successfully",
        ]);
    }



    /***
     * Handles Student Course Registration
     */

    public function doRegister(Request $request)
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

        // foreach ($courses_ as $course) {
        //     Enrollment::create(Enrollment::getFillables($course));
        // }

        // Log Activity 
        ActivityLog::logCourseRegistrationActivity($user, "registered $session $semester courses");

        return response()->json([
            'semester' => $semester,
            'session' => $session,
            'level' => $level,
            'success' =>  "You have successfully registered " . count($courses_) . " courses for $session $semester Semester",
            // 'redirect' => "/course-registration-details?semester=$semester&level=$level"
            //http://127.0.0.1:8000/course-registration-details?semester=HARMATTAN&session=2018%2F2019
        ]);
    }



    public function allocate_to_staff(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'staff_ids' => 'required',
            'course_id' => 'required|exists:courses,id',
        ], [
            'staff_ids.required' => 'Staff to allocate course to was not provided',
            'staff_ids.array' => 'Failed to allocate',
            'course_ids.required' => 'Course id to be allocated was not provided',
            'course_ids.exists' => 'Course was not found',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }
        $course = Course::find($request->course_id);
        $staff_ids = $request->staff_ids;



        $insert = CourseAllocation::create([
            'staff_id' => $staff_ids,
            'course_id' => $course->id
        ]);
        $cordinator = Staff::where('id', $staff_ids)->with('user')->first();


        return response()->json([
            'success' => $course->code . ' has been assigned to ' . $cordinator->user->name,
            'data' => $cordinator
        ]);
    }

    public function makeCourseCordinator(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'staff_id' => 'required',
            'course_id' => 'required|exists:courses,id',
        ], [
            'staff_ids.required' => 'Staff to allocate course to was not provided',
            'staff_ids.array' => 'Failed to allocate',
            'course_ids.required' => 'Course id to be allocated was not provided',
            'course_ids.exists' => 'Course was not found',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }
        $course = Course::find($request->course_id);
        $staff_id = $request->staff_id;

        $allocation = CourseAllocation::where('course_id', $course->id)->where('staff_id', $staff_id)->first();

        if (!$allocation) {

            $insert = CourseAllocation::create([
                'staff_id' => $staff_id,
                'course_id' => $course->id
            ]);
        }

        $course->fill([
            'cordinator' => $staff_id
        ])->save();


        $cordinator = Staff::where('id', $staff_id)->with('user')->first();
        

        return response()->json([
            'success' => $course->code . ' has been assigned to ' . $cordinator->user->name,
            'data' => $cordinator
        ]);
    }
}
