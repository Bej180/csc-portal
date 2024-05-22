<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Advisor;
use App\Models\Student;
use App\Models\User;
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
        if (!$gender) {
            $gender = $user->$role->gender;
        }
        $image = public_path(match (true) {
            !is_null($user->$role->image) => 'storage/' . $user->$role->image,
            $gender == 'female' => 'images/avatar-f.png',
            $gender  == 'male' => 'images/avatar-m.png',
            default => 'images/avatar-u.png',
        });


        if (!file_exists($image)) {
            abort(404);
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
            view('pages.auth.login');
        }
        $user = auth()->user();
        
        return view("pages." . auth()->user()->role . ".dashboard", compact('user'));
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
            $profile->gender === 'male' => 'images/avatar-m.png',
            $profile->gender == 'female' => 'images/avatar-f.png',
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
// return $request->all();
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
        $student = $student_query->paginate(10);
        return $student;



    } 
}
