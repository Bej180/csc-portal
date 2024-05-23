<?php

namespace App\Http\Controllers;

use \App\Mail\ApprovedResultNotification;
use App\Models\Course;
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


        $pendingResults = Result::query()->where('status', 'PENDING')
            ->with(['student.user', 'updater', 'course']);


        $approvedResults = Result::query()->where('status', 'APPROVED')
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

        // return response()->json(['error'=>$validator->errors(),$request->all()], 400);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $results = Result::where('reference_id', '=', $request->results_id)->with('course');

        $results->update([
            'status' => 'APPROVED'
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
     * Allocates courses
     * 
     * Add courses to the list of courses offered by the staff
     */

    public function allocate_courses(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:staffs',
            'courses' => 'required'
        ], [
            'id.numeric' => 'Invalid Staff Id',
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



        $courseRecord = Course::query();
        $courseRecord->whereIn('id', $request->courses);
        $staff = Staff::find($request->id)->first();


        if ($staff->designation === 'technologists') {
            $courseRecord->where('has_practical', true);
        }

        $courses = $courseRecord->get();

        $newCourses = [];



        foreach ($courses as $course) {

            $new = CourseAllocation::updateOrCreate([
                'staff_id' => $request->id,
                'course_id' => $course->id
            ]);
            if ($new) {
                $newCourses[] = $course->id;
            }
        }

        $staff->courses = CourseAllocation::whereIn('course_id', $newCourses)->with('course')->get();

        return response()->json([
            'success' => 'Course allocation was successfully',
            'staff' => $staff
        ]);
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

        // Deallocate from courses the staff is cordinating
        $cordinatingCourses = Course::whereIn('id', $request->courses)
            ->where('cordinator', $request->id);

        if ($cordinatingCourses->exists()) {
            $cordinatingCourses->fill([
                'cordinator' => null
            ])->save();
        }

        return response()->json([
            'success' => 'Course deallocation was successfully',
            'deallocated' => $deallocating
        ]);
    }


    public function allocatable_courses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'staff_id' => 'required|exists:staff,id',
            'semester' => 'required',
            'level' => 'required',
        ], [
            'staff_id.required' => 'Staff ID must be provided',
            'staff_id.exists' => 'Staff is not available',
            'semester.required' => 'Semester is required',
            'level.required' => 'Level must be provided',
        ]);

        $staff = Staff::find($request->staff_id);

        $designation = $staff->designation;

        $blacklist_courses = ['SIW 200', 'SIW 400', 'CSC 555', 'CSC 556'];

        $courses = Course::query();
        $courses = $courses->where('semester', $request->semester)
            ->whereNotIn('code', $blacklist_courses)
            ->where('level', $request->level);

        if ($designation == 'technologist') {
            $courses->where('has_practical', true);
        }
        $previousAllocations = CourseAllocation::where('staff_id', $request->staff_id)->get()->pluck('course_id');
        $courses->whereNotIn('id', $previousAllocations);

        $courses = $courses->get();
        return ['allocatables' => $courses];
    }
}
