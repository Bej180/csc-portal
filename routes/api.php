<?php

use App\Events\AnnouncementEvent;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdvisorController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AppAdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Student;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\HODController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ModeratorController;
use App\Http\Controllers\PortalConfigurationController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TechnologistController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use App\Models\Announcement;
use App\Models\Course;
use App\Models\Result;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Broadcasting\Broadcast;
use Illuminate\Support\Facades\Validator;

/*
GET /quizzes - List all quizzes (index)
GET /quizzes/{quiz} - Show a specific quiz (show)
GET /quizzes/create - Show form to create a new quiz (create)
POST /quizzes - Store a newly created quiz (store)
GET /quizzes/{quiz}/edit - Show form to edit a specific quiz (edit)
PUT /quizzes/{quiz} - Update an existing quiz (update)
DELETE /quizzes/{quiz} - Delete a quiz (destroy)
*/

/*
HTTP_OK (200): The request has succeeded.
HTTP_CREATED (201): The request has been fulfilled and resulted in a new resource being created.
HTTP_ACCEPTED (202): The request has been accepted for processing, but the processing has not been completed.
HTTP_NO_CONTENT (204): The server successfully processed the request and is not returning any content.
HTTP_BAD_REQUEST (400): The server could not understand the request due to invalid syntax.
HTTP_UNAUTHORIZED (401): The client must authenticate itself to get the requested response.
HTTP_FORBIDDEN (403): The client does not have access rights to the content.
HTTP_NOT_FOUND (404): The server can not find the requested resource.
HTTP_METHOD_NOT_ALLOWED (405): T


*/
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/csrf-end-point', fn () => response()->json([
    'token' => csrf_token()
], 200));


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/testtter', function(Request $request) {
        return response()->json([
            'error' => 'not found'
        ], 400);
    });
});

Route::get('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);

    return ['token' => $token->plainTextToken];
});



// Route::get('/login', [AuthController::class, 'apiLogin']);

Route::post('/dologin', [AuthController::class, 'api_login']);
Route::post('/app/auth/verify_otp', [AuthController::class, 'verifyOTP']);

Route::post('/auth/generate-qr-code', 'QrCodeController@generateQrCode')->middleware('auth');
Route::post('/auth/check-scan-status/{token}', 'QrCodeController@checkScanStatus')->middleware('auth');
Route::post('/auth/regenerate-qr-code', 'QrCodeController@regenerateQrCode')->middleware('auth');
Route::post('/auth/scan-qr-code', 'QrCodeController@scanQrCode')->middleware('auth:api');

// Route::get('/register', 'AuthController@register');
Route::post('/doRegister', [AuthController::class, 'api_register_student']);

Route::post('/request_activation_link', [AuthController::class, 'api_request_activation_link']);




/**PROTECTED API */
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/authenticate', [AuthController::class, 'authenticate']);
    
    Route::get('/testapi', function (Request $request) {
        return $_SERVER;
    });
});
Route::post('/fetchawaiting-results', [ResultsController::class, 'api_getAwaitingResults']);


Route::post('/app/student/show', [StudentController::class, 'api_getStudent']);

Route::get('/findStudent', function (Request $request) {
    $student = Student::where('id', $request->query)->orWhere('name', $request->query)->with('user');
});



Route::post('/search/courses', [CourseController::class, 'api_scan']);
Route::post('/app/course/show', [CourseController::class, 'getCourseById']);


Route::get('/student_course_details_home', [CourseController::class, 'student_course_details_home'])->middleware('auth');



// show students
Route::post('/student', [StudentController::class, 'getStudent']);
Route::post('/staff', [AdvisorController::class, 'getAdvisor']);

// Route::post('/search/students', [StudentController::class, 'search_students']);



Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/class', [ClassController::class, 'api_fetchClass']);

    Route::post('/class/generate_name', [ClassController::class, 'generateClassName']);





    Route::post('/classes', [ClassController::class, 'api_index']);

    


});



Route::post('/enrolledCourses', [CourseController::class, 'api_getEnrolledCourses']);



Route::post('/staff', [StaffController::class, 'getStaff']);



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});







