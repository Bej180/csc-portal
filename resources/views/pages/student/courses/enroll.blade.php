@php


    use App\Models\Course;
    $requestedLevel = request()->get('level');
    $requestedSemester = request()->get('semester');
    $requestedSession = request()->get('session');

    $chosenLevelAndSemester = $requestedLevel && $requestedSemester && $requestedSession;

    $user = \App\Models\User::active();
    $student = $user->student;
    $class = $student->class;

    $courses = Course::getCourses($_GET['level'] ?? null, $_GET['semester'] ?? null);

@endphp
<x-template nav='courses' title="Course Registration" controller="StudentCourseRegistrationController" ng-init="initiate_courses()">
            
                @include('pages.student.courses.form')
            
                @include('pages.student.courses.index')

                @include('pages.student.courses.borrow')

            <style>
                #main-slot {
                    padding: 0px;
                }
            </style>

</x-template>
