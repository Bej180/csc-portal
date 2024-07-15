<li class="sidebar-section !border-none">
    Items
</li>

<li data-nav="class" ng-class="{active: isActiveNav('classes')}">
    <a href="{{ route('admin.show-classes') }}">
        <i class="faIcon"><x-icon name="diversity"/></i>
        <label>Classes</label>
    </a>
</li>

<li ng-class="{active: isActiveNav('courses')}">
    <a href="{{ route('admin.show-courses') }}">
        <i><x-icon name="table_rows"/></i>
        <label>Courses</label>
    </a>


</li>


<li ng-class="{active: isActiveNav('results')}">
    <a href="/admin/results" ng-click="toggle('results')">
        <i class="faIcon"><x-icon name="checklist"/></i>
        <label>Results</label>
    </a>

</li>

<li class="sidebar-section">
    Accounts
</li>
<li ng-class="{active: isActiveNav('advisors')}">
    <a href="/admin/advisors">
        <i class="faIcon"><x-icon name="local_library"/></i>
        <label>Advisors</label>
    </a>

</li>

<li ng-class="{active: isActiveNav('staffs')}">
    <a href="/admin/staffs">
        <span class="faIcon"><x-icon name="person_outline"/></span>
        <label>Lecturers</label>
    </a>

</li>



<li ng-class="{active: isActiveNav('technologist')}">
    <a href="/admin/technologist" ng-click="toggle('technologist')">
        <i class="faIcon"><x-icon name="computer"/></i>
        <label>Technologists</label>
    </a>

</li>
<li ng-class="{active: isActiveNav('students')}">
    <a href="/admin/students" ng-click="toggle('students')">
        <i class="faIcon"><x-icon name="school"/></i>
        <label>Students</label>
    </a>

</li>
<li ng-class="{active: isActiveNav('moderators')}">
    <a href="/moderators" ng-click="toggle('moderators')">
        <i class="faIcon"><x-icon name="leaderboard"/></i>
        <label>HOD & Dean</label>
    </a>

</li>
<li class="sidebar-section">
    Settings
</li>

<li data-nav="recycle" ng-class="{active: isActiveNav('recycle')}" ng-click="changeNav('recycle')">
    <a href="{{ route('admin.recycle-bin') }}">
        <i class="faIcon"><x-icon name="delete"/></i>
        <label>Recycle Bin</label>
    </a>
</li>
<li data-nav="configurations" ng-class="{active: isActiveNav('configurations')}" ng-click="changeNav('configurations')">
    <a href="{{ route('admin.show-configurations') }}">
        <i class="faIcon"><x-icon name="tune"/></i>
        <label>Portal Configuration</label>
    </a>
</li>