/// NEW ROUTES
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/admin/dashboard', function (Request $request) {

        $students = Student::all();
        $results = Result::paginate(6);
        $courses = Course::active();
        $totalActiveCourses = $courses->get()->count();
        $inActiveCourses = Course::inActive();
        $totalInactiveCourses = $inActiveCourses->count();
        $totalStudents = Student::get()->count();

        return response(compact('students', 'results', 'courses', 'totalInactiveCourses', 'totalStudents', 'inActiveCourses', 'totalStudents'));
    });

    Route::get('/fetch/user', function (Request $request) {
        return $request->user()->account();
    });

    
    Route::post('/app/auth/logout', [AuthController::class, 'api_logout'])->middleware('auth');



    
    Route::post('/app/auth/refresh_token', [AuthController::class, 'refreshToken']);

    Route::post('/app/user/view_profile', [UserController::class, 'viewProfile']);
    Route::post('/app/user/update_profile', [UserController::class, 'updateProfile']);



    /**ADMIN CONTROLLERS */

    Route::post('/admin/staff/add', [AdminController::class, 'insert_staff']);

    Route::post('/staff', [AdminController::class, 'get_staff']);



    /**ADMIN CONTROLLERS END */

    
});



/// New Routes for guests
Route::post('/app/auth/send_reset_link', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('pages.auth.lost-password');
Route::post('/app/auth/resetpassword', [ForgotPasswordController::class, 'resetPassword']);
Route::post('/app/auth/resetpassword/timer', [ForgotPasswordController::class, 'getTimer']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    // New Routes for users
    Route::post('/app/admin/recyclebin', [AdminController::class, 'recycleBin']);
    Route::post('/app/admin/classes', [ClassController::class, 'classes']);
    Route::post('/app/admin/classes/create', [ClassController::class, 'create']);
    Route::post('/app/admin/classes/advisor/add', [ClassController::class, 'add_advisor']);
    Route::post('/app/admin/session/store', [SessionController::class, 'store']);
    Route::post('/app/admin/session/update', [SessionController::class, 'update']);
    Route::post('/app/admin/session/course_registration_status/update', [SessionController::class, 'update_course_registration_status']);
    // Route::post('/api/admin/sessions/reopen_registration', [SessionController::class, 'reopen_registration']);
    Route::post('/app/admin/courses', [CourseController::class, 'api_getCourses']);
    Route::post('/app/admin/courses/prerequisites/index', [CourseController::class, 'index_prerequisites']);
    Route::post('/app/admin/courses/index', [CourseController::class, 'index_courses']);
    Route::post('/app/admin/courses/search', [CourseController::class, 'search_courses']);
    Route::post('/app/admin/courses/archive', [CourseController::class, 'archive_course']);
    Route::post('/app/admin/courses/create', [CourseController::class, 'createCourse']);
    Route::post('/app/admin/courses/make_cordinator', [CourseController::class, 'makeCourseCordinator']);
    // Route::post('/app/admin/courses/allocate/to_staff', [CourseController::class, 'allocate_to_staff']);
    Route::post('/app/admin/student/create', [StudentController::class, 'store']);
    
    Route::post('/app/admin/student/enrollments/course/drop', [AdminController::class, 'drop_course_from_enrollment']);
    Route::post('/app/admin/student/enrollments/course/add', [AdminController::class, 'add_course_to_enrollment']);
    Route::post('/app/adminappser/enrollments/delete', [AdminController::class, 'delete_enrollments']);
    Route::post('/app/admin/enrollments/update', [AdminController::class, 'update_enrollments']);

    Route::post('/app/admin/staff/create', [AdminController::class, 'store_staff']);
    Route::post('/app/admin/staff/index', [AdminController::class, 'index_staff']);
    Route::post('/app/admin/user/resetlogins', [AdminController::class, 'resetLoginDetails']);


    // Moderators (Dean & HOD) routes    
    Route::post('/app/admin/moderators/index', [ModeratorController::class, 'index']);
    Route::post('/app/admin/moderators/hod/makeHOD', [ModeratorController::class, 'makeHOD']);
    Route::post('/app/admin/moderators/dean/add', [ModeratorController::class, 'addDean']);

    # Portal Configuration
    Route::post('/app/admin/session/show', [PortalConfigurationController::class, 'show']);
    Route::post('/app/admin/session/close', [PortalConfigurationController::class, 'close_semester_course_registration']);



    Route::post('/app/advisor/dashboard', [AdvisorController::class, 'dashboard_api_data']);
    Route::post('/app/advisor/classes', [AdvisorController::class, 'index_classes']);
    Route::post('/app/advisor/student/generate_transcript', [AdvisorController::class, 'generate_transcript']);
    Route::post('/advisor/student/enrollments', [AdvisorController::class, 'studentEnrollments']);

    Route::post('/app/advisor/load_more_courses', [AdvisorController::class, 'getCourses']);
    Route::post('/app/advisor/load_more_students', [AdvisorController::class, 'getStudents']);
   
    Route::post('/app/class/generateInviteLink', [ClassController::class, 'apiGenerateInviteLink'])
        ->middleware('role:staff');
        
    Route::post('/app/class/import', [ClassController::class, 'importClassList'])
        ->middleware('role:admin,staff');

    Route::post('/app/class/withdrawInviteLink', [ClassController::class, 'apiWithdrawInvite'])
        ->middleware('role:staff');
    

    Route::post('/app/search/{role}', [UserController::class, 'apiGetUsers']);


    Route::post('/app/hod/results/index', [HODController::class, 'api_index_results']);
    Route::post('/app/staff/index', [UserController::class, 'get_staffs']);
    Route::post('/app/hod/results/approve', [HODController::class, 'approve_results']);
    Route::post('/app/hod/results/disapprove', [HODController::class, 'disapprove_results']);
    Route::post('/app/staff/show', [UserController::class, 'get_staff']);
    Route::post('/app/staff/course_allocation/deallocate', [UserController::class, 'deallocate_courses']);
    Route::post('/app/staff/course_allocation/allocate', [UserController::class, 'allocate_courses']);
    Route::post('/app/staff/course_allocation/allocatable/all', [UserController::class, 'allocatable_courses']);


    Route::post('/homepage', function(Request $request){
        $validator = Validator::make($request->all(), [
            'passkey' => 'sometimes|password',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }
    
        return $request->user();
    });

    // Staff routes
    Route::post('/app/staff/courses/students', [EnrollmentController::class, 'list_of_enrolled_students']);
    Route::post('/app/staff/courses/index', [StaffController::class, 'index_my_courses']);
    Route::post('/app/staff/result/add', [ResultsController::class, 'add_results']);
    Route::post('/app/staff/results/add', [ResultsController::class, 'save_results']);
    Route::post('/app/staff/results/upload_ogmr', [ResultsController::class, 'upload_ogmr']);
    Route::get('/app/staff/results/sessions',  [StaffController::class, 'results_uploaded_session']);
    Route::post('/app/staff/results/save_draft', [ResultsController::class, 'save_as_draft']);
    Route::post('/app/staff/results/index', [StaffController::class, 'staff_results_index_page']);
    Route::post('/app/staff/lab_scores/index', [StaffController::class, 'staff_lab_scores_index_page']);
    Route::post('/app/staff/results/approve_lab_scores', [StaffController::class, 'approve_lab_scores']);


    Route::post('/app/moderator/make_staff_class_advisor', [ModeratorController::class, 'makeStaffAdviser']);

    Route::post('/test', fn()=>response()->json(['alert.success' => 'Do you want to logout?'], 400));

    Route::post('/app/staff/course/results', [ResultsController::class, 'single_course_results']);

    


    # TECHNOLOGIST ROUTES
    Route::post('/app/technologist/lab_score/add', [TechnologistController::class, 'save_lab_scores']);
    Route::post('/app/technologist/lab_scores/index', [TechnologistController::class, 'index_lab_scores']);
    Route::post('/app/technologist/attendance/create', [TechnologistController::class, 'create_attendance_list']);
    Route::post('/app/technologist/attendance/index', [TechnologistController::class, 'attendance_lists']);
    Route::post('/app/technologist/attendance/mark', [TechnologistController::class, 'mark_attendance']);

    


    // ROutes for students 
    Route::post('/app/student/course_registration/courses', [EnrollmentController::class, 'index_courses_for_registration']);
    Route::post('/app/student/courses/enroll', [EnrollmentController::class, 'store']);
    Route::post('/app/student/results/index', [StudentController::class, 'index_results']);
    Route::post('/app/students/index', [UserController::class, 'index_students']);
    Route::post('/app/student/enrollments/show', [EnrollmentController::class, 'show']);
    Route::post('/app/student/enrollments/index', [EnrollmentController::class, 'index']);
    Route::post('/app/student/enrollments', [EnrollmentController::class, 'getStudentEnrollments']);
    Route::get('/app/student/cgpa', [StudentController::class, 'getCGPA']);


    
    Route::post('/app/todo/complete', [TodoController::class, 'mark_todo']);
    Route::post('/app/todo/store', [TodoController::class, 'store']);
    Route::post('/app/todo/index', [TodoController::class, 'get_todos']);
    Route::post('/app/todo/delete', [TodoController::class, 'delete_todo']);

    Route::post('/app/announcement/stream', [AnnouncementController::class, 'unseen_announcements']);
    Route::post('/app/announcement/announce', [AnnouncementController::class, 'announce']);
    Route::post('/app/announcements/announcer_index', [AnnouncementController::class, 'announcer_index']);
    Route::post('/app/announcement/mark_as_seen', [AnnouncementController::class, 'mark_as_seen']);


});
