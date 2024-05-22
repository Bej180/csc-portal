<li data-nav="courses" ng-class="{'active': nav == 'courses'}"  ng-click="changeNav('courses')">
  <a href="{{ route('student.course_history') }}">
    <i class="material-symbols-rounded">playlist_add_check_rounded</i>
      <label>Course Registration</label>
  </a>
</li>
<li data-nav="results" ng-class="{'active': nav == 'results'}"  ng-click="changeNav('results')">
  <a href="{{ route('student.results') }}">
      <i class="material-symbols-rounded">school</i>
      <label>Results</label>
  </a>
</li>