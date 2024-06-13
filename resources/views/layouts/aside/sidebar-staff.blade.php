<li data-nav="announcement" ng-class="{'active': isActiveNav('announcement')}">
    <a href="/home">
        <i class="material-symbols-rounded">notifications_rounded</i>
        <label>Announcement</label>
    </a>
</li>


@if (auth()->user()?->is('advisor'))
    <li data-nav="student_course_reg" ng-class="{'active': nav == 'student_course_reg'}">
        <a href="{{ route('advisor.students-course-registrations') }}">
            <i class="material-symbols-rounded">playlist_add_check_rounded</i>
            <label>Course Registrations</label>
        </a>
    </li>

    <li data-nav="class" ng-class="{'active': isActiveNav('class')}">
        <a href="{{ route('advisor.show-class') }}">
            <i class="material-symbols-rounded">school_class_rounded</i>
            <label>My Class</label>
        </a>
    </li>
@elseif (auth()->user()?->is('hod'))
    <li data-nav="results" ng-class="{'active': isActiveNav('results')}">
        <a href="/hod/results">
            <i class="material-symbols-rounded">checklist_rounded</i>
            <label>Results</label>
        </a>
    </li>


    <li data-nav="courses" ng-class="{'active': isActiveNav('courses')}">
        <a href="/hod/courses">
            <i class="material-symbols-rounded">assignment_ind_rounded</i>
            <label>Course Allocation</label>
        </a>
    </li>


    <li data-nav="staff" ng-class="{'active': isActiveNav('staff')}">
        <a href="/hod/staff">
            <i class="material-symbols-rounded">people_rounded</i>
            <label>Staff</label>
        </a>
    </li>
@endif

@if (auth()->user()->staff->eitherOfMyCoursesHasPractical())
    <li data-nav="courses" ng-class="{'active': isActiveNav('courses')}">
        <a href="{{ route('staff.lab_score_results')}}">
            <i class="material-symbols-rounded">computer</i>
            <label>Lab Results</label>
        </a>
    </li>
@endif

@if (auth()->user()->staff->is('technologist'))
    <li data-nav="courses" ng-class="{'active': isActiveNav('courses')}">
        <a href="/technologist/lab_results
    ">
            <i class="material-symbols-rounded">checklist_rounded</i>
            <label>Lab Results</label>
        </a>
    </li>
    <li data-nav="courses" ng-class="{'active': isActiveNav('courses')}">
        <a href="/technologist/eattendance">
            <i class="material-symbols-rounded">description</i>
            <label>eAttendance</label>
        </a>
    </li>
@elseif (count(auth()->user()->staff->courses))
    <li data-nav="courses" ng-class="{'active': isActiveNav('courses')}">
        <a href="{{ route('staff.results') }}">
            <i class="material-symbols-rounded">checklist_rounded</i>
            <label>Results</label>
        </a>
    </li>
@endif



{{-- <li class="has-menu" ng-class="{'active': isActiveNav('results')}">
    <a href="#" ng-click="toggle('results')">
        <i class="material-symbols-rounded">local_library</i>
        <label>Results</label>
    </a>

    <ul class="sub-menu">
        <li><a href="{{ route('upload.ogr') }}"> Upload Excel OGR</a></li>
        <li><a href="/staff/uploadManually"> Add Results Manually</a></li>
    </ul>

</li>


<li class="has-menu" ng-class="{'active': isActiveNav('results')}">
    <a href="#" ng-click="toggle('results')">
        <i class="material-symbols-rounded">poll</i>
        <label>Results</label>
    </a>

    <ul class="sub-menu">
        <li><a href="{{ route('advisor.show-results') }}"> All Results</a></li>
        <li><a href="{{ route('advisor.cgpa_summary') }}"> CGPA Summary Result</a></li>
    </ul>

</li> --}}
