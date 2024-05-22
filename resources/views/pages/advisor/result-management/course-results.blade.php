@php
    $advisor = \App\Models\Advisor::active();
    $authUser = auth()->user();
    $semester = request()->semester;
    $session = request()->session;

    $course_id = request()->get('course');

    $students = $class->students;

    $records = $class
        ->students()
        ->leftJoin('users', 'users.id', '=', 'students.id')
        ->leftJoin('enrollments', 'enrollments.reg_no', '=', 'students.reg_no')
        ->leftJoin('courses', function ($join) use ($semester, $session, $course_id) {
            $join
                ->on('courses.id', '=', 'enrollments.course_id')
                ->where('enrollments.semester', $semester) //->where('enrollments.course_id', $course_id)
                ->where('enrollments.session', $session);
        })

        ->leftJoin('results', function ($join) use ($semester, $session, $course_id) {
            $join->on('results.reg_no', '=', 'students.reg_no')->where('enrollments.semester', $semester)->where('enrollments.course_id', $course_id)->where('enrollments.session', $session);
        })

        ->where('enrollments.semester', $semester)
        ->where('enrollments.session', $session)
        ->where('enrollments.course_id', $course_id)
        ->get(['users.name', 'enrollments.reg_no', 'enrollments.course_id', 'code'])
        ->unique('reg_no');

    //dd($records->get());

    $records = \App\Models\Enrollment::join('courses', 'courses.id', '=', 'enrollments.course_id')
    
        ->leftJoin('results', function ($join) {
            $join->on('results.reg_no', '=', 'enrollments.reg_no')
                ->on('results.course_id', '=', 'courses.id')
                ->on('results.semester', '=', 'enrollments.semester')
                ->on('results.session', '=', 'enrollments.session')
                ->on('results.level', '=', 'enrollments.level');
        })
        ->join('students', 'students.reg_no', 'enrollments.reg_no')
        ->join('users', 'users.id', 'students.id')
        ->where('enrollments.semester', $semester)
        ->where('enrollments.session', $session)
        ->where('enrollments.course_id', $course_id);
        $records = $records->groupBy('students.reg_no')->get();
//dd($records);
@endphp


<div class="grid place-content-center grid-cols-1 mt-2">
    @if (count($records) > 0)
        <div class="">
            <table class="visible-on-print print:text-black responsive-table whitespace-nowrap w-full lg:!w-[300px]">
                <thead class="print:bg-white print:text-black">
                    <tr>
                        <th class="w-10">S/N</th>
                        <th class="text-left">Name</th>
                        <th>Reg. No.</th>
                        <th class="w-10">Program</th>
                        <th class="w-10">Test</th>
                        <th class="w-10">Lab</th>
                        <th class="w-10">Exam</th>
                        <th class="w-10">Total</th>
                        <th class="w-10">Grade</th>
                        <th class="w-10">Remark</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="w-10"></th>
                        <th></th>
                        <th></th>
                        <th class="w-10"></th>
                        <th class="w-10">20</th>
                        <th class="w-10">20</th>
                        <th class="w-10">60</th>
                        <th class="w-10">100</th>
                        <th class="w-10"></th>
                        <th class="w-10"></th>
                    </tr>

                    @php $n = 1;@endphp
                    @foreach ($records as $record)
                        @php

                            $result = \App\Models\Enrollment::result($record->reg_no, $record->course_id, $semester, $session);

                            $gradings = $result?->getGrading();
                        @endphp

                        <tr>

                            <td>{{ $n }}</td>
                            <td class="!text-left">{{ $record->name }}</td>
                            <td>{{ $record->reg_no }}</td>
                            <td>{{ explode(' ', $record->code)[0] }}</td>
                            <td>{{ $result ? $result['test'] : '' }}</td>
                            <td>{{ $result ? $result['lab'] : '' }}</td>
                            <td>{{ $result ? $result['exam'] : '' }}</td>
                            <td>{{ $result ? $result['score'] : '' }}</td>
                            <td>{{ $gradings ? $gradings['alphaGrade'] : '' }}</td>
                            <td>{{ $result->remark }}</td>
                        </tr>
                        @php $n++; @endphp
                    @endforeach

                </tbody>
            </table>
        </div>
    @else
    <div result-table-skeleton>
        <img src="{{ asset('images/no-student.png') }}" class="w-[200px] justify-self-center"/>
    </div>

    @endif
</div>
