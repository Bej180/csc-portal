<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\Course;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SessionController extends Controller
{
    public function store(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'active_semester' => 'sometimes',
            'name' => [
                'required',
                'regex:/^\d+\/\d+$/',
                'unique:sessions',
                function($attr, $value, $fail) {
                    if (preg_match('/^(\d+)\/(\d+)$/', $value, $match)) {
                        list(, $start_year, $end_year) = array_map(fn($item) => (int) $item, $match);
                        $end_year -= $start_year;
                        if ($end_year !== 1) {
                            $fail('Invalid session name.');
                        }
                    }
                    else {
                        $fail('Invalid session name..');
                    }
                }
            ]
        ], [
            'name.required' => 'Session name is required',
            'name.regex' => 'Session name is not valid',
            'name.unique' => 'Session with the name already exists',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 401);
        }
        $active_semester = $request->active_semester ?? 'HARMATTAN';
        $course_reg_open = $request->course_registration_status ?? 'OPEN';
        $validated = $validator->validated();

        $validated[strtolower($active_semester).'_course_registration_status'] = $course_reg_open;

        if ($session = AcademicSession::create($validated)) {
            $sessions = AcademicSession::latest();
            $active_session = $sessions->first();
            $data = $session;
            $success = 'Session successfully created';


            $user = $request->user();

            if (!$user->activation_token) {
                $user->fill([
                    'activation_token' => generateToken('users.activation_token')
                ])->save();
            }
    
            
            return response()
                ->json(compact('data','success','session','sessions', 'active_session'));

        }

        
        return response()->json([
            'error' => 'Session could not be created'
        ], 401);
    }

    

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'active_semester' => 'required',
            'id' => 'required|exists:sessions'
        ], [
            'active_semester.required' => 'Semester is required',
            'id.required' => 'Session id is required',
            'id.exists' => 'Session doesnt exist'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(), 
            ], 401);
        }

        $session = AcademicSession::find($request->id);
        $session->fill([
            'active_semester' => $request->active_semester
        ])->save();
        
        $message = $this->show();
        $message['success'] = 'Current semester set for '.$request->active_semester;
        return response()->json($message);

    }
    public function update_course_registration_status(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'status' => 'required',
            'id' => 'sometimes|exists:sessions',
            'session'=> [
                function($attribute, $value, $fail) use ($request) {
                    if (!$value && !$request->id) {
                        $fail('Session is required');
                    }

                }
            ],
            'semester' => 'required'
        ], [
            'status.required' => 'Course Registration status not provided',
            'id.required' => 'Session id is required',
            'id.exists' => 'Session doesnt exist',
            'semester.required' => 'Semester is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(), 
            ], 401);
        }
        $semester = strtolower($request->semester);
        $status = $request->status;

        $session = AcademicSession::query();
        
        if ($id = $request->id) {
            $session->where('id', $id);
        }
        
        if ($name = $request->session) {
            $session->where('name', $name);
        }
        $session = $session->first();
        
        if (!$session) {
            return response()->json([
                'error' => 'Academic Session record not found',
            ], 400);
        }
        // return $session;
        // return ["{$semester}_course_registration_status" => $status];
        
        $session->fill([
            "{$semester}_course_registration_status" => $status,
        ])->save();
        $session->semester = $semester;
        $session->session = $session->name;

        return response()->json([
            'semester' => $session,
            'success' => "{$request->semester} course registration status set $status"
        ]);
        
    }

    public function reopen_registration(Request $request) {
        $validator = Validator::make($request->all(), [
            'session' => 'required',
            'semester' => 'required',
        ],[
            'session.required' =>'Session must be provided',
            'semester.required' =>'Semester is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $session = AcademicSession::where('name', '=', $request->session)->first();
        
        if (!$session) {
            return response()->json([
                'error' => 'Academic Session not found',
            ], 400);
        }

        $semester_status = strtolower($request->semester).'_course_registration_status';

        $session->fill([
            $semester_status => 'OPEN'
        ])->save();

        $open_semesters = AcademicSession::semestersOpenForCourseRegistration();

        return response()->json([
            'success' => $request->semester.' Course Registration OPENED',
            'open_semesters' => $open_semesters
        ]);

    }


   


}
