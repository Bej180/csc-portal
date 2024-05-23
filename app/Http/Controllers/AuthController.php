<?php

namespace App\Http\Controllers;

use App\Mail\AccountActivationLinkNotification;
use App\Mail\LoginMail;
use App\Mail\newDevice;
use App\Mail\NewStudentAccount;
use App\Mail\OtpMail;
use App\Models\AcademicSet;
use App\Models\ActivityLog;
use App\Models\Admin;
use App\Models\Advisor;
use App\Models\Device;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Emargareten\TwoFactor\Actions\TwoFactorRedirector;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use PragmaRX\Google2FA\Google2FA;

class AuthController extends Controller
{

    private function generateOTP()
    {
        return 111111;
    }


    public function authenticate(Request $request)
    {
        // Validate the request data (token)
        $request->validate([
            'token' => 'required|string',
        ]);

        
        $token = $request->token;
        

        // Attempt to authenticate the user using Sanctum's token
        if (Auth::onceUsingId($token)) {
            // Authentication successful
            return response()->json(['message' => 'Authentication successful'], 200);
        } else {
            // Authentication failed
            return response()->json(['message' => 'Invalid token'], 401);
        }
    }
    public function verifyOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tokens' => 'required|array',
            'email' => 'required|exists:users'
        ], [
            'tokens.required' => 'OTP tokens must be provided',
            'email.required' => 'email address is missing',
            'email.exists' => 'User account not found',
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 402);
        }
        $otp = Arr::join($request->tokens, '');

        if ($otp != 111111) {
            return response()->json([
                'error' => 'OTP token mismatch',
            ], 400);
        }


        $user = User::where('email', '=', $request->email)->first();

        if (Auth::loginUsingId($user->id)) {
            $token = $user->createToken($user->role)->plainTextToken;

            // Determine token holder based on remember flag
            $tokenHolder = $request->remember_me ? 'persistent_session' : 'temporary_session';

            $response = [
                $tokenHolder => $token,
                'success' => '2Factor Token verified',
                'redirect' => '/home',
            ];

            if ($callbackUrl = $request->callbackUrl) {
                $response['redirect'] = $callbackUrl;
            }
            return response()
                ->json($response);
        }

        return response()->json([
            'error' => 'Validation Failed',
        ], 401);
    }



    public function success_landing_page()
    {
        return view('pages.auth.registered');
    }





    public function apiLogin(Request $request)
    {


        return $this->attemptLogin($request, function ($user) {
            $token = $user->createToken('myApp')->plainTextToken;
            return compact('token');
        });
    }










    public function login(Request $request)
    {
        if (AuthController::locked_user() && request()->get('change') !== 'user') {
            $redirect = '/lockscreen';
            if ($request->callbackUrl) {
                $redirect .= '?callbackUrl=' . urlencode($request->callbackUrl);
            }

            return redirect($redirect);
        }
        if (session('otp_user_id')) {
            return redirect('/otp');
        }
        return view('pages.auth.login');
    }

    public function register(Request $request)
    {
        $invitation = AcademicSet::getSetFromURL();
        if ($request->has('invite') && !$invitation) {
            abort(403, 'Registeration link has expired or revoked');
        } else if (!$request->has('invite')) {
            abort(403, 'You need invitation link to access this page.');
        }



        $jointoken = null;
        $title = 'Registration Form';

        if ($invitation) {
            $title =  "Joining {$invitation->name}";
            $jointoken = $request->input('invite');
        }
        return view('pages.auth.register', compact('jointoken', 'invitation', 'title'));
    }



    public function doLogout(Request $request)
    {

        $cookie = cookie()->forget('locked_user_id');
        auth()->logout();


        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out')->withCookie($cookie);
    }

    public static function locked_user()
    {
        $locked_user = request()->cookie('locked_user_id');
        if ($locked_user) {
            $user = User::find($locked_user)
                ?->get()
                ->first();
            return $user;
        }
        return null;
    }



    public function doRegister(Request $request)
    {

        $formFields = $request->validate([
            'name' => 'required|regex:/^\s*([a-zA-Z]+)\s+([a-zA-Z]+)\s*([a-zA-Z]+)?\s*$/',
            'gender' => 'in:female,male',
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed'],
            'phone' => 'sometimes|regex:/^\s*\d+\s*$/',
            'checkpolicy' => 'required',
            'role' => 'sometimes|in:admin,student, staff',
            'regno' => 'sometimes|regex:/^\s*\d+\s*$/'
        ], [
            'name.regex' => 'Requires only alphabet characters',
            'checkpolicy.required' => 'You must accept terms and conditions to proceed',
            'phone.regex' => 'Enter a valid phone number',
            'role.in' => 'You selected an invalid role',
            'regno.regex' => 'Reg Number of be a number'
        ]);

        // if invitation token exists add student to the set
        if ($request->has('jtoken')) {
            $set = AcademicSet::where('token', $request->input('jtoken'));
            if ($set) {
                $formFields['set_id'] = $set->first()->id;
            }
        }

        list($firstname, $lastname) = preg_split('/\s+/', $formFields['name']);

        $formFields['username'] = $this->generateUsername($firstname, $lastname);

        // Remove white spaces 
        $formFields = array_map(fn ($value) => trim($value), $formFields);

        $formFields['password'] = bcrypt($formFields['password']);
        request()->merge($formFields);

        $user = User::saveUser($formFields);

        auth()->login($user);

        return redirect('/')->with('success', 'Account Created');
    }











    // public function api_login(Request $request, TwoFactorRedirector $redirector)
    // {


    //     $username = $this->credential();
    //     $callbackUrl = $request->callbackUrl;



    //     $validator = Validator::make($request->all(), [
    //         'usermail' => 'required',
    //         $username => 'sometimes',
    //         'password' => 'required',
    //     ], [
    //         'usermail.required' => 'Email address must be provided',
    //         'password.required' => 'Passwords must be provided'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 401);
    //     }

    //     $user = User::where($username, $request->input($username))?->first();
    //     $logAttempts = 0;

    //     $exists = $user?->exists();


    //     if ($exists) {
    //         $logAttempts = $user->logAttempts;
    //         $logAttempts++;
    //     }


    //     $credentials = $request->only($username, 'password');
    //     $limit = 5;


    //     if (!$user) {
    //         return response([
    //             'error' => 'Invalid credentials',
    //         ], 401);
    //     }


    //     if (($logAttempts + 1) === $limit) {
    //         //session()->set()
    //     }

    //     if ($logAttempts >= 5 && $user->isLocked()) {

    //         return response()->json([
    //             'error' => 'Account has been locked'
    //         ], 401);
    //     } else if (!Hash::check($request->password, $user->password)) {

    //         $user->incrementLogAttempts();

    //         return response()->json([
    //             'error' => 'Invalid credentials'
    //         ], 401);

    //         // on last invalid login attempt, lock user account
    //         // and send a notification email to the user 

    //         return response([
    //             'attempts' => $logAttempts + 1 === $limit
    //         ]);

    //         if (($logAttempts + 1) === $limit) {

    //             $otp = $user->generateOtp();
    //             Session()->put('otp', $otp);
    //             Session()->put('otp_user_id', $user->id);

    //             Email(new OtpMail($user, $otp), $user);

    //             return redirect()->route('otp.verify');
    //         }
    //     }

    //     // if remember me is enabled save cookie
    //     $cookie = null;

    //     $holder = 'temporary_token';
    //     if ($request->remember) {
    //         $cookie = cookie('locked_user_id', $user->id, 525600 * 60);
    //         $holder = 'persistent_session';
    //     }

    //     if (!empty($user->two_factor_secret)) {
    //         // Generate the URL for 2FA verification
    //         $verificationUrl = $redirector->redirect($request)->getTargetUrl();

    //         // Return the 2FA verification URL
    //         return response()->json([
    //             'error' => 'Two-factor authentication required',
    //             'redirect' => $verificationUrl,
    //         ], 401);
    //     }



    //     $ipAddress = request()->ip();
    //     $userAgent = request()->userAgent();

    //     if (!Device::check($user->id)) {

    //         // Report login with new device to user
    //         Email(new NewDevice($user), $user);
    //     }
    //     // Check if 2 Factor Authentication is enabled
    //     else if (!empty($user->secret)) {
    //         return $redirector->redirect($request);
    //         // Redirect to 2FA verification page
    //         return response([
    //             'redirect' => route('2fa.verify')
    //         ], 401);
    //     }

    //     $email = Email(new LoginMail($user), $user);

    //     Auth::login($user, $request->remember);
    //     $auth = Auth::user();


    //     $token = $auth->createToken($auth->role)->plainTextToken;



    //     Session()->put('auth_token', $token);
    //     //$request->session()->regenerate();

    //     $user->unlockAccount();



    //     $response = [
    //         "$holder" => $token,
    //         'success' => 'Login successfully',
    //         'email'  => $user->email,
    //         'name' => $user->name,
    //         'redirect' => '/home',
    //         'role' => $user->role
    //     ];


    //     if ($callbackUrl) {
    //         $response['redirect'] = $callbackUrl;
    //         session()->flash('success', 'Welcome back! we are glad to have you back. Let\'s continue from where you left off.');
    //     }

    //     ActivityLog::logLoginActivity($user);

    //     $response = response()
    //         ->json($response);


    //     if ($cookie) {
    //         return $response->cookie($cookie);
    //     }

    //     return $response;
    // }



    public function api_login(Request $request, TwoFactorRedirector $redirector)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'usermail' => 'required',
            $this->credential() => 'sometimes',
            'password' => 'required',
        ], [
            'usermail.required' => 'Email address must be provided',
            'password.required' => 'Password must be provided'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        // Attempt to authenticate the user
        $user = $this->authenticateUser($request);

        
        if (!$user) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        

        // Handle account lockout if necessary
        if ($this->isAccountLocked($user)) {
            return response()->json(['error' => 'Account has been locked'], 401);
        }



        // Check if 2FA is enabled
        $send2FA  = false;
        if ($user->two_factor_status === 'enabled') {
            if ($user->two_factor_frequency == 'new_device') {
                if (!Device::check($user->id)) {
                    $send2FA = true;
                }
            } else if ($user->two_factor_frequency == 'always') {
                $send2FA = true;
            }
        }

        if ($send2FA) {
            Email(new NewDevice($user), $user);

            return response()->json([
                // 'error' => 'Two-factor authentication required',
                'cause' => '2fa',
                'user_email' => $user->email
                // 'redirect' => route('2fa.verify')
            ], 401);
        }


        // Determine token holder based on remember flag

        $tokenHolder = !empty($request->rememberme) ? 'persistent_session' : 'temporary_session';

        Auth::login($user, $request->rememberme ?? false);

        $token = $user->createToken($user->role)->plainTextToken;


        // Prepare success response
        $response = [
            $tokenHolder => $token,
            'success' => 'Login successfully',
            'email'  => $user->email,
            'name' => $user->name,
            'redirect' => '/home',
            'role' => $user->role
        ];

        // Optionally set redirect URL if provided
        if ($request->callbackUrl) {
            $response['redirect'] = $request->callbackUrl;
            session()->flash('success', 'Welcome back! We are glad to have you back. Let\'s continue from where you left off.');
        }

        // Log login activity
        ActivityLog::logLoginActivity($user);
        return response()->json($response);
    }
    public function credential()
    {
        $login = request()->input('usermail');
        $field = 'username';
        if (ctype_digit($login)) {
            $field = 'phone';
        }
        elseif (preg_match('/^([^@]+)@([^@]+)$/', $login)) {
            $field = 'email';
        }
        
        // $field = ctype_digit($login) ? 'phone' : ('email');
        request()->merge([$field => $login]);
        return $field;
    }


    // Authenticate the user based on provided credentials
    private function authenticateUser(Request $request)
    {
        $username = $this->credential();
        return User::where($username, $request->input($username))->first();
    }

    // Check if the user's account is locked
    private function isAccountLocked($user)
    {
        return $user && $user->isLocked();
    }



    /**
     * Refresh the access token using the refresh token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken(Request $request)
    {
        $request->validate([
            'token' => 'required',
        ]);

        $refreshToken = $request->input('token');
        return [Auth::guard('sanctum')->user()];

        // Attempt to refresh the token
        if (Auth::guard('sanctum')->attempt(['refresh_token' => $refreshToken])) {
            // If refresh token is valid, generate a new access token
            $user = Auth::guard('sanctum')->user();
            $token = $user->createToken($user->role)->plainTextToken;

            return response()->json(['access_token' => $token]);
        }

        // If refresh token is invalid, return an error response
        return response()->json(['error' => 'Invalid refresh token'], 401);
    }

    public function api_register_student(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed',
            'email' => 'required|email|unique:users',
            'phone' => 'required|numeric',
            'name' => 'required|regex:/^([a-zA-Z\s]+)$/',
            'address' => 'sometimes',

            'reg_no' => 'required|numeric',
            'set_id' => 'required|exists:sets,id',

            'lga' => 'sometimes',
            'state' => 'sometimes',
            'country' => 'sometimes',
        ], [
            'password.required' => 'Password is required',
            'password.confirmed' => 'Passwords do not match',
            'email.required' => 'Email is required',
            'email.email' => 'Invalid email address',
            'email.unique' => 'Email address already exists',
            'phone.required' => 'Phone number is required',
            'phone.numeric' => 'Phone number must be number',
            'name.required' => 'Surname and othernames must be provided',
            'name.regex' => 'Name must be only english letters',
            'reg_no.required' => 'Registration Number must be required',
            'reg_no.numeric' => 'Registration Number must be numeric',
            'set_id.exists' => 'Class has been removed'
        ]);


        $role = 'student';


        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $formFields = Arr::except($validator->validated(), 'password_confirmation');





        // Assigned the id of the account that created the user
        // $formFields['created_by'] = $request->user()->id();


        // Make phone number the password if no password is provided
        if (!$request->has('password')) {
            $formFields['password'] = $request->input('phone');
        }
        $formFields['role'] = $role;

        $formFields['password'] = bcrypt($formFields['password']);

        // Add the new account to User model for authe
        $user = User::createUser($formFields);

        // Assign user id to student account
        $formFields['id'] = $user->id;


        if ($uploadedFile = UploaderController::uploadFile('image', 'pic')) {
            //$formFields['image'] = $uploadedFile;
        }

        $create = Student::create($formFields);
        if ($create) {

            // Send activation link to the student provided email
            Email(new NewStudentAccount($user), $user);
            session()->flash('register_success', 'Successfully created account');

            return response()->json([
                'success' => 'Successfully created account.',
                'redirect' => '/registered'
            ]);
        }
        return response()->json(['error' => 'Failed to create an account.'], 401);
    }

    public function api_request_activation_link(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
        ], [
            'email.required' => 'Email address must be provided',
            'email.email' => 'Email address is not valid',
            'email.exists' => 'No account associated with email address',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        $account = User::where('email', '=', $request->email)->first();


        Email(new NewStudentAccount($account), $account);

        return response()->json(['success' => 'Activation link has been sent to your email address']);
    }











    public function verifyTwoFactor(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        // Retrieve the user based on user ID
        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Verify the 2FA code
        $valid = $user->verifyTwoFactorCode($request->code);

        if (!$valid) {
            return response()->json(['error' => 'Invalid 2FA code'], 401);
        }

        // Log the user in and generate token
        $auth = Auth::login($user);
        $token = $auth->createToken($auth->role)->plainTextToken;

        // Prepare success response
        $response = [
            'token' => $token,
            'success' => '2FA verification successful',
            'email'  => $user->email,
            'name' => $user->name,
            'redirect' => '/home',
            'role' => $user->role
        ];

        // Optionally set redirect URL if provided
        if ($request->has('callbackUrl')) {
            $response['redirect'] = $request->callbackUrl;
            session()->flash('success', 'Welcome back! We are glad to have you back. Let\'s continue from where you left off.');
        }

        // Log login activity
        ActivityLog::logLoginActivity($user);

        return response()->json($response);
    }
}
