<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DBExportController;
use App\Models\{Student,Course, Result, AcademicRecord, AcademicSet, Admin, Device, Enrollment, Staff, User};
use App\Http\Controllers\ {
    TwoFactorController,
    QuizController,
    AuthController,
    ModeratorController,
    CourseController,
    ResultController,
    AdminController,
    AdvisorController,
    AnnouncementController,
    ClassController,
    HODController,
    ResultsController,
    StudentController,
    TodoController,
    UserController,
    StaffController,
    MailController,
    MaterialController,
    TechnologistController,
    TestController
};
use App\Http\Controllers\ForgotPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use PhpParser\Builder\ClassConst;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// HOME
Route::get('/', fn() => redirect(auth()->check()?'/home':'/login'));
Route::get('/home', [UserController::class, 'dashboard'])->name('home');


Route::get('/api/notifications/stream', [AnnouncementController::class, 'announcemnt_stream']);

/********TESTING ROUTES**********/
    // Route::get('/generate', [TestController::class, 'generate']);
    Route::get('/calender', fn() => view('components.calendar'));
    Route::get('/export', [DBExportController::class, 'exportToJson']);
    Route::get('/tester', function(Request $request) {
        $results = Result::get();
        
        foreach($results as $result) {
            $result->exam = $result->exams;
            $result->test = $result->tests;
            $result->lab = $result->labs;
            $result->save();
        }

        dd('DOne');

        $user = auth()->user();
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();
        return view('pages.test', compact('user', 'ipAddress', 'userAgent'));
    });
    Route::get('/info', function(){
        echo phpinfo();
    });
    Route::get('/in/{name}', function(Request $request, string $name) {
        $accounts = ['staff' => 19, 'chike'=>3, 'admin'=>1, 'advisor'=>2, 'student'=>mt_rand(3, 20)];
        if (array_key_exists($name, $accounts)) {
            Auth::loginUsingId($accounts[$name]);
            return redirect('/home');
        }
    });

/**2FA */
Route::get('/2fa/verify', [TwoFactorController::class, 'verify'])->name('2fa.verify');
Route::post('/2fa/verify', [TwoFactorController::class, 'postVerify'])->name('2fa.post.verify');


/*********GUEST ROUTES***********/
    Route::middleware('guest')->group(function(){

        Route::get('/login', [AuthController::class, 'login'])->name('login');
        Route::get('/register', [AuthController::class, 'register'])->name('register');

        Route::get('/lost-password', fn() => view('pages.auth.lost-password'))->middleware('guest');


       
        
        Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'passwordResetView'])->name('password.reset');
        
        Route::post('/reset-password', function (Request $request) {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|confirmed|min:8',
            ]);
        
            if ($validator->fails()) {
                return back()->withErrors($validator->errors());
            }
        
            $status = Password::reset($request->only('email', 'password', 'token'), function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            });
        
            return $status === Password::PASSWORD_RESET ? redirect('/login')->with('status', 'Your password has been reset!') : back()->withErrors(['email' => 'Invalid token.']);
        })->middleware('guest')->name('password.update');
        
        Route::get('/otp',function(){
        
            return view('pages.auth.otp');
    })->name('otp.verify');

        Route::get('/registered', [AuthController::class, 'success_landing_page']);
        
        Route::post('/dologin', [AuthController::class, 'doLogin']);
        
        

        Route::get('/lockscreen', function(Request $request){
                if ($profile = AuthController::locked_user()) {
                    return view('pages.auth.lockscreen', compact('profile'));
                }
                return redirect('/login');
        });
    });





