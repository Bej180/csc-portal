<?php

namespace App\Http\Controllers;

use App\Mail\ClassAdvisorAssignment;
use App\Mail\NewStaffAccount;
use App\Models\AcademicSession;
use App\Models\AcademicSet;
use App\Models\ActivityLog;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Result;
use App\Models\Staff;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:staff');
    }

    public function add(Request $request)
    {
        return view('pages.admin.add-staff');
    }

    public function getStaff(Request $request)
    {
        $staff_id = $request->staff_id;

        $validator = Validator::make($request->all(), ['staff_id' => 'required|exists:staffs,id'], [
            'staff_id.required' => 'Staffs id is required'
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->first(), 401);
        }




        $staff = Staff::where('id', '=', $staff_id)->with(['user'])->first();

        $staff->courses = $staff->courses;
        $staff->image = $staff->picture();
        $staff->class = $staff->advisor();


        return $staff;
    }





    public function update(Request $request)
    {

        $formFields = $request->validate([
            'birthdate' => 'sometimes',
            'password' => 'sometimes',
            'address' => 'sometimes',
            'gender' => 'sometimes',
            'image' => 'sometimes',
            'staff_id' => 'sometimes',
            'courses' => 'sometimes',
            'staff_id' => 'required',
            'password' => 'sometimes|confirm'
        ]);

        $staff_id = $request->staff_id;
        $staff = Staff::find($staff_id);


        if (!$staff) {
            return redirect()->back()->with('error', 'Lectuer not found');
        }

        if ($name = User::getFullnameFromRequest()) {
            $formFields['name'] = $name;
        }
        if ($image = UploaderController::uploadFile()) {
            $formFields['image'] = $image;
        }


        try {
            if ($password = $staff->user->getHashedPassword()) {
                $formFields['password'] = $password;
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }



        $staff->user->update($formFields);

        $staff->update($formFields);

        return redirect()->back()->with('success', 'Account has been updated successfully');
    }


    public function staff_result_index(Request $request)
    {


        $sessions = \App\Models\AcademicSession::all();
        $auth = auth()->user();
        $staff = $auth->staff;
        $allocations = $staff->courses()->with('course')->get();




        return view('pages.staff.result-management.index', compact('sessions', 'allocations'));
    }



    public function index_my_courses(Request $request)
    {
        $auth = $request->user();
        $myAccount = $auth->staff;
        $courses = $myAccount->courses()
            ->with('course')
            ->orderBy('created_at')
            ->simplePaginate(10)
            ->map(function ($course) {
                $technologist = $course->technologists();
                $course->technologist = null;
                if (count($technologist)) {
                    $course->technologists = $technologist;
                }
                return $course;
            });

        return $courses;
    }

    public function results_uploaded_session(Request $request)
    {
        $user = $request->user();
        $courses = $user->staff->courses->pluck('course_id');
        $result = Enrollment::whereIn('course_id', $courses)
                        ->orderBy('session', 'desc')
                        ->get('session')
                        ->unique('session')
                        ->map(function($item) use ($courses) {
                          
                            $item->courses = Course::whereIn('id', $courses)
                                ->get(['id', 'code'])
                                ->map(function($course) use ($item) {
                                    $course->result = Result::where('session', $item->session)
                                        ->where('course_id', $course->id)
                                        ->leftJoin('users', 'users.id', '=', 'results.uploaded_by')
                                        ->leftJoin('courses', 'courses.id', '=', 'results.course_id')
                                        ->get([
                                            'course_id',
                                            'users.id',
                                            'courses.code', 
                                            'users.name', 
                                            'session', 
                                            'results.semester', 
                                            'results.level', 
                                            'results.status',
                                            'results.reference_id',
                                            'uploaded_by',
                                            'updated_by',
                                            'results.created_at'
                                        ])
                                        ->unique('code')
                                        ->first();
                                  
                                    return $course;
                                });

                            return $item;    
                        });

        if ($result->isNotEmpty()) {
            return $result;
        }
        return response()->json([
            'message' => 'No result uploaded yet',
        ], 400);
    }

    public function staff_results_index_page(Request $request)
    {
        $user = $request->user();
        $courses = $user->staff->courses->pluck('course_id');

        $results = Result::where('uploaded_by', $user->id)
            ->orWhere(function ($query) use ($user, $courses) {
                $query->whereIn('course_id', $courses)
                    ->whereNot('uploaded_by', $user->id)
                    ->whereNotIn('status', ['incomplete', 'ready']);
            })

            ->with('course')
            ->orderBy('created_at')
            ->groupBy(['reference_id'])
            ->simplePaginate(10)
            ->map(fn ($result) => $result);

        return $results;
    }
    public function index_staff_courses()
    {

        return view('pages.staff.course-management.courses');
    }
























    // ADVISOR METHODS 



    public function makeCourseRep(Request $request)
    {
        $request->validate([
            'set_id' => 'required',
            'course_rep' => 'required'
        ]);
        $set = AcademicSet::whereNotNull('course_rep');
        $set->update(['course_rep' => null]);
        AcademicSet::where(['id' => $request->input('set_id')])
            ->update(['course_rep' => $request->input('course_rep')]);
        return back()->with('message', 'Changed course rep');
    }




    public function profile(Request $request, string $username)
    {
        $staff = User::where('username', $username)?->first();


        return view('staff.profile', compact('staff'));
    }













    /**CLASS MANAGMENT SECTION STARTS*/
    public function show_class()
    {
        return  view('pages.staff.class-management.class');
    }

    public function classlist()
    {
        return view('pages.staff.class-management.staff.classlist');
    }

    /**CLASS MANAGMENT SECTION STARTS*/










    /**RESULT MANAGMENT SECTION STARTS*/
    public function transcript()
    {
        return view('pages.staff.result-management.transcript');
    }

    public function show_results(Request $request)
    {
        $user = auth()->user();
        $staff = $user->staff;
        $classes = $staff->classes;
        $semester = $request->get('semester');
        $session = $request->get('session');
        $course = $request->get('course');
        $class = $staff->class;
        $students = $class->students;
        return view('pages.staff.result-management.results', compact('classes', 'staff', 'class', 'students', 'classes', 'course', 'semester', 'session'));
    }



    public function staff_lab_scores_index_page(Request $request)
    {
        $user = $request->user();
        $staff = $user->staff;
        $courses = $staff->courses->pluck('course_id');

        $results = Result::whereIn('course_id', $courses)
            ->whereNot('uploaded_by', $user->id)
            ->with(['uploader', 'course'])
            ->groupBy('reference_id')
            ->latest()
            ->get()
            ->map(function ($result) {
                $result->status = $result->status === 'INCOMPLETE' ? 'PENDING' : 'APPROVED';
                return $result;
            })
            ->groupBy('status');

        return $results;
    }

    public function approve_lab_scores(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'results_id' => 'required|exists:results,reference_id',
        ], [
            'results_id.required' => 'A unique identifier for the results to be approved was not provided',
            'results_id.exists' => 'Results were not found. They may have been deleted'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $results = Result::where('reference_id', '=', $request->results_id)->with('course');

        $results->update([
            'status' => 'READY'
        ]);

        $firstResult = $results->first();
        $uploader = $firstResult->uploader;
        $date = Carbon::parse($firstResult->created_at);

        $getResults = $results->get();


        //Email(ApprovedResultNotification($uploader, $firstResult->course->code, $date->format('d/m/Y')), $uploader);
        // return $results;

        return response()->json([
            'success' => $results->first()->course->code . ' results have been approved successfully',
            'data' => $this->staff_lab_scores_index_page($request)
        ]);
    }








    /**RESULT MANAGMENT SECTION STARTS*/
}
