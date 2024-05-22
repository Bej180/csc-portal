@php
    $advisor = \App\Models\Advisor::active();
    $authUser = auth()->user();
    $classes = $authUser->profile->classes()->with('students')->get();
    $class = $classes->first();
    $students = $class->students();
    $number_of_students_in_class = $students->count();
    $allStudents = $students->cursorPaginate(5);
    $number_of_semester_courses = 5;
    $results = \App\Models\Result::with('course')->orderBy('level', 'desc');
    $totalResultsUploaded = $results->count();

    $results = $results->cursorPaginate(15);

@endphp
<x-template nav="home" title="Advisor Dashboard">


    <div class="scroller">

        <x-page-header>Dashbaord</x-page-header>

        <div class="dashboard-cards">
            <div class="box card-purple">
                <div class="card-box">
                    <span class="material-symbols-rounded">
                        groups
                    </span>
                </div>
                <div class="box-body rounded-lg flex flex-col w-full text-right justify-end">
                    <div class="card-session">Students</div>
                    <div class="card-counter">{{ $number_of_students_in_class }}</div>
                </div>


            </div>

            <div class="box card-orange">
                <div class="card-box">
                    <span class="material-symbols-rounded">
                        book
                    </span>
                </div>
                <div class="box-body rounded-lg flex flex-col w-full text-right justify-end">
                    <div class="card-session">Courses</div>
                    <div class="card-counter">500</div>
                </div>


            </div>

            <div class="box card-blue">
                <div class="card-box">
                    <span class="material-symbols-rounded">
                        bar_chart
                    </span>
                </div>
                <div class="box-body rounded-lg flex flex-col w-full text-right justify-end">
                    <div class="card-session">Results</div>
                    <div class="card-counter">{{ $totalResultsUploaded }}</div>
                </div>


            </div>

            <div class="box card-green">
                <div class="card-box">
                    <span class="material-symbols-rounded">
                        groups
                    </span>
                </div>
                <div class="box-body rounded-lg flex flex-col w-full text-right justify-end">
                    <div class="card-session">Student</div>
                    <div class="card-counter">+255</div>
                </div>
                <div class="box-footer">
                    5 New Students
                </div>


            </div>

        </div>


        <div class="courses mt-2 !hidden" id="dashboard-cards">
            <!-- DASHBOARD CARD -->



        
        </div>

        <x-todo />




        <h1 class="text-lg text-body-300 font-semibold mt-8 flex gap-1">
            Top Five
            <span class="material-symbols-rounded">star</span>
        </h1>

        <div class="mt-2 overflow-x-auto max-w-full min-w-full">
            <table class="responsive-table min-w-full whitespace-nowrap">
                <thead>
                    <th class="min-w-16"></th>
                    <th>Student Name</th>
                    <th>Reg. Number</th>
                    <th class="w-20">Level</th>
                    <th class="w-20">CGPA</th>
                </thead>
                <tbody>
                    @foreach ($allStudents as $student)
                        <tr>
                            <td align="center">
                                <x-profile-pic :user="$student" alt="student_pic"
                                    class="w-10 h-10 rounded-full object-cover" />
                            </td>
                            <td>{{ $student->user->name }}</td>
                            <td>{{ $student->reg_no }}</td>
                            <td>{{ $student->level }}</td>
                            <td>{{ $student->calculateCGPA() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <h1 class="text-lg text-body-300 font-semibold mt-8">Results Uploaded</h1>

        <div class="mt-2 overflow-x-auto max-w-full min-w-full">
            <table class="responsive-table input-sm min-w-full whitespace-nowrap">
                <thead>
                    <th class="w-20">Course Code</th>
                    <th>Course Title</th>
                    <th class="w-20">Units</th>
                    <th class="w-20"></th>
                </thead>
                <tbody>
                    @foreach ($results as $result)
                        <tr>
                            <td class="uppercase">{{ $result->course->code }}</td>
                            <td>{{ $result->course->name }}</td>
                            <td>{{ $result->course->units }}</td>
                            <td>
                                <a class="btn-primary"
                                    href="/display_results?session={{ $result->session }}&semester={{ $result->semester }}&course=all">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

</x-template>
<style>

    html:not(.dark) body {
        background: #faf8f8;
    }

</style>