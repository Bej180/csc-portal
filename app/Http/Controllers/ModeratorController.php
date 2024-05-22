<?php

namespace App\Http\Controllers;

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
        $hod = Staff::with('user')->where('is_hod', true)->first();
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
            'hod' => $staff
        ]);
    }
}
