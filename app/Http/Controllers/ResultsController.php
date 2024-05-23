<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ResultsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Result;
use App\Http\Controllers\Controller;
use App\Mail\LabScoreAddeNotification;
use App\Models\AcademicSession;
use App\Models\AcademicSet;
use App\Models\Admin;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ResultsController extends Controller
{

    public function uploadExcel(Request $request)
    {
        $matcher = [
            'Reg No.' => 'reg_no',
            'LAB' => 'lab',
            'TEST' => 'test',
            'EXAM' => 'exam',
            'TOTAL' => 'score'
        ];

        $request->validate([
            "level" => "required",
            "semester" => "required",
            "course" => "required",
            "session" => "required"
        ]);
        $level = $request->level;
        $semester = $request->semester;
        $course = $request->course;
        $session = $request->session;

        if ($processed = $this->processResults('result')) {

            Result::insert($processed);

            return redirect()->back()->with('success', 'Successfully uploaded results');
        }


        return redirect()->back()->with('error', 'Failed to process results');
    }

    public function processResults($name): ?array
    {
        // the keys are the matcher, they are the names of the column
        // the values are the column name of the table to store the results 

        $required_columns = [
            //key  => value
            'regno' => 'reg_no',
            'lab' => 'lab',
            'test' => 'test',
            'exam' => 'exam',
            'total' => 'score'
        ];


        $request   =    request();
        $file      =    $request->file($name);
        $level     =    $request->level;
        $semester  =    $request->semester;
        $course    =    $request->course;
        $session   =    $request->session;


        // Convert file to Array
        $data = Excel::toArray([], $file);

        $rowMatched = [];

        // clean up the column data, by removing non-alphanumeric and underscore characters
        // and converting the column to lower case
        $cleanValue = fn ($value) => strtolower(preg_replace('/[^a-zA-Z0-9_]+/', '', $value));

        $foundRow = false;
        $retrieveColumns = [];



        foreach ($data[0] as $rowNumber => $row) {

            foreach ($row as $col) {
                // clean up the column data, by removing non-alphanumeric and underscore characters
                // and converting the column to lower case
                $resultColumn = $cleanValue($col);


                if (array_key_exists($cleanValue($col), $required_columns)) {
                    // if found, that means the row is the header,
                    // which contains other names, like results, remarks and others

                    foreach ($row as $n => $column) {
                        $cleanColumn = $cleanValue($column);
                        if (!array_key_exists($cleanColumn, $required_columns)) {
                            continue;
                        }

                        $retrieveColumns[$n] = $required_columns[$cleanColumn];
                    }
                    $foundRow = $rowNumber;
                    break;
                }
            }
        }


        if ($foundRow === false) {
            return null;
        }


        $results = array_splice($data[0], $foundRow + 2);


        $newResult = [];
        foreach ($results as $result) {
            $append = [
                'level'      =>  $level,
                'semester'   =>  $semester,
                'course_id'  =>  $course,
                'session'    =>  $session
            ];

            foreach ($retrieveColumns as $index => $retrieved) {
                $append[$retrieved] = $result[$index];
            }
            $newResult[] = $append;
        }

        return $newResult;
    }




    public function uploadExcelx(Request $request)
    {
        $matcher = [
            'Reg No.' => 'reg_no',
            'LAB' => 'lab',
            'TEST' => 'test',
            'EXAM' => 'exam',
            'TOTAL' => 'score'
        ];

        $request->validate([
            "level" => "required",
            "semester" => "required",
            "course" => "required",
            "session" => "required"
        ]);
        $level = $request->level;
        $semester = $request->semester;
        $course = $request->course;
        $session = $request->session;


        $file = $request->file('result');


        $data = Excel::toArray([], $file);


        // Store the data in the database
        // For example:
        $n = 0;


        $results = [];

        $foundRow = false;
        $retrieveColumns = [];

        foreach ($data[0] as $rowNumber => $row) {


            foreach ($row as $col) {
                if (array_key_exists($col, $matcher)) {
                    dd($col);
                    foreach ($row as $n => $column) {
                        if (!array_key_exists($column, $matcher)) {
                            continue;
                        }
                        $retrieveColumns[$n] = $matcher[$column];
                    }
                    $foundRow = $rowNumber;
                    break;
                }
            }
        }

        if ($foundRow === false) {
            return redirect()->back()->with('error', 'Failed to scan results');
        }


        $results = array_splice($data[0], $foundRow + 2);


        $newResult = [];


        foreach ($results as $result) {
            $ResultDB = new Result();
            foreach ($retrieveColumns as $index => $retrieved) {
                $newResult[$retrieved] = $result[$index];
                $ResultDB->{$retrieved} = $result[$index];
                $ResultDB->level = $level;
                $ResultDB->semester = $semester;
                $ResultDB->course_id = $course;
                $ResultDB->session = $session;
            }
            // Store into the database table Results 
            $ResultDB->save();
        }



        return redirect()->back()->with('success', count($results) . ' results uploaded and processed successfully');
    }

    /**
     * This method routes the page 
     * for staff to add results manually
     */

    public function staff_add_result(Request $request)
    {
        $session = request()->session;
        $semester = request()->semester;
        $course_id = request()->course;
        $staff = auth()->user()->staff;
        $courses = $staff->courses;

        $enrolledStudents = Enrollment::students($semester, $session, $course_id);
        return view('pages.staff.result-management.add-results', compact('enrolledStudents', 'courses', 'staff', 'course_id', 'session', 'semester'));
    }

    



    


    public function approveResults(Request $request)
    {
        dd($request);
    }


    public function spreadsheet(Request $request)
    {
        return view('pages.staff.result-management.upload-result');
    }




    /**
     * SHow's form to insert results into the database table
     */

    public function insert()
    {
        return view('pages.staff.upload-form');
        //fn()=> view('pages.staff.upload-results')
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new ResultsImport, $request->file('file'));

        return redirect()->back()->with('success', 'Results imported successfully.');
    }

    

    
    public function index(Request $request)
    {
        $course = $request->get('course');
        $session = $request->get('session');
        $semester = $request->get('semester');
        $class_id = $request->get('class_id');

        $active_user = auth()->user();
        $staff = $active_user->staff;
        if (!$staff) {
            return redirect('/home')->with('error', 'You do not have permission to access this page');
        }
        $classes = $staff->classes;

        $route = 'course-result';


        if ($course === 'all') {
            $route = 'all-semester-courses-result';
        }

        return view("pages.staff.results", compact('course', 'semester', 'session', 'classes', 'route', 'staff'));
    }

    public function api_getAwaitingResults(Request $request)
    {
        $request->validate([
            'level' => 'required'
        ]);
        $level = $request->get('level');

        return Result::getLevelAwaitingResults($level);
    }






    public function awaitingResults()
    {
        $awaitingResults = Result::awaitingResults()->get();





        $results = [];

        if ($awaitingResults && count($awaitingResults) > 0) {
            foreach ($awaitingResults as $n => $result) {

                $level = $result['level'];
                $code = $result['code'];

                $results[$level] ??= [];
                $results[$level][$code] ??= [];

                //  dd($result);
                // $results = Arr::prepend($results, "$level.$code", $result);


                // $results[$level][$code][count($results[$level][$code])-1] = $result;
            }
        }
        //dd($results);
        return view('pages.admin.awaiting-results', compact('results'));
    }


    

    /**
     * Show student results to
     * Admin and course staff
     */

    public function index_moderators(Request $request)
    {
        $course = $request->get('course', '');
        $session = $request->get('session', '');
        $semester = $request->get('semester', '');
        $class_id = $request->get('class_id', '');

        $active_user = auth()->user();

        $class = null;
        $students = [];
        if ($active_user->role === 'admin') {
            if ($class_id) {
                $class = AcademicSet::find($class_id);
            }
            $classes = Admin::academicSets();
        } else {
            $staff = $active_user->staff;
            $class = $staff->class;
            $students = $class->students;
            $classes = $staff->classes;
        }

        return view("pages.admin.results", compact('session', 'classes', 'students', 'semester'));
    }


    /**
     * Show form for staff to insert student's results
     */

    public function insert_result()
    {
        return view('results.add-results');
    }








    public function add_single_student_results(Request $request)
    {

        $formField = $request->validate([
            // required fields
            'course_id' => 'required',
            'reg_no' => 'required',
            'semester' => 'required',
            'session' => 'required',
            'level' => 'required',

            // for now these fields are optional         
            'grade' => 'sometimes',
            'exam' => 'sometimes',
            'test' => 'sometimes',
            'grade' => 'sometimes',
            'lab' => 'sometimes',
            'remark' => 'sometimes',
            'score' => 'sometimes',
        ]);
        $semester = $request->semester;
        $level = $request->level;
        $session = $request->session;
        $reg_no = $request->reg_no;
        $course_id = $request->course_id;


        $course = Enrollment::where('course_id', $course_id)
            ->where('semester', $semester)
            ->where('session', $session)
            ->where('reg_no', $reg_no)
            ->where('level', $level)
            ->get()
            ->first();

        $result = Result::where('course_id', $course_id)
            ->where('semester', $semester)
            ->where('session', $session)
            ->where('reg_no', $reg_no)
            ->get()
            ->first();


        if (!auth()->check()) {

            return response([
                'error' => 'You need to login to be able to access this page.'
            ], 400);
        }


        if (auth()->user()->role !== 'admin' && $result && $result->status !== 'APPROVED') {

            return response([
                'error' => 'Result cannot be updated, contact admin for help'
            ], 400);
        }

        if (!$course) {

            return response([
                'error' => 'Course not found'
            ], 404);
        }


        // If result alread exist, update
        if ($result) {
            $result->updated_by = auth()->id();
            $save = $result->update($formField);
        } else {
            $formField['uploaded_by'] = auth()->id();
            $save = Result::create($formField);
        }

        $result->updateCGPA();

        return compact('save');


        if ($save) {
            return response([
                'success' => 'Result saved successfully',
                'data' => $formField
            ]);;
        }

        return response([
            'error' => 'Failed to update result',
        ], 400);
    }


    




    public function staff_results_index_page(Request $request) {
        $user = $request->user();
        $courses = $user->staff->courses->pluck('course_id');
        
        $results = Result::where('uploaded_by', $user->id)
                    ->orWhere(function($query) use ($user, $courses) {
                        $query->whereIn('course_id', $courses)
                            ->whereNot('uploaded_by', $user->id)
                            ->whereNotIn('status', ['incomplete', 'ready']);
                    })
        
                    ->with('course')
                    ->groupBy(['reference_id'])
                    ->latest()
                    ->get();
        
        return $results;
    }



    
    

    


    


    public function single_course_results(Request $request) {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'semester' => 'required',
            'session' => 'required',
        ], [
            'course_id.required' => 'Course ID required',
            'course_id.exists' => 'Course does not exist',
            'semester.required' => 'Semester of results to be returned is required',
            'session.required' => 'Session of results to be returned is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $results = Result::where('course_id', $request->course_id)
            ->where('semester', $request->semester)
            ->where('session', $request->session)
            ->with(['student.user', 'course'])
            ->get();

        return $results;
    }


    public function staff_lab_score_results() {
        return view('pages.staff.result-management.lab-score-index');
    }





    public function show(Student $student)
    {
        // Define the current semester and session here, replace with your logic
        $currentSemester = 'HARMATTAN';
        $currentSession = '2023/2024';

        // Fetch all results for the student
        $results = $student->results()->with('course')->get();

        // Calculate the GPA data for the student
        $gpaData = Result::studentGPA($student->reg_no, $currentSemester, $currentSession);

        // Calculate cumulative GPA
        $cumulativeTGP = $gpaData['current']['TGP'] + $gpaData['previous']['TGP'];
        $cumulativeTNU = $gpaData['current']['TNU'] + $gpaData['previous']['TNU'];
        $cumulativeGPA = $cumulativeTNU ? $cumulativeTGP / $cumulativeTNU : 0;

        // Prepare data for the view
        $studentResults = [
            'reg_no' => $student->reg_no,
            'scores' => $results->pluck('score'),
            'current' => $gpaData['current'],
            'previous' => $gpaData['previous'],
            'cumulative' => ['TGP' => $cumulativeTGP, 'TNU' => $cumulativeTNU, 'GPA' => round($cumulativeGPA, 2)]
        ];

        return view('students.show', compact('studentResults'));
    }



    public function showAll()
    {
        // Define the current semester and session here, replace with your logic
        $currentSemester = 'HARMATTAN';
        $currentSession = '2023/2024';

        // Fetch all students
        $students = Student::with('results.course')->get();

        // Prepare data for all students
        $studentResults = $students->map(function($student) use ($currentSemester, $currentSession) {
            // Calculate the GPA data for the student
            $gpaData = Result::studentGPA($student->reg_no, $currentSemester, $currentSession);

            // Calculate cumulative GPA
            $cumulativeTGP = $gpaData['current']['TGP'] + $gpaData['previous']['TGP'];
            $cumulativeTNU = $gpaData['current']['TNU'] + $gpaData['previous']['TNU'];
            $cumulativeGPA = $cumulativeTNU ? $cumulativeTGP / $cumulativeTNU : 0;

            return [
                'reg_no' => $student->reg_no,
                'scores' => $student->results->pluck('score'),
                'current' => $gpaData['current'],
                'previous' => $gpaData['previous'],
                'cumulative' => [
                    'TGP' => $cumulativeTGP,
                    'TNU' => $cumulativeTNU,
                    'GPA' => round($cumulativeGPA, 2)
                ]
            ];
        });

        return view('pages.admin.result-management.all-results', compact('studentResults'));
    }
}


