<?php

namespace App\Http\Controllers;

use App\Mail\ClassAdvisorAssignment;
use App\Models\AcademicSet;
use App\Models\ActivityLog;
use App\Models\Dean;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ModeratorController extends Controller
{
    

    public function index()
    {
        $hod = Staff::with('user')->where('is_hod', true)->first()->user->account();
        $dean = Dean::with('user')->latest()->first();

        return compact('hod', 'dean');
    }


    public function addDean(Request $request)
    {

        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'gender' => 'required',
            'staff_id' => 'required|unique:deans',
            'title' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'address' => 'sometimes'
        ], [
            'name.required' => 'Full name required',
            'gender.required' => 'Gender required',
            'staff_id.required' => 'Staff ID is required',
            'title.required' => 'Title of the dean is required',
            'email.required' => 'Email address required',
            'email.email' => 'Email address is not valid',
            'phone.required' => 'Phone number required',
            'staff_id.unique' => 'Account with the same Staff ID already exists',
            'email.unique' => 'Email address already exists',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }
        
        $data = $validator->validated();
        $data['phone'] = preg_replace('/\D/', '', $data['phone']);

        // add title to name
        $name = $data['title'] . ' ' . $data['name'];

        $data['name'] = $name;

        // Make phone number initial the password and has it
        $data['password'] = Hash::make($data['phone']);
        $data['role'] = 'dean';

        $authAccount = User::createUser($data);
        $dean_id = $authAccount->id;


        // Assign id to profile
        $data['id'] = $dean_id;
        $dean = Dean::create($data);


        $newCreatedAccount = Dean::where('id', $dean_id)->with('user')->first();


        if ($newCreatedAccount) {
            // log the activity
            ActivityLog::log($request->user(), 'account_creation', 'created a Dean account for ' . $name);

            // email the staff about the new account creation
            //  Email(new NewDeanAccount($authAccount), $authAccount);

            return response()->json([
                'success' => 'Dean account has been created',
                'dean' => $newCreatedAccount
            ]);
        }

        return response()->json([
            'error' => 'Failed to create Dean accoount',
        ], 401);

    }



    public function makeHOD(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'staff' => 'required|exists:staffs,id',
        ], [
            'staff.required' => 'Staff must be selected',
            'staff.exists' => 'Staff not found',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);

        }
        // Look for the current HOD and change is_hod to false
        $staffs = Staff::where('is_hod', true);
        $ids = $staffs->pluck('id');
        
        if ($ids->contains($request->staff)) {
            return response()->json([
                'error' => $staffs->first()->user->name . ' has already been made HOD',
            ], 400);
        }

        $staffs->update([
            'is_hod' => false,
        ]);
        
        $staff = Staff::find($request->staff);
        


        $fill = $staff->fill([
            'is_hod' => true
        ])->save();

        ActivityLog::log($staff->user, 'made_hod', 'made '.$staff->user->name.' the HOD of CSC');
        $staff->user = $staff->user;

        return response()->json([
            'success' => $staff->user->name . ' has been made the HOD of CSC',
            'hod' => $staff->user->account()
        ]);
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
                'errors' => $validator->errors()
            ], 401);
        }
        $session = $request->session;
        $staff_id = $request->staff_id;
        $staff = Staff::find($staff_id);
        $class = AcademicSet::where('name', '=', $request->session)->with('advisor.user')->first();

        // check if class already exists
        if ($class) {
            if ($class->advisor_id == $staff_id) {
                return response()->json([
                    'error' => $class->advisor->user->name . " is already the class advisor of " . $session,
                ], 400);
            }
            else if ($class->advisor_id && !$request->confirmed) {
                return response()->json([
                    'confirm' => "**{$class->advisor->user->name}** is the class advisor of **$session class**. Continuing will remove {$class->advisor->user->pronoun('him')} and make **{$staff->user->name}** the new class advisor",
                ], 400);
            }

            if ($class->advisor_id) {
                $has_class = AcademicSet::whereNot('id', $class->id)->where('advisor_id', $class->advisor_id)->exists();

                // Reset previous class advisor record

                $class->advisor->update([
                    'is_class_adivsor' => $has_class
                ]);


            }

            return [$staff];
            // update is_class_advisor in Staff record
            $staff->update([
                'is_class_advisor' => true
            ]);

            // update advisor_id in Set record
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


        // Send notification to staff about the new role assigned to him/her
        Email(new ClassAdvisorAssignment($staff->user, $class), $staff->user);


        return response()->json([
            'success' => 'Successfully made '.$staff->user->name.' the Class Advisor of '.$session.' class',
            'data' => $class
        ]);
    }
}
