<off-canvas dir="end" title="Borrow Course: {{$requestedSemester}}" show="borrow_course">




    <form class="flex w-full items-center justify-between gap-2 mb-2">
      
        <div class="flex-1">
            <input type="search" name="search" ng-model="borrowQuery" class="input"
                placeholder="Course Code (eg: CSC 501)"/>

        </div>
        <div>
            <button type="button" ng-disabled="!borrowQuery" class="btn btn-primary" ng-click="SearchCourse($event)">Search</button>
        </div>

    </form>

    <form action="/course_registration" method="POST" data-semester="{{ $requestedSemester }}"
        data-session="{{ $requestedSession }}" data-level="{{ $requestedLevel }}">

        <div ng-if="borrowingCourses.length>0" id="course-registration-container" class="flex flex-col gap-2">
            <div class="text-body-300 flex items-center justify-between text-xs">
                <p>Total units selected:
                    <span class="font-semibold" ng-bind="units"
                        ng-class="{'text-red-500':units > maxUnits || units < minUnits, 'text-green-600':units < maxUnits && units > minUnits}"></span>
                    out of
                    <span class="font-semibold" ng-bind="maxUnits"></span>
                    max units
                </p>

                <a href="./course-registration-borrow-courses.html" class="opacity-0 -z-10">
                    <button ng-click="toggleBorrowing()" type="button"
                        class="btn bg-[var(--primary)] rounded text-white hover:bg-[var(--primary-700)] transition text-xs">
                        Add/Borrow Courses
                    </button>
                </a>
            </div>




            <div class="cardx text-sm">

                <table class="responsive-table">
                    <thead>
                        <tr>
                            <th class="w-10">Select</th>
                            <th class="w-20">Code</th>
                            <th>Title</th>
                            <th class="w-20 text-center">Units</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr ng-repeat="borrow_course in borrowedCourses track by borrow_course.id"
                            data-id="{% borrow_course.id %}" data-code="{% borrow_course.code %}"
                            data-name="{% borrow_course.name %}" data-units="{% borrow_course.units %}">
                            <td class="mb-2">
                                <x-checkbox ng-click="borrow($event, units + borrow_course.units)" name="borrow[]" ng-checked="true" />
                            </td>
                            <td ng-bind="borrow_course.code" class="mb-2"></td>
                            <td ng-bind="borrow_course.name" class="mb-2"></td>
                            <td ng-bind="borrow_course.units" class="text-center mb-2"></td>
                        </tr>

                        <tr ng-repeat="course in borrowingCourses track by course.id"
                            ng-show="!borrowedCourses[course.id]" data-id="{% course.id %}" data-code="{%course.code%}"
                            data-name="{%course.name%}" data-units="{%course.units%}">
                            <td class="mb-2">
                              <x-checkbox ng-click="borrow($event, units + course.units)" name="selectCourse" />
                            </td>
                            <td ng-bind="course.code" class="mb-2"></td>
                            <td ng-bind="course.name" class="mb-2"></td>
                            <td ng-bind="course.units" class="text-center mb-2"></td>
                        </tr>
                    </tbody>
                </table>
            </div>








            <input type="hidden" name="semester" value="{{ $requestedSemester }}" />
            <input type="hidden" name="level" value="{{ $requestedLevel }}" />
            <input type="hidden" name="session" value="{{ $requestedSession }}" />

            <input type="hidden" name="courses[]" id="courses" />
            <button type="button" class="btn btn-primary" ng-if="borrowedCourses.length>0" ng-click="saveBorrowedCourses($event)">
                Borrow Courses
            </button>
        </div>
    </form>
</off-canvas>
