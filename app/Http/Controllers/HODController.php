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



   





    
}
