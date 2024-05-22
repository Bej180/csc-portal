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
use App\Models\CourseAllocation;
use App\Models\Department;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Support\Arr;
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
        ], [
            'fullname.required' => 'Staff\'s name must be provided',
            'email.required' => 'Email address must be provided',
            'email.email' => 'Email is not valid',
            'email.unique' => 'Email has already been used or a staff\'s has already been created',
            'phone.required' => 'Phone must be provided',
            'gender.required' => 'Gender must be provided',
            'staff_id.required' => 'Staff ID must be provided',
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
            $formFields['password'] = $request->input('phone');
        }
        if ($image = UploaderController::uploadFile('image')) {
            $formFields['image'] = $image;
        }

        // this identifies that the account belongs to a staff
        $formFields['role'] = 'staff';

        // user the staffs phone number as inital password
        $formFields['password'] = bcrypt($formFields['phone']);


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
                        'staff_id' => $staff_id,
                        'designation' => $request->get('designation', 'lecturer'),
                    ];
                }
                CourseAllocation::insert($courses);
            }

            // email the staff about the new account creation
            Email(new NewStaffAccount($authAccount), $authAccount);
            $userController = new UserController();

            return response()->json([
                'success' => 'Staff account has been created',
                'data' => $userController->apiGetUsers('staff', $request)
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

    # API

    public function reset_user_password(Request $request) {
        $validator = Validator::make($request->all(), [
            'new_password' => 'required',
            'id' => 'required|exists:users'
        ], [
            'new_password.required' => 'New password must be provided',
            'id.required' => 'User must be provided',
            'id.exists' => 'Account not found',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $user = User::find($request->id);
        $user->fill([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => "Successfully reset {$user->name}'s passsword",
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
