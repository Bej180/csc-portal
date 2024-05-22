<?php

namespace App\Http\Controllers;

use \App\Mail\ApprovedResultNotification;
use App\Models\CourseAllocation;
use \App\Models\Result;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HODController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:staff,is_hod:1');
    }

    public function index_staff()
    {
        return view('pages.hod.staff-management.staffs');
    }



    public function index_results()
    {

        $pendingResults = Result::where('status', 'PENDING')->groupBy('reference_id')->get();
        $approvedResults = Result::where('status', 'APPROVED')->groupBy('reference_id')->get();

        return view('pages.hod.result-management.results', compact('approvedResults', 'pendingResults'));
    }





    public function index_courses()
    {
        return view('pages.hod.course-management.courses');
    }


    public function api_index_results(Request $request)
    {


        $pendingResults = Result::query()->where('status', 'complete')
            ->with(['student.user', 'updater', 'course']);


        $approvedResults = Result::query('status', 'approved')
            ->with(['student.user', 'updater', 'course']);

        if ($request->sort && is_array($request->sort)) {
            $approvedResults->orderBy(...$request->sort);
            $pendingResults->orderBy(...$request->sort);
        }



        $pendingResults = $pendingResults->get()->groupBy('reference_id');
        $approvedResults = $approvedResults->get()->filter(function ($i) use ($request) {
            if ($request->search) {
                $search = strtolower(preg_replace('~\W+~', '.', $request->search));
                return match (true) {
                    !!preg_match("/$search/", strtolower($i->updater->name)) => true,
                    !!preg_match("/$search/", strtolower($i->updater->email)) => true,
                    !!preg_match("/$search/", strtolower($i->updater->phone)) => true,
                    !!preg_match("/$search/", strtolower($i->semester)) => true,
                    !!preg_match("/$search/", strtolower($i->level)) => true,
                    !!preg_match("/$search/", strtolower($i->session)) => true,
                    !!preg_match("/$search/", strtolower($i->status)) => true,
                    !!preg_match("/$search/", strtolower($i->remark)) => true,
                    !!preg_match("/$search/", strtolower($i->course->name)) => true,
                    !!preg_match("/$search/", strtolower($i->course->code)) => true,
                    default => false,
                };
            }
            return true;
        })->groupBy('reference_id');
        // $approvedResults = $approvedResults->flatten(1);

        return compact('approvedResults', 'pendingResults');
    }


    // public function get_result(Request $request) {
    //     $validator = Validator::make($request->all(), [
    //         'reference_id' => 'required|exists:results',
    //     ], [
    //         'reference_id.required' => 'Results id must be provided',
    //         'reference_id.exists' => 'Results not found',
    //     ]);
    //     if ($validator->fails()){
    //         return response()->json([
    //             'errors' => $validator->errors(),
    //         ], 400);
    //     }


    //     $result = Result::where('reference_id', '=', $request->reference_id)
    // }



    public function approve_results(Request $request)
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
            'status' => 'approved'
        ]);

        $firstResult = $results->first();
        $date = Carbon::parse($firstResult->created_at);

        $getResults = $results->get();

        // re-calculate the cpga of the results uploaded
        foreach ($getResults as $result) {
            $result->updateCGPA();
        }


        //Email(ApprovedResultNotification($uploader, $firstResult->course->code, $date->format('d/m/Y')), $uploader);
        // return $results;

        return response()->json([
            'success' => $results->first()->course->code . ' results have been approved successfully',
            'data' => $this->api_index_results($request)
        ]);
    }



    /**
     * Fetch multiple staff members
     */
    public function get_staffs(Request $request)
    {
        $auth = $request->user();

        $staffs = Staff::query()->whereNot('staffs.id', $auth->id)
            //->join('users', 'users.id', '=', 'staffs.id')
            ->with(['user', 'courses.course']);





        $staffs = $staffs
            ->latest()
            ->paginate(10)
            ->map(function ($staff) {
                $staff->classes = $staff->classes;
                return $staff;
            })
            ->filter(function ($staff) use ($request) {
                $search = $request->search;

                if ($search) {
                    $search = preg_replace('/\s+/', '%', $request->search);

                    return Staff::where('staffs.id', $staff->id)
                        ->join('users', 'users.id', '=', 'staffs.id')
                        ->where(function ($query) use ($search) {
                            $query->where('users.name', 'LIKE', "%$search%")
                                ->orWhere('designation', 'LIKE', "%$search%")
                                ->orWhere('staff_id', 'LIKE', "%$search%")
                                ->orWhere('users.phone', 'LIKE', "%$search%")
                                ->orWhere('users.email', 'LIKE', "%$search%");
                        })->exists();
                }
                return true;
            });

        return $staffs;
    }

    /**
     * Fetch single staff member information
     */

    public function get_staff(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:staffs'
        ], [
            'id.required' => 'Staff ID must be provided',
            'id.numeric' => 'Invalid staff ID',
            'id.exists' => 'Staff account unavailable',
        ]);

        if ($validator->fails()) {

            return error_helper($validator->errors());

        }

        $auth = $request->user();


        $staffs = Staff::query()->where('id', $request->id)
            //->join('users', 'users.id', '=', 'staffs.id')
            ->with(['user', 'courses.course']);





        $staffs = $staffs
            ->latest()
            ->paginate(10)
            ->map(function ($staff) {
                $staff->classes = $staff->classes;
                return $staff;
            })
            ->filter(function ($staff) use ($request) {
                $search = $request->search;

                if ($search) {
                    $search = preg_replace('/\s+/', '%', $request->search);

                    return Staff::where('staffs.id', $staff->id)
                        ->join('users', 'users.id', '=', 'staffs.id')
                        ->where(function ($query) use ($search) {
                            $query->where('users.name', 'LIKE', "%$search%")
                                ->orWhere('designation', 'LIKE', "%$search%")
                                ->orWhere('staff_id', 'LIKE', "%$search%")
                                ->orWhere('users.phone', 'LIKE', "%$search%")
                                ->orWhere('users.email', 'LIKE', "%$search%");
                        })->exists();
                }
                return true;
            });

        return $staffs;
    }






    /**
     * Deallocates courses
     * 
     * Removes courses from the list of courses offered by the staff
     */

    public function deallocate_courses(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:staffs',
            'courses' => 'required|array'
        ], [
            'id.required' => 'Staff ID must be provided',
            'id.exists' => 'Staff Account is unavailable at the moment',
            'courses.required' => 'Courses to deallocate must be provided',
            'courses.array' => 'Course to be deallocated is missing',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $courses = CourseAllocation::whereIn('course_id', $request->courses)
            ->where('staff_id', $request->id);
        $deallocating = $courses->get();
        $courses->delete();

        return response()->json([
            'success' => 'Course deallocation was successfully',
            'deallocated' => $deallocating
        ]);
    }
}
