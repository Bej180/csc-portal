<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Result;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EnrollmentController extends Controller
{

    public function list_of_enrolled_students(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session' => 'required',
            'semester' => 'required',
            'course_id' => 'required|exists:enrollments',
        ], [
            'session.required' => 'Session must be provided',
            'semester.required' => 'Session must be provided',
            'course.required' => 'Course must be provided',
            'course_id.exists' => 'No student registered for this course',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }
        $semester = $request->get('semester');
        $session = $request->get('session');
        $course_id = $request->course_id;

        $result = Result::where('course_id', $course_id)
                    ->where('semester', $semester)
                    ->where('session', $session)
                    ->with('uploader')->first();

        if ($result && !in_array($result->status, ['incomplete', 'ready'])) {
            $uploader = $result->uploader?->name;
            if ($result->uploader->id === auth()->id()) {
                $uploader = 'you';
            }

            return response()->json([
                'error' => 'Result has already been uploaded by ' . $uploader,
            ], 400);
        }


        $enrolledStudents = Enrollment::students($semester, $session, $request->course_id);
       
        if (!count($enrolledStudents)) {
            return response()->json([
            
                'error' => "No student found to have enrolled in the course in $semester semeseter of $session academic session",
            ], 400);
        }

        return $enrolledStudents;
    }
}
