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

    public function __construct() {
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





    public function makeStaffAdviser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'staff_id' => 'required|exists:staffs,id',
            'session' => 'required',
        ], [
            'staff_id.required' => 'Staff id must be provided',
            'staff_id.exists' => 'Invalid staff account',
            'session.required' => 'Session must be provided',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 401);
        }
        $session = $request->session;
        $staff_id = $request->staff_id;

        $class = AcademicSet::where('name', '=', $request->session)->first();

        // check if class already exists
        if ($class) {
            $class->update([
                'advisor_id' => $staff_id
            ]);
        } else {
            list($start_year, $end_year) = explode('/', $session);
            // create new class 
            $class = AcademicSet::create([
                'name' => $session,
                'start_year' => $start_year,
                'end_year' => $end_year,
                'advisor_id' => $staff_id
            ]);
        }

        $staff = Staff::find($staff_id);

        // Send notification to staff about the new role assigned to him/her
        Email(new ClassAdvisorAssignment($staff, $class), $class);


        return response()->json([
            'success' => 'Successfully made Class Advisor',
            'data' => $class
        ]);
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


    public function save_as_draft(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'session' => 'required|exists:sessions,name',
            'students' => 'required|array',
            'course_id' => 'required|exists:courses,id'
        ], [
            'session.required' => 'Session is missing',
            'session.exists' => 'Academic session not found',
            'students.required' => 'Student results are missing',
            'students.array' => 'Student results are missing',
            'course_id.required' => 'Course id is missing',
            'course_id.exists' => 'The course you are trying to save its results does not exist',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $students = $request->students;
        $existingResults = Result::where('course_id', $request->course_id)
            ->where('session', $request->session)
            ->get();

        if ($existingResults->isNotEmpty()) {
            $status = $existingResults[0]->status;

            if ($status === 'INCOMPLETE') {
                return response()->json(['error' => 'A technologist has uploaded lab score results for this course. You need to approve it before continuing'], 400);
            }
            if ($status === 'APPROVED') {
                return response()->json(['error' => 'You cannot update or add this result because it is already approved previously uploaded ones'], 400);
            }
        }

        foreach ($students as $student) {

            $result = Result::firstOrNew([
                'course_id' => $request->course_id,
                'session' => $request->session,
                'reg_no' => $student['reg_no']
            ]);

            $uploadedBy = $result->uploaded_by;

            if ($uploadedBy) {
                $result->updated_by = auth()->id();
            }
            else {
                $result->uploaded_by = auth()->id();
            }


            $result->status = 'DRAFT';
            $result->score = (int) $student['score'];
            $result->lab = $student['lab'];
            $result->exam = $student['exam'];
            $result->test = $student['test'];
            $result->grade = $result->getGrade();
            $result->remark = $result->getRemark();
            $result->grade_points = $result->getGradePoints();
            $result->save();
        }

        return response()->json(['scuccess' => 'Results updated successfully'], 200);
    }





    public function save_results(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'students' => 'required|array',
            'course_id' => 'required|exists:courses,id',
            'session' => 'required',
        ], [
            'students.required' => 'Students results were not submitted',
            'students.array' => 'Students results were not submitted',
            'course_id.required' => 'Course was not submitted',
            'course_id.exists' => 'The course you want to submit results is unknown.',
            'session.required' => 'Session must be provided',
        ]);

        // Handle validation errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            // Retrieve request data
            $course_id = $request->course_id;
            $session = $request->session;
            // Check existing results
            $existingResults = Result::where('course_id', $course_id)
                ->where('session', $session)
                ->first();

            if ($existingResults && in_array($existingResults->status, ['READY', 'INCOMPLETE'])) {
                return response()->json([
                    'error' => 'Results have already been uploaded',
                ], 400);
            }
            
            
            $has_practical = $existingResults?->has_practical;
            

            // Process new results
            $reference_id = generateToken('results.reference_id');
            $course = Course::findOrFail($course_id);
            
                

            foreach ($request->students as $student) {
                
                // Check if the student already exists
                $existingResult = Result::where([
                    'reg_no' => $student['reg_no'],
                    'course_id' => $course_id,
                    'session' => $session
                ])->first();

                
                if ($existingResult) {
                    $existingResult->exam = $student['exam'];
                    $existingResult->lab = $student['lab'];
                    $existingResult->test = $student['test'];
                    $existingResult->test = $student['score'];
                    $existingResult->setGradings();
                    $existingResult->status = 'PENDING';
                    $existingResult->save();
                    
                }
                else {
                    // result contains lab, exam, score, reg_no
                    $result = new Result($student);
                    $result->status = 'PENDING';
                    $result->units = $course->units;
                    $result->course_id = $course_id;
                    
                    $result->level = $course->level;
                    $result->setGradings();
                    
                    $result->reference_id = $reference_id;
                   
                    
                    $result->semester = $course->semester;
                    $result->uploaded_by = auth()->id();
                    $result->session = $session;
                    
                    $result->save();
                }
            }

            // Return success response
            return response()->json(['success' => 'Results uploaded successfully']);
        } catch (\Exception $e) {
            // Return error response
            return response()->json(['data'=>$e->getMessage(),'error' => 'Failed to upload results'], 401);
        }
    }


    public function list_of_enrolled_students(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session' => 'required',
            // 'semester' => 'required',
            'course_id' => 'required|exists:enrollments',
        ], [
            'session.required' => 'Session must be provided',
            // 'semester.required' => 'Session must be provided',
            'course.required' => 'Course must be provided',
            'course_id.exists' => 'No student registered for this course',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }
        $session = $request->get('session');
        $course_id = $request->course_id;
        $course = Course::find($course_id);
        $semester = $course->semester;

        $result = Result::where('course_id', $course_id)
            ->where('semester', $semester)
            ->where('session', $session)
            ->with('uploader')->first();

        if ($result && !in_array($result->status, ['incomplete', 'ready'])) {
            $uploader = $result->uploader?->name;
            if ($result->uploader->id === auth()->id()) {
                $uploader = 'you';
            }

            if ($result->status !== 'draft') {
                return response()->json([
                    'data' => $result,
                    'error' => 'Result has already been uploaded by ' . $uploader,
                ], 400);
            }
        }


        $enrolledStudents = Enrollment::students($semester, $session, $request->course_id);

        if (!count($enrolledStudents)) {
            return response()->json([

                'error' => "No student found to have enrolled in the course in $semester semeseter of $session academic session",
            ], 400);
        }


        return $enrolledStudents;
    }


    /**RESULT MANAGMENT SECTION STARTS*/
}
