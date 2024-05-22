<?php

namespace App\Http\Controllers;

use App\Mail\LabScoreAddeNotification;
use App\Models\AcademicSession;
use App\Models\AttendanceList;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Result;
use App\Models\StudentsAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TechnologistController extends Controller
{
    public function attendance_index_route(Request $request)
    {
        $sessions = AcademicSession::get('name')->unique('name');
        $courses = auth()->user()->staff->courses;
        

        return view('pages.technologist.attendance', compact('sessions', 'courses'));
    }

    public function attendance_lists(Request $request)
    {
        // $attendanceList = AttendanceList::with(['course'])->latest()->paginate(10);
        $attendanceList = AttendanceList::query();

        $attendanceList = $attendanceList->with(['course']);
        
        if ($request->attendance_id) {
            $attendanceList->where('id', $request->attendance_id);
        }

        $attendanceList = $attendanceList->latest()->paginate(10);


        $attendanceList = $attendanceList->map(function ($attendance) {
            

            $attendance->students = Enrollment::where('session', $attendance->session)
                ->where('course_id', $attendance->course_id)
                ->with('student.user')
                ->get()
                ->unique('reg_no')
                ->map(function ($item) use ($attendance) {

                    $attended = StudentsAttendance::where('reg_no', $item->reg_no)
                        ->where('attendance_id', $attendance->id)
                        ->first();
                    $item->status = false;

                    if ($attended) {
                        $item->status = $attended->status;
                    }
                    return $item;

                });

            return $attendance;
        });

        return $attendanceList;
    }





    public function create_attendance_list(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'title' => 'required',
            'session' => 'required',
        ], [
            'course_id.required' => 'Course ID was not provided',
            'course_id.exists' => 'Course doesnt exist',
            'title.required' => 'Course title was not provided',
            'session.required' => 'Course session was not provided'
        ]);

        $validated = $validator->validated();

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }
        $validated['created_by'] = $request->user()->id;

        $attendance = AttendanceList::create($validated);

        if ($attendance) {
            $attendance->students = Enrollment::where('session', $request->session)
                ->where('course_id', $request->course_id)
                ->with('student')
                ->get()
                ->unique('reg_no');

            return response()->json([
                'success' => 'Attendance Profile created successfully',
                'data' => $attendance,
                'attendance_lists' => $this->attendance_lists()
            ]);
        }

        return response()->json([
            'error' => 'Failed to create attendance profile',
        ], 400);
    }


    public function lab_results(Request $request)
    {

        $sessions = AcademicSession::get('name')->unique('name');
        $courses = auth()->user()->staff->courses()->with('course')->get();

        return view('pages.technologist.lab_results',  compact('sessions', 'courses'));
    }


    public function index_lab_scores(Request $request)
    {

        $results = Result::where('uploaded_by', $request->user()->id)
            ->with(['uploader', 'course'])
            ->groupBy('reference_id')
            ->get();

        $results = $results->map(function ($result) {
            $result->status = $result->status === 'incomplete' ? 'PENDING' : 'APPROVED';
            return $result;
        });


        return $results;
    }


    public function save_lab_scores(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'results' => 'required|array',
            'course_id' => 'required|exists:courses,id',
            'semester' => 'required|in:HARMATTAN,RAIN',
            'session' => 'required',
        ], [
            'results.required' => 'Results were not submitted',
            'results.array' => 'Results were not submitted',
            'course_id.required' => 'Course was not submitted',
            'course_id.exists' => 'The course you want to submit results is unknown.',
            'semester.required' => 'Semester is required',
            'semester.in' => 'Semester must be only HARMATTAN or RAIN semester',
            'session.required' => 'Session must be provided',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
        try {


            $course_id = $request->course_id;
            $semester = $request->semester;
            $session = $request->session;

            $result = Result::where('course_id', $course_id)
                ->where('semester', $semester)
                ->where('session', $session)
                ->with('uploader')->first();

            if ($result) {
                $uploader = $result->uploader?->name;
                if ($result->uploader->id === auth()->id()) {
                    $uploader = 'you';
                }

                return response()->json([
                    'error' => 'Result has already been uploaded by ' . $uploader,
                ], 400);
            }

            $results = $request->results;


            $extracts = ['lab', 'reg_no', 'level'];
            $course = Course::find($course_id);
            $units = $course->units;

            $reference_id = generateToken('results.reference_id');

            foreach ($results as $n => $result) {
                $queue = new Result();
                foreach ($extracts as $extract) {
                    $queue->$extract = is_numeric($result[$extract]) ? (int) $result[$extract] : $result[$extract];
                }
                $queue->semester = $request->semester;
                $queue->level = $request->level;
                $queue->session = $request->session;
                $queue->units = $units;
                $queue->reference_id = $reference_id;

                $queue->course_id = $course_id;
                $queue->uploaded_by = auth()->id();

                $queue->save();
            }

            if ($course->cordinator) {
                // notify the course cordinator about the uploaded lab scores
                Email(new LabScoreAddeNotification($course), $course->cordinator);
            }

            return response([
                'success' => "Lab Scores uploadeded successfully. It's waiting for approval"
            ]);
        } catch (\Exception $e) {
            return response([
                'error' => $e->getMessage() . 'Failed to upload Lab Scores'
            ], 401);
        }
    }


    public function mark_attendance(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:PRESENT,ABSENT',
            'reg_no' => 'required|exists:students',
            'attendance_id' => 'required|exists:attendance_lists,id'
        ], [
            'type.required' => 'No Action was taken',
            'type.in' => 'A student is either PRESENT or ABSENT',
            'reg_no.required' => 'Student Registration Number was not provided',
            'reg_no.exists' => 'Student account was not found',
            'attendance_id' => 'Attedance Profile was not found',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $findUser = StudentsAttendance::where('reg_no', $request->reg_no)
            ->where('attendance_id', $request->attendance_id)
            ->first();

        if ($findUser) {
            $findUser->fill([
                'status' => $request->status
            ])->save();

            
        } else {
            StudentsAttendance::create($validator->validated());
        }

        $attendance_lists = $this->attendance_lists($request);//->where('attendance_id', $request->attendance_id)->first();

        return response()->json([
            'success' => 'Student Attendance updated as ' . $request->status,
            'students' => $attendance_lists
        ]);
    }
}
