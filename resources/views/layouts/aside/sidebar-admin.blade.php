<li class="sidebar-section !border-none">
    Items
</li>

<li data-nav="class" ng-class="{'active': nav == 'classes'}">
    <a href="{{ route('admin.show-classes') }}">
        <i class="faIcon"><x-icon name="diversity"/></i>
        <label>Classes</label>
    </a>
</li>

<li ng-class="{'active': nav == 'courses'}">
    <a href="{{ route('admin.show-courses') }}">
        <i><x-icon name="table_rows"/></i>
        <label>Courses</label>
    </a>


</li>


<li ng-class="{'active': nav == 'results'}">
    <a href="/admin/results" ng-click="toggle('results')">
        <i class="faIcon"><x-icon name="checklist"/></i>
        <label>Results</label>
    </a>

</li>

<li class="sidebar-section">
    Accounts
</li>
<li ng-class="{'active': nav == 'advisors'}">
    <a href="/admin/advisors">
        <i class="faIcon"><x-icon name="local_library"/></i>
        <label>Advisors</label>
    </a>

</li>

<li ng-class="{'active': nav == 'staffs'}">
    <a href="/admin/staffs">
        <span class="faIcon"><x-icon name="person_outline"/></span>
        <label>Lecturers</label>
    </a>

</li>



<li ng-class="{'active': nav == 'technologist'}">
    <a href="/admin/technologist" ng-click="toggle('technologist')">
        <i class="faIcon"><x-icon name="computer"/></i>
        <label>Technologists</label>
    </a>

</li>
<li ng-class="{'active': nav == 'students'}">
    <a href="/admin/students" ng-click="toggle('students')">
        <i class="faIcon"><x-icon name="school"/></i>
        <label>Students</label>
    </a>

</li>
<li ng-class="{'active': nav == 'moderators'}">
    <a href="/moderators" ng-click="toggle('moderators')">
        <i class="faIcon"><x-icon name="leaderboard"/></i>
        <label>HOD & Dean</label>
    </a>

</li>
<li class="sidebar-section">
    Settings
</li>

<li data-nav="configurations" ng-class="{'active': nav == 'configurations'}" ng-click="changeNav('configurations')">
    <a href="{{ route('admin.show-configurations') }}">
        <i class="faIcon"><x-icon name="tune"/></i>
        <label>Portal Configuration</label>
    </a>
</li>
