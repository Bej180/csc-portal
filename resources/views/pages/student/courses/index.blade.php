
<div class="p-4">
    <div id="course-registration-container" class="flex flex-col gap-2 overflow-y-visible">
        <div class="text-sm text-body-300 flex items-center justify-between sticky top-[20px] z-10">
            <span :class="{ 'hidden': selectedUnits == 0 }" style="text-shadow: 1px 1px 0px #c4bebe;background: #e7ece7fc;padding: 2px 5px;border-radius: 5px;border: 3px solid #fff;box-shadow: 0px 1px 2px #00000">Total units selected:

                <span class="font-semibold" ng-bind="selectedUnits"
                    ng-class="{'text-red-500':selectedUnits > maxUnits || selectedUnits < minUnits, 'text-green-600':selectedUnits < maxUnits && selectedUnits > minUnits}"></span>
                out of
                <span class="font-semibold" ng-bind="maxUnits"></span>
                max units
            </span>
            <span ng-class="{hidden:selectedUnits>=0}">
                Unit Range (
                min: <span class="font-semibold" ng-bind="minUnits"></span>
                max: <span class="font-semibold" ng-bind="maxUnits"></span>
                )

            </span>
            <button ng-if="regData.level != 100" type="button" class="btn btn-primary" ng-click="openBorrorPanel()">
                Borrow Courses
            </button>
        </div>

        <div ng-show="regData.level == 100" class="text-xm text-red-500  italic">
            <i class="fa fa-exclamation-triangle"></i> You are required to choose either IGB or FRN
        </div>

        <form>
           
            <input type="hidden" ng-model="coursesRegistrationData.session"/>
            <input type="hidden" ng-model="coursesRegistrationData.level"/>
            <input type="hidden" ng-model="coursesRegistrationData.semester"/>

           
            <div class="card has-table" style="height:initial">
                <table class="responsive-table min-w-full whitespace-nowrap">
                    <thead class="print:bg-black print:text-white h-14">
                        <tr>
                            <th class="!text-center">Select</th>
                            <th>Code</th>
                            <th>Title</th>
                            <th class="!text-center">Units</th>
                        </tr>
                    </thead>
                    <tbody ng-repeat="(option, courses) in reg_courses" id="course-registeration-prepend">
                        
                        <tr>
                            <td colspan="4" >
                                <div ng-bind="option" class="font-semibold text-center">
                                </div>
                            </td>
                        </tr>
                        <tr ng-repeat="course in courses">
                            <td class="!text-center">
                                <input type="checkbox" name="course[]" value="{%course.id%}" class="checkbox"
                                    ng-checked="course.checked" ng-model="courses_to_be_registered"
                                    ng-click="toggleSelect($event, course)" />
                            </td>
                            <td class="uppercase" ng-bind="course.code"></td>
                            <td ng-bind="course.name"></td>
                            <td class="!text-center" ng-bind="course.units" class="text-center"></td>
                           
                        </tr>

                    </tbody>
                </table>
            </div>

            <div class="flex justify-end my-2">
                <button controller="registerCourses()" ng-disabled="!proceed && (selectedUnits < minUnits || selectedUnits > maxUnits)" type="button"
                    class="btn-primary">Register courses</button>
            </div>
        </form>
    </div>
</div>
