<x-popend class="!bg-transparent !lg:bg-white" name="open_borror_panel">

    <div class="flex w-full items-center justify-between gap-2 mb-2">
      
        <div class="flex-1">
          {% semester %}
            <input type="search" ng-model="borrowQuery" class="input"
                placeholder="Course Code (eg: CSC 501)"/>
  
        </div>
        <div>
            <button type="button" ng-disabled="!borrowQuery" class="btn btn-primary" ng-click="SearchCourse()">Search</button>
        </div>
    </div>
  
    <form method="POST">
  
        <div ng-if="borrowingCourses.length>0" id="course-registration-container" class="flex flex-col gap-2">
            
  
  
  
  
            <div class="card text-sm">
              <div class="text-body-300 flex items-center justify-between text-xs">
                  <p>Total units selected:
                      <span class="font-semibold" ng-bind="selectedUnits"
                          ng-class="{'text-red-500':selectedUnits > maxUnits || selectedUnits < minUnits, 'text-green-600':selectedUnits < maxUnits && selectedUnits > minUnits}"></span>
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
  
                        {{-- <tr ng-repeat="borrow_course in borrowedCourses track by borrow_course.id"
                            data-id="{% borrow_course.id %}" data-code="{% borrow_course.code %}"
                            data-name="{% borrow_course.name %}" data-units="{% borrow_course.units %}">
                            <td class="mb-2">
                                <x-checkbox ng-click="toggleBorrow($event, borrow_course)" name="borrow[]" ng-checked="true" />
                            </td>
                            <td ng-bind="borrow_course.code" class="mb-2"></td>
                            <td ng-bind="borrow_course.name" class="mb-2"></td>
                            <td ng-bind="borrow_course.units" class="text-center mb-2"></td>
                        </tr> --}}
  
                        <tr ng-repeat="course in borrowingCourses track by course.id"
                            ng-show="!borrowedCourses[course.id]" data-id="{% course.id %}" data-code="{%course.code%}"
                            data-name="{%course.name%}" data-units="{%course.units%}">
                            <td class="mb-2">
                              <input type="checkbox" class="checkbox" ng-click="toggleBorrow($event, course)" name="selectCourse" />
                            </td>
                            <td ng-bind="course.code" class="mb-2"></td>
                            <td ng-bind="course.name" class="mb-2"></td>
                            <td ng-bind="course.units" class="text-center mb-2"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button type="button" class="btn btn-primary" ng-click="popDown('open_borror_panel')">
              Done
          </button>
  
  
  
  
  
           
        </div>
    </form>
  
  </x-popend>