<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AcademicSession;
use App\Models\Course;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PortalConfigurationController extends Controller
{
    public function show(Request $request) {
        
        $sessions = AcademicSession::latest()->get();
        $active_session = $sessions->first();
        $open_semesters = AcademicSession::semestersOpenForCourseRegistration();
        $advisors = Staff::where('is_class_advisor', true)->count();
        $students = Student::count();
        $courses = Course::count();
        $staffs = Staff::count();
        $account = $request->user()?->account();

        $count = compact('students', 'courses', 'staffs', 'advisors');  

        
        return compact('sessions', 'active_session', 'open_semesters', 'count', 'account');
    }

    public function close_semester_course_registration(Request $request) {
        $validator = Validator::make($request->all(), [
            'semester' => 'required',
            'session' => 'required',
        ], [
            'semester.required' => 'Semester must be provided',
            'session.required' => 'Academic session is missing',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }


        $semester = $request->semester;
        $session = $request->session;
        $semester_status = strtolower($semester).'_course_registration_status';

        $sessionRecord = AcademicSession::where('name', $request->session)->first();

        $sessionRecord->fill([
            "$semester_status" => 'CLOSED'
        ])->save();

        return response()->json([
            'success' => "$session $semester has been CLOSED successfully",
        ]);






    }




    public static function initializeAdminAccount() {
        $admin = User::where('role', 'admin')->exists();
       
        
        if (!$admin) {

            User::createAccount([
                  "id"=> 1,
                  "gender"=> "male",
                  "email"=> "admin@gmail.com",
                  "username"=> "admin",
                  "name"=> "G. A. Chukwudebe",
                  "role"=> "admin",
                  "password"=> "admin",
                  "staff_id"=> "ADM67890",
                  "rank"=> "Professor",
                  "title"=> "Engr. Prof. (Mrs)"
            ]);

        }
    }
}
