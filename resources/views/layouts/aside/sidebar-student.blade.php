<li data-nav="courses" ng-class="{'active': isActiveNav('courses')}"  ng-click="changeNav('courses')">
  <a href="{{ route('student.enrollments') }}">
    <i class="faIcon"><x-icon name="playlist_add"/></i>
      <label>Enrollments</label>
  </a>
</li>
<li data-nav="results" ng-class="{'active': isActiveNav('results')}"  ng-click="changeNav('results')">
  <a href="{{ route('student.results') }}">
      <i class="faIcon"><x-icon name="school"/></i>
      <label>Results</label>
  </a>
</li>