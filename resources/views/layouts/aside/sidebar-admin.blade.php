<li class="sidebar-section !border-none">
    Items
</li>

<li data-nav="class" ng-class="{'active': nav == 'classes'}">
    <a href="{{ route('admin.show-classes') }}">
        <i class="material-symbols-rounded">school_class_rounded</i>
        <label>Classes</label>
    </a>
</li>

<li ng-class="{'active': nav == 'courses'}">
    <a href="{{ route('admin.show-courses') }}">
        <i class="material-symbols-rounded">table_rows_rounded</i>
        <label>Courses</label>
    </a>


</li>


<li ng-class="{'active': nav == 'results'}">
    <a href="/admin/results" ng-click="toggle('results')">
        <i class="material-symbols-rounded">checklist_rounded</i>
        <label>Results</label>
    </a>

</li>

<li class="sidebar-section">
    Accounts
</li>
<li ng-class="{'active': nav == 'advisors'}">
    <a href="/admin/advisors">
        <i class="material-symbols-rounded">local_library</i>
        <label>Advisors</label>
    </a>

</li>

<li ng-class="{'active': nav == 'staffs'}">
    <a href="/admin/staffs">
        <span class="material-symbols-rounded">person_outline_rounded</span>
        <label>Lecturers</label>
    </a>

</li>



<li ng-class="{'active': nav == 'technologist'}">
    <a href="/admin/technologist" ng-click="toggle('technologist')">
        <i class="material-symbols-rounded">computer_rounded</i>
        <label>Technologists</label>
    </a>

</li>
<li ng-class="{'active': nav == 'students'}">
    <a href="/admin/students" ng-click="toggle('students')">
        <i class="material-symbols-rounded">school_class_rounded</i>
        <label>Students</label>
    </a>

</li>
<li ng-class="{'active': nav == 'moderators'}">
    <a href="/moderators" ng-click="toggle('moderators')">
        <i class="material-symbols-rounded">leaderboard_rounded</i>
        <label>HOD & Dean</label>
    </a>

</li>
<li class="sidebar-section">
    Settings
</li>

<li data-nav="configurations" ng-class="{'active': nav == 'configurations'}" ng-click="changeNav('configurations')">
    <a href="{{ route('admin.show-configurations') }}">
        <i class="material-symbols-rounded">settings</i>
        <label>Portal Configuration</label>
    </a>
</li>
