<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Advisor;
use App\Models\Student;
use App\Models\User;
use App\Models\Staff;
use App\Models\CourseAllocation;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }






    public function doLogout(Request $request)
    {
        dd('Hello');
        dd([$request]);
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'You have been logged out');
    }


    public function update(Request $request)
    {


        $user = User::findOrFail(auth()->id());


        $validator = Validator::make($request->all(), [
            'configure' => 'required',
            'oldPassword' => [
                'sometimes',

                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('Your old password is not correct.');
                    }
                }
            ],
            'password' => [
                'sometimes', 'min:6', 'confirmed', 'different:oldPassword'
            ]
        ], [
            'password.confirmed' => 'Passwords do not match',
            'password.min' => 'Password must not be less than :min characters'
        ]);

        $configure = $request->configure;

        if ($validator->fails()) {
            session()->flash($configure, $validator->errors()->first()); // Use 'error' as key and first error message
            return redirect()->back();
        }


        if ($configure === 'password') {

            $user->fill([
                'password' => Hash::make($validator['password'])
            ])->save();

            session()->flash('passsword', 'Your password has been updated successfully.');
            return redirect()->back();
        }
    }



    /**
     * Display user profile picture
     * If user has't uploaded profile picture
     * displays picture based on user gender
     */

    public function display_picture(User $user)
    {
        $role = $user->role;
        $gender = $user->gender;
       
        $image = public_path(match (true) {
            isset($user->$role->image) => 'storage/' . $user->$role->image,
            isset($user->image) => 'storage/' . $user->image,
            $gender == 'FEMALE' => 'images/avatar-f.png',
            $gender  == 'MALE' => 'images/avatar-m.png',
            default => 'images/avatar-u.png',
        });


        if (!file_exists($image)) {
            $image = public_path('images/avatar-u.png');
        }

        $mime = mime_content_type($image);
        $filesize = filesize($image);

        header('Content-Type: ' . $mime);
        header('Content-Length: ' . $filesize);
        Cache::put($image, true, 60 * 50);



        readfile($image);
        exit;
    }




    /***Displays dashboard view to user based on role*/
    public function dashboard()
    {
        
        if (!auth()->check()) {
            return view('pages.auth.login');
        }
        $user = auth()->user();
        $role = $user->role;

        return view("pages.$role.dashboard", compact('user'));
        
        if ($user->activation_token) {
            return view("pages.$role.dashboard", compact('user'));
        } 
        else if ($role === 'admin') {
            session()->flash('alert', 'You are welcome. You logged in as Administrator and you need to set the configurations of this portal.');
            return redirect('/page/configurations');
        }
        else {
            session()->flash('info', 'You are welcome. You need to activate your account to continue using this portal.');
            return redirect("$role/activation");
        }
        
    }


    public function _updateProfile(Request $request)
    {

        $formFields = $request->validate([
            'firstname' => 'sometimes|regex:/^([a-zA-Z]+)$/',
            'lastname' => 'sometimes|regex:/^([a-zA-Z]+)$/',
            'middlename' => 'sometimes|regex:/^([a-zA-Z]+)$/',
            'email' => 'sometimes|email', // Rule::unique('users')],
            'phone' => 'sometimes',
            'password' => 'sometimes|confirmed',
            'oldPassword' => [
                'sometimes',
                function ($attribute, $value, $fail) {

                    if (!Hash::check($value, auth()->user()->password)) {
                        $fail('Old password didn\'t match');
                    }
                }
            ], // Rule::unique('users')
            'address' => 'sometimes',
            'level' => 'sometimes'
        ]);


        $updatable = Arr::except($formFields, ['firstname', 'lastname', 'middlename', 'oldPassword']);
        $name = null;
        if ($request->has('firstname')) {
            $name = $request->firstname;
        }
        if ($request->has('middlename')) {
            $name .= ' ' . $request->middlename;
        }
        if ($request->has('lastname')) {
            $name .= ' ' . $request->lastname;
        }
        if ($name) {
            $updatable['name'] = $name;
        }





        $authUser = auth()->user();

        $instance = $authUser->profile;

        $fillable = $instance->getFillable();

        if ($request->has('password')) {
            $updatable['password'] = bcrypt($formFields['password']);
        }

        if ($request->hasFile('profileImageSelect')) {
            $instance->image = $request->file('profileImageSelect')->store('pic', 'public');
        }
        foreach ($updatable as $column => $value) {
            if (in_array($column, $fillable)) {
                $instance->$column = $value;
            }
        }

        $authUser->update($updatable);


        return back()->with('success', 'Profile UPdated');
    }


    /**
     * Show setting page to users
     */
    public function show_settings()
    {
        return view('pages.student.settings');
    }



    public function activate_account(string $token)
    {
        return view('pages.auth.activate-account');
    }



    /**ADVISOR PAGE */
    public function show_staff(Advisor $staff)
    {
        return view('pages.admin.staff-management.show', compact('staff'));
    }







    public function apiGetUsers(string $role, Request $request)
    {


        // Remove 's' from the role parameter if it exists
        $role = rtrim($role, 's');




        // Validate the request parameters
        $validator = Validator::make($request->all(), [
            'limit' => 'nullable|numeric',
            'page' => 'nullable|numeric',
            'queries' => 'nullable|array',
            'queries.*.id' => 'nullable|numeric',
            'queries.*.name' => 'nullable|string',
            'queries.*.phone' => 'nullable|regex:/^[0-9]+$/',
        ], [
            'queries.array' => 'The queries parameter must be an array.',
            'queries.*.id.numeric' => 'The id must be a number.',
            'queries.*.name.string' => 'The name must be a string.',
            'queries.*.phone.regex' => 'The phone must be a number.',
            'limit.numeric' => 'Invalid limit',
            'page.numeric' => 'Invalid page',
        ]);

        $limit = (int) $request->get('limit', 10);
        $page = (int) $request->get('page', 1);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Start building the query to retrieve users
        $userQuery = User::query()->where('role', $role);

        // Apply additional filters if provided
        if ($request->filled('queries')) {
            foreach ($request->queries as $field => $value) {
                if ($field === 'reg_no') {
                    $userQuery->join('students', function ($join) use ($value) {
                        $join->on('students.id', '=', 'users.id')
                            ->where('students.reg_no', '=', $value);
                    });
                } else if ($field === 'name') {
                    $userQuery->where($field, 'LIKE', "%$value%");
                } else {
                    $userQuery->where($field, $value);
                }
            }
        }


        $pageName = 'get' . ucfirst($role);

        $userQuery->with($role);
        // Retrieve users matching the criteria
        $users = $userQuery->latest()->simplePaginate($limit, ['*'], $pageName, $page)
            ->map(function($user) {
                
                return $user;
            });
        //$users['pager'] = $pageName;



        // Check if any users were found
        if ($users->isEmpty()) {
            return response()->json(['message' => 'No users found matching the specified criteria.'], 404);
        }
      

        // foreach ($users as $n => $user) {
            
        //     if ($role === 'staff' && $user?->is_advisor()) {
        //         $users[$n]['class'] = $user->$role->advisor();
        //     }
        // }



        // Return the retrieved users
        return response()->json($users);
    }



    public function activities()
    {
        return view('pages.activities');
    }



    public function profile_settings()
    {
        if (auth()->user()->role === 'student') {
            return redirect('/student/profile');
        }
        return view('pages.general.settings');
    }



    public function updateProfile(Request $request)
    {
        
        
        $unUpdatable = [
            'student' => [
                'id',
                'reg_no',
                'name',
                'birthdate',
                'image'
            ],

            'staff' => [
                'id',
                'staff_id',
                'image'
            ],
            'admin' => [
                'id',
                'image'
            ]
        ];


        $validator = Validator::make($request->all(), [
            'data' => 'required|array',
            'type' => 'required',
            'data.username' => [
                'sometimes',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->type === 'username') {
                        $findUser = User::where('username', $value)->first();
                        if ($findUser) {
                            $fail('Username already exists');
                        }
                    }
                }
            ],
            'data.password' => [
                'sometimes',
                function ($attribute, $value, $fail) use ($request) {
                    if (Arr::exists($request->data, 'password')) {
                        if (!Arr::exists($request->data, 'old_password')) {
                            $fail('You old password is required');
                        } else if (!Arr::exists($request->data, 'password_confirmation')) {
                            $fail('Confirmation password is required');
                        } else if (!Hash::check($request->data['old_password'], $request->user()->password)) {
                            $fail('The old password you provided is not correct');
                        } else if ($request->data['password_confirmation'] !== $value) {
                            $fail('Passwords do not match');
                        }
                    }
                }
            ]
        ], [
            'data.required' => 'You provided no data for update',
            'data.array' => 'Data not provided',
            'type.required' => 'Can not update profile',
            'data.username.unique' => 'Username name was not updated because it already exists.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }


        $data = $request->data;
        $user = $request->user();


        $role = $user->role;
        $profile = $user->$role;

        if (Arr::exists($data, 'password')) {
            $data['password'] = Hash::make($data['password']);
        }

        if ($uploadImage = UploaderController::uploadFile('image', 'pic')) {
            $data['image'] = $uploadImage;
        }

        if (isset($unUpdatable[$user->role])) {
            $data = Arr::except($data, $unUpdatable[$user->role]);
        }

        if (!empty($data['title'])  && !empty($data['name']) && $role !== 'student') {
            $data['name'] = $data['title'] . ' ' .$data['name'];
        }



        



        
        $user->fill($data);
        $user->save();

        $profile->fill($data);
        $profile->save();

        ActivityLog::log($user, 'profile_update', 'update profile');

        return response()->json([
            'success' => 'You have successfully updated your profile',
            'data' => $profile
        ]);
    }


    public function viewProfile(Request $request)
    {
        $authUser = auth()->user();
        $role = $authUser->role;
        $profile = $authUser->$role;

        
        $profile->image = asset(match (true) {
            !is_null($profile->image) => 'storage/' . $profile->image,
            $profile->gender === 'MALE' => 'images/avatar-m.png',
            $profile->gender == 'FEMALE' => 'images/avatar-f.png',
            default => 'images/avatar-u.png',
        });

        
        $auth = $authUser->toArray();
        $pro = $profile->toArray();
        $profile =  array_merge($auth, $pro);
        
        if (!empty($profile['title'])  && $role !== 'student') {
            $proper_name = trim(preg_replace('/^'.$profile['title'].'/', '', $profile['name']));
            $profile['name'] = $proper_name;
        }

        return $profile;
    }


    public function changeProfilePic(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2046'
        ]);

        $user = auth()->user();

        if ($imagePath = UploaderController::uploadFile('image', 'pic')) {
            $user->saveProfileImagePath($imagePath);

            session()->flash('success', 'Your profile image has been uploaded');
            return back();
        }

        return redirect()->back()->withErrors(['error' => 'Failed upload profile image'])->onlyInput();
    }


    public function index_students(Request $request) {
    
        $student_query = Student::query();
        $student_query->join('users', 'users.id', '=', 'students.id');

        if ($request->search) {
            $cleanSearch = preg_replace('/\s+/', '%', $request->search);
            
            $student_query->where('users.name', 'LIKE', "%$cleanSearch%")
                        ->orWhere('users.email', 'LIKE', "%$cleanSearch%")
                        ->orWhere('users.phone', 'LIKE', "%$cleanSearch%");
        }

        if ($request->sort && is_array($request->sort)) {
            $student_query->orderBy(...$request->sort);
        }
        return $student_query->paginate(10)->map(fn($student)=>$student->user->account());
        
    } 







     /**
     * Fetch multiple staff members
     */
    public function get_staffs(Request $request)
    {
        $auth = $request->user();
    

        $newStaff = Staff::query()
                ->whereNot('staffs.id', $auth->id)
                ->with(['user', 'classes','courses.course']);

        if ($search = $request->search) {
            $search = preg_replace('/\s+/', '%', $search);

            $newStaff->where(function ($query) use ($search) {
                    $query->where('users.name', 'LIKE', "%$search%")
                        ->orWhere('designation', 'LIKE', "%$search%")
                        ->orWhere('staff_id', 'LIKE', "%$search%")
                        ->orWhere('users.phone', 'LIKE', "%$search%")
                        ->orWhere('users.email', 'LIKE', "%$search%");
                });
        }

        $newStaff = $newStaff
            
            ->orderBy('staffs.created_at', 'DESC')
            ->paginate(10);
        
        $newStaff = $newStaff->map(fn($staff) => array_merge($staff->toArray(), $staff->user->toArray()));

        
        return $newStaff;

        $staffs = Staff::query()->whereNot('staffs.id', $auth->id)
            //->join('users', 'users.id', '=', 'staffs.id')
            ->with(['user', 'courses.course']);





        $staffs = $staffs
            ->latest()
            ->paginate(10)
            ->map(function ($staff) {
                $staff->classes = $staff->classes;
                return $staff;
            })
            ->filter(function ($staff) use ($request) {
                $search = $request->search;

                if ($search) {
                    $search = preg_replace('/\s+/', '%', $request->search);

                    return Staff::where('staffs.id', $staff->id)
                        ->join('users', 'users.id', '=', 'staffs.id')
                        ->where(function ($query) use ($search) {
                            $query->where('users.name', 'LIKE', "%$search%")
                                ->orWhere('designation', 'LIKE', "%$search%")
                                ->orWhere('staff_id', 'LIKE', "%$search%")
                                ->orWhere('users.phone', 'LIKE', "%$search%")
                                ->orWhere('users.email', 'LIKE', "%$search%");
                        })->exists();
                }
                return true;
            });

        return $staffs;
    }

    /**
     * Fetch single staff member information
     */

    public function get_staff(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:staffs'
        ], [
            'id.required' => 'Staff ID must be provided',
            'id.numeric' => 'Invalid staff ID',
            'id.exists' => 'Staff account unavailable',
        ]);

        if ($validator->fails()) {

            return error_helper($validator->errors());
        }

        $auth = $request->user();


        $staffs = Staff::query()->where('id', $request->id)
            //->join('users', 'users.id', '=', 'staffs.id')
            ->with(['user', 'courses.course']);





        $staffs = $staffs
            ->latest()
            ->paginate(10)
            ->map(function ($staff) {
                $staff->classes = $staff->classes;
                return $staff;
            })
            ->filter(function ($staff) use ($request) {
                $search = $request->search;

                if ($search) {
                    $search = preg_replace('/\s+/', '%', $request->search);

                    return Staff::where('staffs.id', $staff->id)
                        ->join('users', 'users.id', '=', 'staffs.id')
                        ->where(function ($query) use ($search) {
                            $query->where('users.name', 'LIKE', "%$search%")
                                ->orWhere('designation', 'LIKE', "%$search%")
                                ->orWhere('staff_id', 'LIKE', "%$search%")
                                ->orWhere('users.phone', 'LIKE', "%$search%")
                                ->orWhere('users.email', 'LIKE', "%$search%");
                        })->exists();
                }
                return true;
            });

        return $staffs;
    }





    /**
     * Allocates courses
     * 
     * Add courses to the list of courses offered by the staff
     */

     public function allocate_courses(Request $request)
     {
 
 
         $validator = Validator::make($request->all(), [
             'id' => 'required|exists:staffs',
             'courses' => 'required',
             'password_required' => 'password'
         ], [
             'id.numeric' => 'Invalid Staff Id',
             'id.required' => 'Staff ID must be provided',
             'id.exists' => 'Staff Account is unavailable at the moment',
             'courses.required' => 'Courses to deallocate must be provided',
             'courses.array' => 'Course to be deallocated is missing',
             'password_required.password' => 'The password you provided did not match the password stored in the database'
         ]);

         if (!$request->password_required) {
            return response()->json([
                'password_required' => true,
            ], 400);
        }

         if (!$request->user()->hasPermissionTo('allocate_course')) {
            return response()->json([
                'error' => "You do not have the permission to allocate course",
            ], 400);
         }
 
 
         if ($validator->fails()) {
 
             return response()->json([
                 'errors' => $validator->errors(),
             ], 400);
         }
 
 
 
         $courseRecord = Course::query();
         $courseRecord->whereIn('id', $request->courses);
         $staff = Staff::find($request->id)->first();
 
 
         if ($staff->designation === 'technologists') {
             $courseRecord->where('has_practical', true);
         }
 
         $courses = $courseRecord->get();
 
         $newCourses = [];
 
 
 
         foreach ($courses as $course) {
 
             $new = CourseAllocation::updateOrCreate([
                 'staff_id' => $request->id,
                 'course_id' => $course->id
             ]);
             if ($new) {
                 $newCourses[] = $course->id;
             }
         }
 
         $staff->courses = CourseAllocation::whereIn('course_id', $newCourses)->with('course')->get();
 
         return response()->json([
             'success' => 'Course allocation was successfully',
             'staff' => $staff
         ]);
     }
     /**
      * Deallocates courses
      * 
      * Removes courses from the list of courses offered by the staff
      */
 
     public function deallocate_courses(Request $request)
     {
 
         $validator = Validator::make($request->all(), [
             'id' => 'required|exists:staffs',
             'courses' => 'required|array',
             'password_required' => 'password'
         ], [
             'id.required' => 'Staff ID must be provided',
             'id.exists' => 'Staff Account is unavailable at the moment',
             'courses.required' => 'Courses to deallocate must be provided',
             'courses.array' => 'Course to be deallocated is missing',
             'password_required.password' => 'The password you provided did not match the password stored in the database'
         ]);

         if (!$request->password_required) {
            return response()->json([
                'password_required' => true,
            ], 400);
        }

         if (!$request->user()->hasPermissionTo('allocate_course')) {
            return response()->json([
                'error' => "You do not have the permission to deallocate course",
            ], 400);
         }
 
         if ($validator->fails()) {
             return response()->json([
                 'errors' => $validator->errors(),
             ], 400);
         }
 
 
         $courses = CourseAllocation::whereIn('course_id', $request->courses)
             ->where('staff_id', $request->id);
 
 
 
         $deallocating = $courses->get();
 
         $courses->delete();
 
         // Deallocate from courses the staff is cordinating
         $cordinatingCourses = Course::whereIn('id', $request->courses)
             ->where('cordinator', $request->id);
 
         if ($cordinatingCourses->exists()) {
             $cordinatingCourses->fill([
                 'cordinator' => null
             ])->save();
         }
 
         return response()->json([
             'success' => 'Course deallocation was successfully',
             'deallocated' => $deallocating
         ]);
     }
 
 
     public function allocatable_courses(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'staff_id' => 'required|exists:staff,id',
             'semester' => 'required',
             'level' => 'required',
         ], [
             'staff_id.required' => 'Staff ID must be provided',
             'staff_id.exists' => 'Staff is not available',
             'semester.required' => 'Semester is required',
             'level.required' => 'Level must be provided',
         ]);

         if (!$request->user()->hasPermissionTo('allocate_course')) {
            return response()->json([
                'error' => "You do not have the permission to allocate course",
            ], 400);
         }
 
         $staff = Staff::find($request->staff_id);
 
         $designation = $staff->designation;
 
         $blacklist_courses = ['SIW 200', 'SIW 400', 'CSC 555', 'CSC 556'];
 
         $courses = Course::query();
         $courses = $courses->where('semester', $request->semester)
             ->whereNotIn('code', $blacklist_courses)
             ->where('level', $request->level);
 
         if ($designation == 'technologist') {
             $courses->where('has_practical', true);
         }
         $previousAllocations = CourseAllocation::where('staff_id', $request->staff_id)->get()->pluck('course_id');
         $courses->whereNotIn('id', $previousAllocations);
 
         $courses = $courses->get();
         return ['allocatables' => $courses];
     }

}
