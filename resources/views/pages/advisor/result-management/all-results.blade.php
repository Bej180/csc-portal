@php
    use App\Models\Result;
    $session = request()->get('session');
    $semester = request()->get('semester');

    $courses = \App\Models\Enrollment::where('enrollments.semester', $semester)
        ->join('courses', 'courses.id', '=', 'enrollments.course_id')
        ->where('enrollments.session', $session)
        ->where('enrollments.semester', $semester)
        ->groupBy('courses.code')
        ->orderBy('courses.id')
        ->get(['courses.code', 'courses.units',  'courses.has_practical',  'courses.id']);

    $students = \App\Models\Enrollment::where('enrollments.semester', $semester)
        ->where('enrollments.session', $session)
        ->join('students', 'students.reg_no', '=', 'enrollments.reg_no')
        ->join('users', 'users.id', '=', 'students.id')
        ->groupBy('enrollments.reg_no')
        ->get(['enrollments.course_id', 'enrollments.reg_no', 'students.cgpa']);

    $score_array = [];

    foreach ($courses as $course) {
        $score_array[$course->id] = '';
    }

    $student_results = [];

    foreach ($students as $student) {
        $records = [
            'score' => $score_array,
            'reg_no' => $student->reg_no,
            'cgpa' => $student->cgpa,
        ];

        $results = \App\Models\Enrollment::where('enrollments.semester', $semester)
            ->join('results', function ($join) {
                $join
                    ->on('enrollments.course_id', '=', 'results.course_id')
                    ->on('enrollments.reg_no', '=', 'results.reg_no')
                    ->on('enrollments.semester', '=', 'results.semester')
                    ->on('enrollments.session', '=', 'results.session');
            })
            ->where('enrollments.session', $session)
            ->where('enrollments.reg_no', $student->reg_no)
            ->orderBy('enrollments.course_id')
            ->get([
                'results.reg_no',
                'results.score',
                'results.lab',
                'results.exam',
                'results.test',
                'results.course_id',
            ]);

        foreach ($results as $result) {
            $records['score'][$result->course_id] = $result->score;
        }
        $student_results[] = $records;
    }

    $results = $class
        ->students()
        ->join('results', 'results.reg_no', '=', 'students.reg_no')
        ->join('courses', 'courses.id', '=', 'results.course_id')
        ->join('enrollments', 'enrollments.course_id', '=', 'results.course_id')
        ->where('enrollments.session', '=', $session)
        ->where('enrollments.semester', '=', $semester)
        ->orderBy('courses.id')
        ->get([
            'courses.code',
            'results.course_id',
            'results.score',
            'results.lab',
            'results.exam',
            'results.test',
            'students.reg_no',
        ]);

@endphp



<div id="advisor-results-container-cgpa-summary" class="cd mt-5">
    <table id="all-sessions" class="cd-b responsive-table  text-xs">
        <thead style="text-align: center;">
            <tr>
                <th class="w-5">S/N</th>
                <th>Reg. No.</th>
                
                <span>
                    @foreach ($courses as $course)
                        <th>{{ $course->code }}</th>
                    @endforeach

                </span>

                <th colspan="3">Current</th>
                <th colspan="3">Previous</th>
                <th colspan="3">Cummulative</th>
                <th>Remark</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <!-- Course Units -->
                <span>
                    @foreach ($courses as $course)
                        <th class="!text-center">{{ $course->units }}</th>
                    @endforeach

                </span>
                <th>TGP</th>
                <th>TNU</th>
                <th>GPA</th>

                <th>TGP</th>
                <th>TNU</th>
                <th>GPA</th>

                <th>TGP</th>
                <th>TNU</th>
                <th>GPA</th>

                <th></th>
            </tr>
        </thead>
        <tbody style="text-align: center;">
            @foreach ($student_results as $student)
                <tr>
                    <td class="!text-center">{{ $loop->index + 1 }}</td>
                    <td>{{ $student['reg_no'] }}</td>
                    <span>
                        @foreach ($student['score'] as $score)
                            <td class="!text-center">{{ $score }}</td>
                        @endforeach
                    </span>
                    @php
                        $gpa = Result::studentGPA($student['reg_no'], $semester, $session);
                        $previewsGPA = Result::studentPreviousSemesterGPA($student['reg_no'], $semester, $session);

                        $cgpa = $student['cgpa'];

                    @endphp

                    <td class="border-l-2">{{ $gpa['TGP'] }}</td>
                    <td>{{ $gpa['TNU'] }}</td>
                    <td>{{ $gpa['GPA'] }}</td>

                    <td class="border-l-2">{{ $previewsGPA['TGP'] }}</td>
                    <td>{{ $previewsGPA['TNU'] }}</td>
                    <td>{{ $previewsGPA['GPA'] }}</td>

                    <td class="border-l-2"></td>
                    <td></td>
                    <td>{{ $cgpa }}</td>

                    <td class="border-l-2">PASS</td>
                </tr>
            @endforeach


        </tbody>
    </table>
</div>