/***AUTHENTICATED USERS ROUTES***/

    Route::middleware('auth')->group(function(){
       

        /**MODERATORS ROUTES**/

            Route::get('/classlist', [ClassController::class, 'classlist'])->name('view.class_list');

        
            Route::get('/courses', [CourseController::class, 'index'])
                ->name('view.courses')
                ->middleware('role:mod');
            
            Route::post('/announcement/add', [AnnouncementController::class, 'add'])->middleware('role:mod');


            Route::get('/announcement', [AnnouncementController::class, 'insert'])->middleware('role:mod');

            
            // Display For uploading of results
            Route::get('/upload-results', fn()=> view('pages.advisor.upload-results'))->middleware('role:mod');

            Route::get('/cgpa_summary', fn() => view('pages.advisor.cgpa-summary-result'))->name('advisor.cgpa_summary')->middleware('role:advisor');

            

            Route::post('/update/staff', [StaffController::class, 'update'])
                ->name('update.staff');
            
            

            Route::get('/material/insert', [MaterialController::class, 'insert'])
                ->name('insert.material')
                ->middleware('role:staff');

            Route::post('/material/upload', [MaterialController::class, 'store'])
                ->name('upload.material')
                ->middleware('role:staff');

            Route::get('/material/{material}', [MaterialController::class, 'show'])
                ->name('show.material');

        /**ADMIN ROUTES**/

            Route::get('/admin/courses',  [CourseController::class, 'admin_view_courses'])
            ->name('admin.view-courses')
            ->middleware('role:admin');

            Route::get('/admin/classes', [ClassController::class, 'show_to_admin'])
                ->name('admin.classes')
                ->middleware('role:admin');

            Route::get('/admin/class/add', [ClassController::class, 'add'])
                ->name('admin.add-class')
                ->middleware('role:admin');

            Route::get('/admin/class/edit', [ClassController::class, 'show_to_admin'])
                ->name('admin.edit-class')
                ->middleware('role:admin');

            Route::post('/admin/class/store', [ClassController::class, 'store'])
                ->name('admin.store-new-class')
                ->middleware('role:admin');


            Route::get('/admin/advisor/edit', [AdvisorController::class, 'update_admin'])
            ->name('admin.edit_course')
            ->middleware('role:admin');

            Route::post('/admin/advisor/store', [AdvisorController::class, 'store'])
            ->name('create_advisor')
            ->middleware('role:admin');

            Route::get('/admin/result/pending', [ResultsController::class, 'awaitingResults'])
                ->name('awaiting-results')
                ->middleware('role:admin');
            Route::post('/admin/approve/pending', [ResultsController::class, 'approveResults'])
                ->name('approveAwaitingResults')
                ->middleware('role:admin');



            Route::get('/mail',[MailController::class, 'sendMail']);










            Route::get('/admin/course/edit', [CourseController::class, 'edit']);
           

            Route::get('/admin/advisors', [AdvisorController::class, 'index_admin']);
           
            Route::get('/admin/advisor/add', [AdvisorController::class, 'add']);
           
            

            Route::get('/admin/staff/add', [StaffController::class, 'add'])
            ->name('add.staff')
            ->middleware('role:admin');



            Route::get('/timetable', fn() => view('pages.admin.timetable'));

            Route::post('/update', [UserController::class, 'update']);
           

            
            

            

            
            


            Route::post('/staff/store', [StaffController::class, 'store'])
                ->name('store.staff')
                ->middleware('role:admin');
            
           
           
                Route::get('/courses/add', [CourseController::class, 'addCourseForm'])
                ->name('add.course')->middleware('role:admin');

                Route::post('/courses/add', [CourseController::class, 'store'])
                ->name('store.course')->middleware('role:admin');

                

            Route::get('/admin/course/{course}', [CourseController::class, 'showForAdmin'])
                ->name('admin-view.course')->middleware('role:admin');
            
            Route::post('/admin/advisor/update', [AdminController::class, 'update_advisor'])
                ->name('advisor.admin_update')
                ->middleware('role:admin');
        
           
        
            // Admin: update course 
            Route::post('/courses/update', [CourseController::class, 'updateCourse'])
            ->middleware('role:admin')
            ->name('update.course');
        

           


            
          

        /**ADVISORS ROUTES**/
            Route::get('/advisor/transcript', [ResultsController::class, 'view_transcripts'])
                ->name('nav.transcript')
                ->middleware('role:advisor');

            Route::post('/generate_transcripts', [ModeratorController::class, 'loadTranscript'])
                ->name('generate.transcript')
                ->middleware('role:advisor');
                



            Route::get('/advisor/results', [ResultsController::class, 'index'])
                ->name('advisor.nav-result')
                ->middleware('role:advisor');

        /**STAFFS ROUTES**/
            Route::get('/moderator/add-results', [ResultsController::class, 'insert_result'])
                ->name('results.insert_results')
                ->middleware('role:staff');



            
            Route::post('/import-results', [ResultsController::class, 'uploadExcel'])
                ->name('import.results')
                ->middleware('role:staff');

            Route::get('/upload-form', [ResultsController::class, 'insert'])
                ->name('results.upload_form')         
                ->middleware('role:staff');
            
            Route::get('/results/spreadsheet', [ResultsController::class, 'spreadsheet'])
                ->name('upload.ogr')
                ->middleware('role:staff');

            

            Route::get('/staff/uploadManually', [ResultsController::class, 'staff_add_result'])
                ->name('staff.add-result')
                ->middleware('role:staff');

                Route::get('/staff/results',  [StaffController::class, 'staff_result_index'])
                ->name('staff.results');
            
            // Route::get('/staff/results', [ResultsController::class, 'staff_result_index'])
            //     ->name('staff.results')
            //     ->middleware('role:staff');

            Route::get('/staff/lab-score-results', [ResultsController::class, 'staff_lab_score_results'])
                ->name('staff.lab_score_results')
                ->middleware('role:staff');

            Route::get('/admin/settings', [AdminController::class, 'settings'])
                ->name('admin.settings')
                ->middleware('role:admin');


        /**TECHNOLOGISTS ROUTES */
            Route::get('/technologist/eattendance', [TechnologistController::class, 'attendance_index_route']);
            Route::get('/technologist/lab_results', [TechnologistController::class, 'lab_results'])->middleware('role:staff,designation:technologist');

        /**STUDENT ROUTES**/
            Route::get('/student/register-courses', [CourseController::class, 'registerCourse'])
                ->middleware('role:student')
                ->name('register.course');

            Route::get('/student/profile', [StudentController::class, 'profile_settings'])->middleware('role:student');
               
            Route::get('/student/results', [StudentController::class, 'show_results'])
                ->name('student.results')
                ->middleware('role:student');

            Route::get('/student/course_reg_history', [StudentController::class, 'course_registration_history_page'])
                ->name('student.course_history')
                ->middleware('role:student');
            
            Route::get('/course-registration', [StudentController::class, 'course_registration'])->middleware('role:student');
            
            Route::post('/course_registration', fn()=>view('pages.student.course_registration-post'))->middleware('role:student');
            
            Route::get('/course-registration-details', [CourseController::class, 'viewEnrollments'])->name('view.enrollment')->middleware('role:student');
            
        


        /**OTHERS ROUTES**/
            Route::post('/todo/add', [TodoController::class, 'store']);
        
            Route::get('/search/students', [StudentController::class, 'search_students'])->name('search.students');
            
            Route::get('/course/{course}', [CourseController::class, 'show'])
                ->name('view.course');

            Route::get('/settings', fn() => view('pages.general.settings'))
                ->name('settings');

            Route::get('/change_password', fn() => view('pages.general.change-password'))
                ->name('change_password');
            Route::post('/change_password', [UserController::class, 'changePassword']);

            Route::get('/activities', [UserController::class, 'activities']);

            Route::post('/user/change_profile_pic', [UserController::class, 'changeProfilePic']);

                
            Route::get('/logout', [AuthController::class, 'doLogout'])->name('logout');

            Route::get('/profilepic/{user}', [UserController::class, 'display_picture'])->name('profilepic');




            # ADMIN ROUTES
            Route::get('/admin/staffs', [AdminController::class, 'show_staffs'])
                ->name('admin.show-staffs')
                ->middleware('role:admin');

            Route::get('/moderators', [AdminController::class, 'show_moderators'])
                ->name('admin.show-moderators')
                ->middleware('role:admin');
                

            Route::get('/admin/classes', [AdminController::class, 'show_classes'])
                ->name('admin.show-classes')
                ->middleware('role:admin');

            Route::get('/admin/courses', [AdminController::class, 'show_courses'])
                ->name('admin.show-courses')
                ->middleware('role:admin');

            Route::get('/admin/advisors', [AdminController::class, 'show_advisors'])
                ->name('admin.show-advisors')
                ->middleware('role:admin');

            Route::get('/admin/students', [AdminController::class, 'show_students'])
                ->name('admin.show-students')
                ->middleware('role:admin');

            Route::get('/admin/results', [AdminController::class, 'show_results'])
                ->name('admin.show-results')
                ->middleware('role:admin');

            Route::get('/page/configurations', [AdminController::class, 'control_panel'])
                ->name('admin.show-configurations')
                ->middleware('role:admin');


            #HOD ROUTES
            Route::get('/hod/staff', [HODController::class, 'index_staff']);
            Route::get('/hod/results', [HODController::class, 'index_results']);
            Route::get('/hod/courses', [HODController::class, 'index_courses']);



            # Class Advisors Routes
            Route::get('/advisor/class', [AdvisorController::class, 'show_class'])
                ->name('advisor.show-class');

            Route::get('/advisor/transcript', [AdvisorController::class, 'transcript'])
                ->name('advisor.transcript');

            Route::get('/advisor/results', [AdvisorController::class, 'show_results'])
                ->name('advisor.show-results');
            
            Route::get('/advisor/students-course-registrations', [AdvisorController::class, 'show_students_course_reg'])
                ->name('advisor.students-course-registrations');


           

            
            Route::get('/advisor/{advisor}', [UserController::class, 'show_advisor'])
                ->name('admin.show-advisors');
            


            
            Route::get('/activate-account', [UserController::class, 'activate_account'])
                ->name('activate-account');


            
            Route::get('/profile', [UserController::class, 'profile_settings']);
                
                

                

    });


//     public function sendAccountCreationEmail(User $user)
// {
//     Mail::to($user)->send(new AccountCreationMail($user));
// }

// public function sendUnusualActivityEmail(User $user, string $ipAddress, string $userAgent)
// {
//     Mail::to($user)->send(new UnusualActivityMail($user, $ipAddress, $userAgent));
// }