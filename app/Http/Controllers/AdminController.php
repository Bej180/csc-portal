<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Advisor;
use App\Models\AcademicSet;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\LoginController;
use App\Mail\NewAdvisorAccount;
use App\Mail\NewStaffAccount;
use App\Models\AcademicSession;
use App\Models\ActivityLog;
use App\Models\Admin;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\CourseAllocation;
use App\Models\Result;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function dashboard()
    {


        return view('admin.dashboard');
    }



    public function settings(Request $request)
    {
        return view('pages.admin.configuration.settings');
    }


    public function control_panel(Request $request)
    {
        $sessions = AcademicSession::latest()->get();
        $active_session = $sessions->first();
        $open_semesters = AcademicSession::semestersOpenForCourseRegistration();
        $advisors = Staff::where('is_class_advisor', true)->count();
        $students = Student::count();
        $courses = Course::count();
        $staffs = Staff::count();

        return view('pages.admin.configuration.control_panel', compact('students', 'courses', 'staffs', 'active_session', 'open_semesters', 'sessions'));
    }






    /**
     * Admin updates students information
     */

    public function update_staff(Request $request, bool $is_api = false)
    {

        $formFields = $request->validate([
            'staff_id' => 'required',
            'firstname' => 'sometimes',
            'lastname' => 'sometimes',
            'middlename' => 'sometimes',
            'email' => 'sometimes|email',
            'phone' => 'sometimes',
            'birthdate' => 'sometimes',
            'entryMode' => 'sometimes',
            'set_id' => 'sometimes',
            'gender' => 'sometimes',
            'image' => 'sometimes',

            'staff_id' => 'sometimes'
        ]);


        if ($name = User::getFullnameFromRequest()) {
            $formFields['name'] = $name;
        }

        $currentAccount = Advisor::where('id', $request->staff_id)->with('user')->get()->first();

        if (!$currentAccount) {
            return redirect()->back()->with('error', 'Advisor Account does not exist');
        }

        // If email is among the fields to be updated but it's the same as the current email
        // Unset the field
        if (array_key_exists('email', $formFields) && $formFields['email'] == $currentAccount->user->email) {
            unset($formFields['email']);
        }
        if ($image = UploaderController::uploadFile('image')) {
            $formFields['image'] = $image;
        }


        $currentAccount->user->update($formFields);
        $currentAccount->update($formFields);

        return redirect()->back()->with('success', 'Student account updated successfully');
    }


    /**
     * Admin updates students information
     */

    public function update_student(Request $request, bool $is_api = false)
    {

        $formFields = $request->validate([
            'firstname' => 'sometimes',
            'lastname' => 'sometimes',
            'middlename' => 'sometimes',
            'email' => 'sometimes|email',
            'phone' => 'sometimes',
            'birthdate' => 'sometimes',
            'entryMode' => 'sometimes',
            'set_id' => 'sometimes',
            'session' => 'sometimes',
            'level' => 'sometimes',
            'gender' => 'sometimes',
            'image' => 'sometimes',
            'reg_no' => 'required'
        ]);

        if ($name = User::getFullnameFromRequest()) {
            $formFields['name'] = $name;
        }

        $currentAccount = Student::where('reg_no', $request->reg_no)->with('user')->get()->first();

        if (!$currentAccount) {
            return redirect()->back()->with('error', 'Student Account does not exist');
        }


        // If email is among the fields to be updated but it's the same as the current email
        // Unset the field
        if (array_key_exists('email', $formFields) && $formFields['email'] == $currentAccount->user->email) {
            unset($formFields['email']);
        }
        if ($image = UploaderController::uploadFile('image')) {
            $formFields['image'] = $image;
        }


        $currentAccount->user->update($formFields);
        $currentAccount->update($formFields);

        return redirect()->back()->with('success', 'Student account updated successfully');
    }

    /**
     * Saves Admin Account information into the database
     */
    public function store(Request $request)
    {
        $role = 'admin';


        $firstname = $request->get('firstname', '');
        $lastname = $request->get('lastname', '');
        $middlename = $request->get('middlename', '');


        // Validate user inputs against list of rules
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'phone' => 'required',
            'password' => 'sometimes|confirmed',
            'set_id' => 'required',
            'session' => 'sometimes'
        ]);


        // instantiate User object
        $user = new User();


        // concatenate the firstname, lastname and middlename to for fullname
        $formFields['name'] = Arr::join([$firstname, $lastname, $middlename], ' ');

        // Assigned the id of the account that created the user
        if (auth()->check() && auth()->user()->role !== 'student') {
            $formFields['created_by'] = auth()->id();
        }

        // Make phone number the password if no password is provided
        if (!$request->has('password')) {
            $formFields['password'] = $request->input('phone');
        }
        $formFields['role'] = $role;

        $formFields['password'] = bcrypt($formFields['password']);

        // Add the new account to User model for authe
        $user = User::createUser($formFields);


        // Upload image if image is selected
        if ($uploadedFile = UploaderController::uploadFile('image', 'pic')) {
            $formFields['image'] = $uploadedFile;
        }


        $formFields['id'] = $user->id;
        Admin::create($formFields);

        return redirect()->back()->with('message', strtoupper($role) . ' account added');
    }




    /**
     * Save staff's information into the database
     */
    public function store_staff(Request $request)
    {


        // Validate user inputs against list of rules
        $validator = Validator::make($request->all(),  [
            'email' => 'required|email|unique:users',
            'fullname' => 'required',
            'phone' => 'required',
            'gender' => 'required',
            'address' => 'sometimes',
            'title' => 'sometimes',
            'birthdate' => 'sometimes',
            'staff_id' => 'required',
            'designation' => 'required',
            'passkey' => 'password'
        ], [
            'fullname.required' => 'Staff\'s name must be provided',
            'email.required' => 'Email address must be provided',
            'email.email' => 'Email is not valid',
            'email.unique' => 'Email has already been used or a staff\'s has already been created',
            'phone.required' => 'Phone must be provided',
            'gender.required' => 'Gender must be provided',
            'staff_id.required' => 'Staff ID must be provided',
            'designation.required' => 'Staff Designation is required',
        ]);

        

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 401);
        }
        $formFields = $validator->validated();





        // concatenate the firstname, lastname and middlename to for fullname
        $name = Arr::join([
            $request->get('title', ''),
            $request->get('fullname')
        ], ' ');
        $formFields['name'] = $name;

        // Add the id of the admin that created the staff's account
        $formFields['created_by'] = $request->user()->id;

        if (!$request->user()->is_admin()) {
            return response()->json([
                'error' => 'You not permitted to create a staff account',
            ], 401);
        }


        // Make phone number the password if no password is provided
        if (!$request->has('password')) {
            $formFields['password'] = $request->input('staff_id');
        }
        if ($image = UploaderController::uploadFile('image')) {
            $formFields['image'] = $image;
        }

        // this identifies that the account belongs to a staff
        $formFields['role'] = 'staff';

        // user the staffs phone number as inital password
        $formFields['password'] = bcrypt($formFields['password']);


        // Create Auth Account
        $authAccount = User::createUser($formFields);
        $staff_id = $authAccount->id;

        // Assign id to profile
        $formFields['id'] = $staff_id;
        $staff = Staff::create($formFields);



        if ($staff) {
            // log the activity
            ActivityLog::log($request->user(), 'account_creation', 'created a staff account for ' . $name);

            if ($request->courses) {
                $courses = [];
                foreach ($request->courses as $course_id) {
                    $courses[] = [
                        'course_id' => $course_id,
                        'staff_id' => $staff_id
                    ];
                }
                CourseAllocation::insert($courses);
            }

            // email the staff about the new account creation
            Email(new NewStaffAccount($authAccount), $authAccount);
            
            return response()->json([
                'success' => 'Staff account has been created',
                'data' => $authAccount->account()
            ]);
        }


        return response()->json([
            'error' => 'Failed to create staff profile',
        ], 401);
    }



    /** STAFF MANAGEMENT METHODS START HERE */
    public function show_staffs()
    {
        return view('pages.admin.staff-management.staffs');
    }

    public function index_staff(Request $request)
    {
        $user = User::query();
        $user->join('staffs', 'staffs.id', 'users.id')
            ->where('role', 'staff');

        if ($request->type) {
            if ($request->type == 'advisor') {
                $user->where('is_class_advisor', true);
            } else if ($request->type == 'hod') {
                $user->where('is_hod', true);
            } else {
                $user->where('designation', $request->type);
            }
        }


        if ($search = $request->search) {
            $multiSearch = preg_replace('/\s+/', '%',  $search);
            $user->where(function ($query) use ($multiSearch) {
                $query->where('users.name', 'LIKE', "%$multiSearch%")
                    ->orWhere('users.email', 'LIKE', "%$multiSearch%")
                    ->orWhere('users.phone', 'LIKE', "%$multiSearch%")
                    ->orWhere('staffs.staff_id', 'LIKE', "%$multiSearch%");
            });
        }
        if ($request->sort && is_array($request->sort)) {
            $user->orderBy(...$request->sort);
        }

        $staff = $user->paginate(10);

        return $staff;
    }

    public function show_advisors()
    {
        return view('pages.admin.advisor-management.advisors');
    }





    /** STAFF MANAGEMENT METHODS END HERE */






    /*----------------------------------------------*/
    /**CLASS MANAGEMENT METHODS START HERE **/
    public function add()
    {
        return view('pages.admin.class-management.add-class');
    }
    public function show_classes()
    {

        $staffs = Staff::with('user')->get();
        return view('pages.admin.class-management.classes', compact('staffs'));
    }

    /**CLASS MANAGEMWNT METHODS END HERE */




    /*----------------------------------------------*/
    /**COURSE MANAGEMENT METHODS START HERE **/
    public function show_courses()
    {
        return view('pages.admin.course-management.courses');
    }

    public function view_course(Course $course)
    {

        return view('pages.admin.course-management.show-course', compact('course'));
    }


    /**COURSE MANAGEMWNT METHODS END HERE */







    /*----------------------------------------------*/
    /**CLASS ADVISOR MANAGEMENT METHODS START HERE **/


    public function get_users(Request $request)
    {
        // Define custom error messages for validation
        $customMessages = [
            'queries.required' => 'The queries parameter is required.',
            'queries.array' => 'The queries parameter must be an array.',
            'queries.*.id.numeric' => 'The id must be a number.',
            'queries.*.name.string' => 'The name must be a string.',
            'queries.*.phone.regex' => 'The phone must be a number.',
            'role.required' => 'The user role must be provided',
        ];

        // Validate the request parameters
        $validator = Validator::make($request->all(), [
            'queries' => 'nullable|array',
            'queries.*.id' => 'nullable|numeric',
            'queries.*.name' => 'nullable|string',
            'queries.*.phone' => 'nullable|regex:/^[0-9]+$/',
            'role' => 'required'
        ], $customMessages);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Retrieve form fields from the request
        $formFields = $request->input('queries');

        // Start building the query to retrieve staffs
        $userQuery = User::query();

        $userQuery->where('role', $request->role);


        // Check if any of the form fields are provided
        if (!empty(array_filter($formFields))) {
            foreach ($formFields as $field => $value) {
                if ($value !== null) {
                    $userQuery->where($field, $value);
                }
            }
        }

        // Execute the query to retrieve staffs
        $users = $userQuery->get();

        // Check if any staffs were found
        if ($users->isEmpty()) {
            return response()->json(['message' => 'No staffs found matching the specified criteria.'], 404);
        }

        // Return the retrieved staffs
        return response()->json($users);
    }



    public function add_staff()
    {
        return view('pages.admin.staff-management.add-staff');
    }

    public function edit_staff(Request $request)
    {
        $request->validate([
            'staff_id' => 'required'
        ]);
        $staff_id = $request->staff_id;
        $staff = Advisor::find($staff_id)?->get()?->first();
        if (!$staff) {
            return redirect()->back()->with('error', 'Advisor not found');
        }
        return view('pages.admin.staff-management.edit-staff', compact('staff'));
    }


    /**
    * Delete all enrollments for a particular semester
     */
    public function delete_enrollments(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'enrollment_id' => 'required|exists:enrollments,request_id',
            'reg_no' => 'required|exists:enrollments',

            'passkey' => 'password'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $enrollments = Enrollment::where('request_id', $request->enrollment_id)
            ->where('reg_no', $request->reg_no)
            ->get();

        $first = $enrollments->first();

        // foreach($enrollments as $enrollment) {
        //     $enrollment->delete();
        // }
        Enrollment::destroy($enrollments);

        $enrollments = Enrollment::where('request_id', $request->enrollment_id)
            ->where('reg_no', $request->reg_no)
            ->get();

        return response()->json([
            'data' => $enrollments,
            'success' => "You have successfully deleted {$first->student->user->name}'s enrollment for {$first->level} level of {$first->session} {$first->semester} semester"
        ]);
    }

    /**
     * Display all soft deleted courses, students and staffs
     */
    public function recycle_bin_view(Request $request) 
    {
       

        return view('pages.admin.recycle-bin');

    }

    public function recycleBinTakeAction(Request $request) {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:restore,delete',
            'type' => 'required|in:Course,User,Enrollment',
            'id' => 'required|integer',
        ], [
            'action.required' => 'Action is required',
            'action.in' => 'Unknown action',
            'type.required' => 'An error occured',
            'type.in' => 'Invalid type',
            'id.required' => 'ID not provided',
            'id.integer' => 'Invalid ID',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        if (!$request->user()->is_admin()) {
            return response()->json([
                'error' => 'You are not allowed to perform '.$request->action,
            ], 400);
        }
        $type = $request->type;

        $builder = match($type) {
            'Course' => Course::query(),
            'Enrollment' => Enrollment::query(),
            'User' => User::query(),
            default => null,
        };

        if ($builder) {
            $first = $builder->onlyTrashed()
                ->where('id', $request->id)->first();
            
            if (!$first) {
                return response()->json([
                    'error' => $type.' record not found. It may have already been deleted',
                ], 404);
            }
            
            $message = match($type) {
                'Course' => $first->code,
                'User' => "{$first->name}'s account",
                default => $type,
            };

            if ($request->action === 'restore') {
                
                $first->update([
                    'deleted_at' => null
                ]);
                $message .= ' has been restored';
            }
            else if ($request->action === 'delete') {
                if (!$request->confirmed) {
                    return response()->json([
                        'confirm' => 'Are you sure you want to permanently delete this ' . $type
                    ], 400);
                }
                $first->forceDelete();

                $message .= ' has been deleted permanently';
            }

            return response()->json([
                'success' => $message
            ]);
        }



    }

    public function recycleBin (Request $request) {

        if (!$request->user()->is_admin()) {
            return response()->json([
                'error' => 'You are not allowed to access this feature',
            ], 400);
        }

        $courses = Course::onlyTrashed()->get([
            'id', 'code as name', 'deleted_at', 'created_at'
        ]);

        $users = User::onlyTrashed()->get([
            'id', 'name', 'deleted_at', 'created_at'
        ]);
        
        $results = [];//Result::onlyTrashed()->get();

        $enrollments = [];//Enrollment::onlyTrashed()->get();


        return compact('users', 'courses', 'results', 'enrollments');

    }


    /**
    * Update all enrollments for a particular semester
     */
    public function update_enrollments(Request $request) {
        $startYear = date('Y');
        $endYear = $startYear + 1;
        
        $validator = Validator::make($request->all(), [
            'enrollment_id' => 'required|exists:enrollments,request_id',
            'session' => 'sometimes|session:5',
            'level'=>'sometimes|in:100,200,300,400,500',
            'semester' => 'sometimes|in:RAIN,HARMATTAN',

            'passkey' => 'password'
        ], [
            'enrollment_id.required' => 'Enrollment ID to be updated was not provided',
            'enrollment_id.exists' => 'Enrollment was not found',
            'session.session' => "Invalid session use (eg: $startYear/$endYear)",
            'level.in' => 'Invalid level must be from 100 to 500',
            'semester.in' => 'Invalid Semester must be either HARMATTAN or RAIN',
        ]);
        $data = [];
    
        if ($semester = $request->semester) {
            $data['semester'] = strtoupper($semester);
        }
        if ($level = $request->level) {
            $data['level'] = $level;
        }
        if ($session = $request->session) {
            $data['session'] = $session;
        }

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $enrollments = Enrollment::where('request_id', $request->enrollment_id)
            ->get();

        $first = $enrollments->first();

        foreach($enrollments as $enrollment) {
            $enrollment->update($data);
        }

        return response()->json([
            'success' => "You have successfully update {$first->student->user->name}'s enrollment record"
        ]);
    }

    /**
     * Delete a single enrollment 
     */

     public function drop_course_from_enrollment(Request $request) {

        $validator = Validator::make($request->all(), [
            'passkey' => 'password',
            'id' => 'required|exists:enrollments'
        ], [
            'id.required' => 'Enrollment ID was not provided',
            'id.exists' => 'Enrollment was not found. It may have been delete already'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }
        

        $enrollment = Enrollment::where('id', $request->id)->with('course')->first();
        $course_code = $enrollment->course->code;
        $enrollment->delete();

        return response()->json([
            'success' => "You have successfully droped $course_code",
        ]);
     }

     /**
      * Add course to enrollment
      */

      public function add_course_to_enrollment(Request $request) {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'enrollment_id' => 'required|exists:enrollments,request_id',
            'passkey' => 'password',
        ], [
            'course_id.required' => 'Course ID is required',
            'course_id.exists' => 'Course Not found',
            'enrollment_id.required' => 'Enrollment ID was missing',
            'enrollment_id.exists' => 'Enrollment not found',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }
        $enrollments = Enrollment::where('request_id', $request->enrollment_id)
                ->with('student.user')
                ->get();
        $course_ids = $enrollments->pluck('course_id')->toArray();
        
        if (in_array($request->course_id, $course_ids)) {
            return response()->json([
                'error' => 'Course has already been added to student enrollment',
            ], 400);
        }
        $enrollment = $enrollments->first();

        $newEnrollment = Enrollment::create([
            'reg_no' => $enrollment->reg_no,
            'course_id' => $request->course_id,
            'semester' => $enrollment->semester,
            'request_id' => $enrollment->request_id,
            'approved' => 1,
            'session' => $enrollment->session,
            'level' => $enrollment->level,
        ]);

        $course = Enrollment::where('enrollments.id', $newEnrollment->id)
            ->join('courses', 'courses.id', '=', 'enrollments.course_id')
            ->select([
                'enrollments.id',
                'enrollments.request_id',
                'enrollments.level',
                'enrollments.semester',
                'courses.code',
                'courses.option',
                'courses.name',
                'session',
                'units'
            ])
            ->first();

        return response()->json([
            'data' => $course,
            'success' => "You have successfully added {$course->code} to {$enrollment->student->user->name}'s {$enrollment->session} {$enrollment->semester} semester enrollment"
        ]);


      }
    
    # API

    public function resetLoginDetails(Request $request) {
       
        $validator = Validator::make($request->all(), [
            'email' => 'sometimes|email',
            'username' => 'sometimes',
            'reset_password' => 'sometimes',
            'passkey' => 'password',
            'id' => 'required|exists:users'
        ], [
            'email.email' => 'You provided an invalid email address',
            'id.required' => 'User must be provided',
            'id.exists' => 'Account not found',
        ]);



        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $validated = $validator->validated();

        $user = User::find($request->id);
        $data = [];

        foreach(['username', 'email'] as $field) {
            if (Arr::exists($validated, $field)) {
                if ($validated[$field] !== $user->$field) {
                    $data[$field] = $validated[$field];
                }
            }
        }
        $account = $user->account();
        if ($request->reset_password) {
            $data['password'] =  Hash::make(match($account->role) {
                'student' => $account->reg_no, 
                default => $account->staff_id,
            });
        }

        $count_data = count($data);

        if (!$count_data) {
            return response()->json([
                'error' => 'Nothing was updated because you changed nothing',
            ], 400);
        }
        else {
            $user->fill($data)->save();
        }

        $user_name = $user->name;
        $columns = array_keys($data);
        $message = "Successfully reset {$user_name}'s ".Arr::join($columns, ', ', ' and ');

        if ($count_data === 1) {
            $message = $user_name."'s ".ucfirst($columns[0]) . " reset successfully";

            if (Arr::exists($data, 'password')) {
                $usedPassword = 'StaffID';
                if ($user->role === 'student') {
                    $usedPassword = 'Registration Number';
                }
                $his = $user->pronoun('his');

                $message = "$user_name's password has been reset to $his $usedPassword successfully";
            }
        }

       


        return response()->json([
            'success' => $message,
        ]);
    }


    public function get_staff(Request $request)
    {
        $staff_id = $request->staff_id;


        if (!$staff_id) {
            return response()->json(['error' => 'Student Id is required'])->status(403);
        }

        $staff = Advisor::where('id', '=', $staff_id)->get();

        if (!$staff) {
            return response()->json(['error' => 'Advisor not found'])->status(404);
        }
        $staff = $staff->first();
        $class = $staff->class;
        $staff->studentsCount = $class->students()->count();
        $students = $class->students()->with('user')->paginate(3);

        $allStudents = [];

        foreach ($students as $student) {
            $currentStudent = $student;
            $currentStudent->picture = $student->picture();
            $allStudents[] = $currentStudent;
        }
        $staff->students = $allStudents;
        $staff->image = $staff->picture();
        $staff->user->fullname;

        return $staff;
    }


    /**CLASS ADVISOR MANAGEMWNT METHODS END HERE */



    /**MODERATOR MANAGEMENT METHODS STARTS HERE */
    public function show_moderators(Request $request)
    {
        $staffs = Staff::with('user')->get();

        return view('pages.admin.moderator-management.index', compact('staffs'));
    }
    /**MODERATOR MANAGEMENT METHODS ENDS HERE */








    /*----------------------------------------------*/
    /**STUDENT MANAGEMENT METHODS START HERE **/

    public function show_students()
    {
        return view('pages.admin.student-management.students');
    }



    /**STUDENT MANAGEMWNT METHODS END HERE */






    /*----------------------------------------------*/
    /**STUDENT MANAGEMENT METHODS START HERE **/

    public function show_results()
    {
        return view('pages.admin.result-management.results');
    }



    /**STUDENT MANAGEMWNT METHODS END HERE */
}
