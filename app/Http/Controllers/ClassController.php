<?php

namespace App\Http\Controllers;

use App\Models\AcademicSet;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ClassController extends Controller
{

    const TOKEN_LENGTH = 20;

    public function destroy()
    {
    }
    public function index()
    {
    }
    public function update()
    {
    }

    public function api_index(Request $request)
    {
        return AcademicSet::all();
    }



    public function api_fetchClass(Request $request)
    {

        $class = AcademicSet::where('name', '=', $request->get('class_name'))
            ->orWhere('id', '=', $request->get('class_id'))
            ->orWhere('start_year', '=', $request->get('start_year'))
            ->orWhere('end_year', '=', $request->get('end_year'))
            ->with(['user.user', 'students.user']);
            
        if (!$class->exists()) {
            return response()->json(['message' => 'Class not found'], 400);
        }
        return $class->first();
    }

    






    public function generateClassName() {

        // a year before 
        $start_year = date('Y') - 2;

        $avaliable_years = [];
        
        while(count($avaliable_years) < 5) {

            $check = AcademicSet::where('start_year', $start_year)->exists();
            if (!$check) {
                $end_year = $start_year + 5;
                $avaliable_years[] = "$start_year/$end_year";
            }

            $start_year++;
        }

        // Cache::put($avaliable_years, true, 60); 


        return $avaliable_years;




    }





    public function store(Request $request)
    {
        $formFields = $request->validate([
            'start_year' => 'required|regex:/^[0-9]+$/',
            'end_year' => 'required|regex:/^[0-9]+$/',
            'department' => 'required',
            'staff_id' => 'sometimes|regex:/^[0-9]+$/'
        ], [
            'start_year.regex' => 'Invalid start year',
            'end_year.regex' => 'Invalid end year',
            'staff_id.regex' => 'Invalid Advisor Id'
        ]);

        $formFields['name'] = "{$formFields['department']} {$formFields['end_year']}";

        $set = AcademicSet::create($formFields);

        return redirect()->route('view.academic_set', compact('set'));
    }



    public function apiGenerateInviteLink(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'class_id' => 'required|exists:sets,id',
        ], [
            'class_id.required' => 'Class id is required',
            'class_id.exists' => 'Class does not exist',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(), 'errors' => $validator->errors()
            ], 401);
        }

        $class = AcademicSet::find($request->class_id);


        do {
            $token = Str::random(self::TOKEN_LENGTH);
        } while (AcademicSet::getSetFromToken($token));

        $class->update(compact('token'));

        return response()->json([
            'link' => url('/register?invite='.$token)
        ]);
    }

    public function apiWithdrawInvite(Request $request) {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required|exists:sets,id',
        ], [
            'class_id.required' => 'Class id is required',
            'class_id.exists' => 'Class does not exist',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(), 'errors' => $validator->errors()
            ], 401);
        }

        $class = AcademicSet::find($request->class_id);


        $withdrawn = $class->update([
            'token' => null
        ]);

        if ($withdrawn) {
            return response()->json(['message' => 'Invitation link withdrawn. Students cant access it anymore']);
        }
        return response()->json(['message' => 'Failed to withdraw invitation link']);

    }


    public function getInviteLink(Request $request) {

    }













    public function create(Request $request) {
        // return $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:sets|regex:/^\d+\/\d+$/',
            'staff_id' => 'nullable|numeric|exists:users,id',
        ], [
            'name.required' => 'Session is required',
            'name.unique' => 'Session has already been created',
            'name.regex' => 'Invalid session',
            'staff_id.numeric' => 'Invalid staff id',
            'staff_id.exists' => 'Instructor does not exist',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' =>$validator->errors()->first(), 
                'errors' =>$validator->errors()
            ], 401);
        }
        preg_match('/^(\d+)\/(\d+)$/', $request->name, $match_session);

            list(, $start_year, $end_year) = array_map(fn($data) => (int) $data, $match_session);
            
            $year_diff = $end_year - $start_year;

            $formFields = array_merge($validator->validated(), [
                'end_year' => $end_year,
                'start_year' => $start_year,
            ]);
            
            
            if ($year_diff !== 5) {
                return response()->json([
                    'error' => 'Invalid session. Year must be 5 intervals like 2018/2023'
                ], 401);
            }

            $addClass = AcademicSet::create($formFields);

            
            if (!$addClass) {

                return response()->json([
                    'error' => 'Failed to create a new class'
                ], 401);

            }

            if ($addClass->advisor_id) {
                $advisor = Staff::find($addClass->advisor_id);
                $advisor->fill([
                    'is_class_advisor' => true,
                ])->save();
            }
           
            return response()->json(array_merge([
                'success' => 'Class added successfully', 
                'data' => $addClass
            ], $this->classes()));

        
        
      
    }


    public function classes() {
        $classes = AcademicSet::with(['students.user', 'advisor.user'])->latest()->get();

        return compact('classes');

    }


    public function add_advisor(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:sets',
            'staff_id' => 'required|exists:staffs,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }
        $staff_id = $request->staff_id;
        $id = $request->id;

        $academicClass = AcademicSet::find($id);
        $staff = Staff::find($staff_id);

        $academicClass->fill([
            'advisor_id' => $staff_id
        ])->save();

        $staff->fill([
            'is_class_advisor' => true
        ]);

        $class = AcademicSet::with('advisor')->first();

        
        return response()->json([
            'success' => $staff->user->name.' has been made the Class Advisor of '.$academicClass->name,
            'class' => $class
        ]);
    }
}
