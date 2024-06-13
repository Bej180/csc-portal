<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CourseRegistrationStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseRegistrationStatusController extends Controller
{
    public function validator(Request $request) 
    {

        return Validator::make($request->all(), [
            'session' => 'required|session',
            'semester' => 'required|in:HARMATTAN,RAIN',
        ], [
            'session.required' => 'Session is reuqired',
            'session.session' => 'Invalid session',
            'semester.required' => 'Semester is required',
            'semester.in' => 'semester must be either HARMATTAN or RAIN'
            
        ]);
    }
    
    public function is_open(Request $request) {
        $validator = $this->validator($request);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $session = $request->session;
        $semester = $request->semester;

        if (CourseRegistrationStatus::isOpen($session, $semester)) {
            return response()->json([
                'message' => "$session $semester semester is open for course registration"
            ]);
        }

        return response()->json([
            'error' => "$session $semester semester is not open for course registration"
        ], 400);

       

    }






    public function close(Request $request) {
         $validator = $this->validator($request);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }


        $record = CourseRegistrationStatus::where('session', $request->session)
            ->where('semester', $request->semester)
            ->first();
        
        if (!$record || $record->close_on->isPast()) {
            if ($record) {
                $record->delete();
            }
            return response()->json([
                'error' => "{$request->session} {$request->semester} semester course registration is already closed"
            ], 400);
        }

        $record->delete();

        return response()->json([
            'success' => "{$request->session} {$request->semester} semester course registration is has been CLOSED successfully"
        ]);


    }




    public function open(Request $request) {
        $validator = $this->validator($request);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        
        $session = $request->session;
        $semester = $request->semester;
        $weeks = $request->weeks;

        CourseRegistrationStatus::extendWeek($session, $semester, $weeks);

        return response()->json([
            'success' => "$session $semester semester's course registration has been reopened",
        ]);
        
    }
}
