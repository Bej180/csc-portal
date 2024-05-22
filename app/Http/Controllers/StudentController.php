<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\User;
use App\Models\Result;
use App\Models\Student;
use App\Models\Department;
use App\Models\AcademicSet;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{



    public function profile_settings()
    {
        return view('pages.student.profile');
    }

    public function course_registration()
    {
        $sessions = AcademicSession::get('name')->unique('name');
        return view('pages.student.course-registration', compact('sessions'));
    }

    /**
     * Save courses selected by students
     */

    public function course_registration_history_page()
    {
        $user = auth()->user();
        $student = $user->student;
        $enrollments = $student->courseRegistrationPerSemester;

        $hasEnrolled = count($enrollments) > 0;
        $title = 'Enrolled Courses';
        if (!$hasEnrolled) {
            $title = 'Not Enrolled to any course';
        }

        $sessions = \App\Models\AcademicSession::get();

        return view('pages.student.courses.course_registration_history', compact('title', 'hasEnrolled', 'enrollments', 'sessions'));
    }



    public function settingPage()
    {
        return view('pages.student.settings');
    }



    /**
     * Save student to the database
     */

    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'surname' => 'required',
            'reg_no' => 'required|unique:students',
            'othernames' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'password' => 'sometimes|confirmed',
            'set_id' => 'required',
            'address' => 'sometimes',
            'birthdate' => 'sometimes',
            'gender' => 'sometimes|in:male,female',
            'session' => 'sometimes'
        ], [
            'reg_no.required' => "Student's Registration Number must be provided",
            'reg_no.unique' => "Student with the same Registration Number already exists",
            'gender.in' => 'Invalid gender, you are either a male or female',
            'surname.required' => "Student's fullname is required",
            'othernames.required' => "Student's fullname is required",
            'email.required' => 'Email is required',
            'email.unique' => 'Email already exists',
            'email.email' => 'Invalid email address',
            'phone.required' => 'Phone number is required',
            'password.confirmed' => 'Passwords do not match',
            'set_id.required' => 'Academic set is required'
        ]);




        $role = 'student';
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 401);
        }
        $formFields = $validator->validated();


        $surname = $request->get('surname', '');
        $othernames = $request->get('othernames', '');


        // instantiate User object
        $user = new User();

        // concatenate the firstname, lastname and middlename to for fullname
        $formFields['name'] = Arr::join([
            $surname,
            $othernames
        ], ' ');

        // Assigned the id of the account that created the user
        $formFields['created_by'] = auth()->id();

        $formFields['phone'] = preg_replace('/\D+/', '', $formFields['phone']);

        if (!$formFields['phone']) {
            return response()->json([
                'error' => 'Phone number must be numeric characters only.'
            ], 400);
        }


        // Make phone number the password if no password is provided
        if (!$request->has('password')) {
            $formFields['password'] = $formFields['phone'];
        }
        $formFields['role'] = $role;

        $formFields['password'] = bcrypt($formFields['password']);

        // Add the new account to User model for authe
        $user = User::createUser($formFields);

        // Assign user id to student account
        $formFields['id'] = $user->id;


        if ($uploadedFile = UploaderController::uploadFile('image', 'pic')) {
            $formFields['image'] = $uploadedFile;
        }

        Student::create($formFields);
        $students = Student::with('user')->latest()->get();

        return response()->json([
            'success' => 'Successfully created account.',
            'data' => $students
        ]);
    }


    public function search_students(Request $request)
    {
        $query = $request->get('query');

        $students = User::where('users.role', '=', 'student')

            ->join('students', 'students.id', '=', 'users.id')
            ->where('users.name', 'LIKE', "%$query%")
            ->orWhere('students.reg_no', 'LIKE', "%$query%")
            ->get();
        return $students;
    }




    public function profile(string $username)
    {
        return response($username);
    }



    public function update(Request $request)
    {

        $formFields = $request->validate([
            'address' => 'sometimes',
            'birthdate' => 'sometimes',
            'gender' => 'sometimes|in:male,female',
            'phone' => 'sometimes',
            'level' => 'sometimes',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2046'
        ]);

        $student = new Student();
        $user = new User();

        $userFillables = $user->getFillables();
        $studentFillables = $student->getFillables();

        $userProfile = User::find(auth()->id());
        $studentProfile = Student::find(auth()->id());

        $studentData = $request->only($studentFillables);

        if ($request->hasFile('image')) {
            $studentData['image'] = $request->file('image')->store('pic', 'public');
        }


        $userProfile->update($request->only($userFillables));
        $studentProfile->update($studentData);

        return back()->with('success', 'success:Profile Updated');
    }


    public function doRegister(Request $request)
    {

        $formFields = $request->validate([
            'name' => 'required',
            'reg' => 'required',
            'phone' => 'required',
            'email' => ['required', 'email', Rule::unique('users')],
            'password' => 'required',
            'gender' => 'sometimes',
            'birthdate' => 'sometimes',
            'address' => 'sometimes'
        ]);

        $name = $request->input('name');
        $reg = $request->input('reg');
        $phone = $request->input('phone');
        $email = $request->input('email');
        $password = $request->input('password');
        $gender = $request->input('gender');

        $role = 'student';
        $password = bcrypt($password);


        // Add to user table
        $user = User::createUser(compact('name', 'email', 'password', 'role'));


        // Add to student table
        $student = new Student();

        // Assign user id to student
        $student->id = $user->id;

        $optionalData = ['gender', 'birthdate', 'address', 'set_id'];

        foreach ($optionalData as $field) {
            if ($request->has($field)) {
                $student->{$field} = $request->input($field);
            }
        }
        if ($request->hasFile('image')) {
            $student->image = $request->file('image')->save('pic', 'public');
        }



        if ($request->has('jtoken')) {
            $set = AcademicSet::where('token', $request->input('jtoken'));
            if ($set) {
                $student->set_id = $set->first()->id;
            }
        }

        $save = $student->save();

        // Automatically log student in

        Auth()->login($user);
        return User::redirectToDashboard()->with('success', 'Account has been created');
    }

    private function data()
    {
        $user = auth()?->user();
        $student = $user?->student;
        $set = $student?->set;
        $staff = $set?->staff->user;
        $courses = $student?->courses;

        return compact('user', 'set', 'student', 'courses', 'staff');
    }

    public function register(Request $request)
    {

        $title = 'Registeration Form';

        $invitation = AcademicSet::getSetFromURL();
        $jointoken = null;

        if ($invitation) {
            $title =  "Joining {$invitation->name}";
            $jointoken = $request->input('jointkn');
        }


        return view('auth.student-registration', compact('invitation', 'title', 'jointoken'));
    }



    public function getStudent(Request $request)
    {
        $student_id = $request->student_id;

        if (!$student_id) {
            return response()->json(['error' => 'Student Id is required'])->status(403);
        }

        $student = Student::where('id', '=', $student_id)->with(['user', 'class'])->get();

        if (!$student) {
            return response()->json(['error' => 'Student not found'])->status(404);
        }
        $student = $student->first();
        $student->cgpa = $student->calculateCGPA();
        $student->image = $student->picture();

        return $student;
    }























    public function api_getStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id'
        ], [
            'student_id.required' => 'Student ID is required',
            'student_id.exists' => 'Student account not found',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        return Student::where('id', $request->student_id)->with('user')->first();
    }



    public function show_results(Request $request)
    {


        $user = $request->user();
        $student = $user->student;
        $sessions = Enrollment::where('reg_no', $student->reg_no)->groupBy('session')->get();
        $enrollments = $student->enrollments()->groupBy('session');


        $session = request()->session;
        $semester = request()->semester;
        $unapproved = $approved = null;
        $GPA = 0.0;


        if ($session && $semester) {
            dd($semester, $session);


            $results = function ($status) use ($student,  $semester, $session) {
                $equality = $status === 'approved' ? '=' : '!=';

                $results = Enrollment::join('courses', 'courses.id', '=', 'enrollments.course_id')
                    ->where('enrollments.semester', $semester)
                    ->where('enrollments.reg_no', $student->reg_no)
                    ->leftJoin('results', function ($join) use ($student) {
                        $join->on('results.reg_no',    '=', 'enrollments.reg_no')
                            ->on('results.course_id', '=', 'courses.id')
                            ->on('results.semester',  '=', 'enrollments.semester')
                            ->on('results.session',   '=', 'enrollments.session')
                            ->on('results.level',     '=', 'enrollments.level');
                    })
                    ->where('results.status', $equality, 'PENDING')
                    ->where('enrollments.session', $session)
                    ->get();

                return $results;
            };


            $approved = $results('approved');
            $unapproved = $results('unapproved');


            $GPA = Result::calculateGPA($approved, $semester, $session);
        }


        return view('pages.student.results.show-results', compact('sessions', 'semester', 'GPA', 'student', 'session', 'approved', 'unapproved'));
    }



    public function index_results(Request $request)
    {

        $user = $request->user();
        $student = $user->student;

        $enrollments = Enrollment::query();
        $enrollments->where('enrollments.reg_no', '=', $student->reg_no);

        if ($semester = $request->semester) {
            $enrollments->where('enrollments.semester', '=', $semester);
        }

        if ($session = $request->session) {
            $enrollments->where('enrollments.session', '=', $session);
        }

        $totalEnrollments = Enrollment::where('reg_no', $student->reg_no)->get();
        $awaitingResults = $totalEnrollments->filter(function ($enrollment) {
            $result = Result::where('course_id', $enrollment->course_id)
                ->where('reg_no', $enrollment->reg_no)
                ->first();
            if (!$result || ($result && $result->status !== 'APPROVED')) {
                return true;
            }

            return false;
        });
        $awaitingResults = $awaitingResults->toArray();
        $unsettledResults = Result::where('reg_no', $student->reg_no)
            ->where('score', '<', 40)
            ->get()
            ->filter(function ($result) {
                return !Result::where('score', '>', 39)
                    ->where('course_id', $result->course_id)
                    ->where('reg_no', $result->reg_no)->exists();
            });


        $results = $enrollments
            // ->with('course')
            ->leftJoin('results', function ($join) {
                $join->on('results.reg_no', '=', 'enrollments.reg_no')
                    ->on('results.course_id', '=', 'enrollments.course_id')
                    ->on('results.session', '=', 'enrollments.session')
                    ->on('results.semester', '=', 'enrollments.semester')
                    ->where('results.status', '=', 'APPROVED');
            })
            ->get()
            ->map(function ($result) {
                $result->settled = true;

                if ($result->score < 40 || !$result->score) {

                    $result->settled = Result::where('reg_no', '=', $result->reg_no)
                        ->where('status', '=', 'APPROVED')
                        ->where('course_id', '=', $result->course_id)
                        ->where('score', '>', 39)->exists();
                }

                return $result;
            })


            ->groupBy(['session', 'semester'])

            ->map(function ($sessions) {
                return $sessions->map(function ($semesterResults) {
                    $totalUnits = $semesterResults->sum('course.units');
                    $totalGradePoints = $semesterResults->sum('grade_points');

                    return [
                        'results' => $semesterResults,
                        'totalUnits' => $totalUnits,
                        'totalGradePoints' => $totalGradePoints,
                    ];
                })->put('sessionTotals', [
                    'totalUnits' => $sessions->flatten(1)->sum('course.units'),
                    'totalGradePoints' => $sessions->flatten(1)->sum('grade_points')
                ]);
            });

        return compact('awaitingResults', 'results', 'unsettledResults', 'totalEnrollments');
        return $results;


        $sessions = Enrollment::where('reg_no', $student->reg_no)->groupBy('session')->get();
        $enrollments = $student->enrollments()->groupBy('session');


        $session = request()->session;
        $semester = request()->semester;
        $unapproved = $approved = null;
        $GPA = 0.0;


        if ($session && $semester) {
            dd($semester, $session);


            $results = function ($status) use ($student,  $semester, $session) {
                $equality = $status === 'approved' ? '=' : '!=';

                $results = Enrollment::join('courses', 'courses.id', '=', 'enrollments.course_id')
                    ->where('enrollments.semester', $semester)
                    ->where('enrollments.reg_no', $student->reg_no)
                    ->leftJoin('results', function ($join) use ($student) {
                        $join->on('results.reg_no',    '=', 'enrollments.reg_no')
                            ->on('results.course_id', '=', 'courses.id')
                            ->on('results.semester',  '=', 'enrollments.semester')
                            ->on('results.session',   '=', 'enrollments.session')
                            ->on('results.level',     '=', 'enrollments.level');
                    })
                    ->where('results.status', $equality, 'PENDING')
                    ->where('enrollments.session', $session)
                    ->get();

                return $results;
            };


            $approved = $results('approved');
            $unapproved = $results('unapproved');


            $GPA = Result::calculateGPA($approved, $semester, $session);
        }


        return view('pages.student.results.show-results', compact('sessions', 'semester', 'GPA', 'student', 'session', 'approved', 'unapproved'));
    }








    public function course_registration_details(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'semester' => 'required',
            'level' => 'required',
            'session' => 'required',
        ], [
            'session.required' => 'Session was not provided',
            'level.required' => 'Level was not specified',
            'session.required' => 'Session is missing',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $semester = $request->semester;
        $level = $request->level;
        $session = $request->session;
        $user = $request->user();
        $student = $user->student;


        $enrollments = Course::getEnrollments($semester, $session);

        if (!count($enrollments)) {
            return response()->json([
                'error' => 'Enrollment history doesnt exist'
            ], 400);
        }

        $level = $enrollments->first()->level;

        return compact('semester', 'level', 'student', 'user', 'session', 'enrollments');
    }

    public function api_enrollments(Request $request)
    {
        $user = $request->user();
        $student = $user->student;
        $enrollments = $student->courseRegistrationPerSemester;



        return compact('enrollments');
    }
}
