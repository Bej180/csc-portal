<x-popend title="Staff Profile" name="display_staff">
    <div class="flex flex-col gap-3 items-center sm:flex-row">
        <img src="/profilepic/{% staff_in_view.id %}"
            class="w-28 sm:w-24 lg:w-28 aspect-square rounded-md object-cover" />
        <div class="text-center sm:text-left">
            <h1 class="text-2xl font-semibold" ng-bind="staff_in_view.user.name"></h1>
            <p class="mt-1 text-lg px-2 py-1 uppercase bg-green-50/35 text-[--highlight-text-color]"
                ng-bind="staff_in_view.designation"></p>
        </div>
    </div>
    <div class="horizontal-divider"></div>
    <div class="grid grid-cols-5 mt-4">
        <p class="px-2 rounded-l-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-1">
            StaffID:</p>
        <p class="px-2 rounded-r-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-4"
            ng-bind="staff_in_view.staff_id"></p>

        <p
            class="px-2 rounded-l-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-1 bg-green-50/35 text-[--highlight-text-color]">
            Sex:</p>
        <p class="px-2 rounded-r-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-4 bg-green-50/35 text-[--highlight-text-color] font-semibold uppercase"
            ng-bind="staff_in_view.gender"></p>

        <p class="px-2 rounded-l-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-1">
            Phone:</p>
        <p class="px-2 rounded-r-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-4"
            ng-bind="staff_in_view.user.phone"></p>

        <p
            class="px-2 rounded-l-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-1 bg-green-50/35 text-[--highlight-text-color]">
            Address:</p>
        <p class="px-2 rounded-r-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-4 bg-green-50/35 text-[--highlight-text-color] font-semibold"
            ng-bind="staff_in_view.address"></p>

        <p class="px-2 rounded-l-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-1">
            Email:</p>
        <p class="px-2 rounded-r-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-4"
            ng-bind="staff_in_view.user.email"></p>
    </div>
    <div class="horizontal-divider"></div>
    <div class="p-2 pt-0" ng-controller="HODCourseAllocationController">

        <button type="button" class="text-sm font-semibold flex items-center justify-between w-full pb-2"
            ng-click="toggleDisplay()">

            <span class="font-[600]">Course Allocation</span>
            <span class="fa"
                ng-class="{'fa-chevron-up':!display_course_allocations, 'fa-chevron-down': display_course_allocations}"></span>
        </button>

        <div ng-if="display_course_allocations" class="flex flex-col gap-4">

            <fieldset class="p-fieldset">
                <legend class="p-legend opacity-50 mb-2">Courses Offered<span
                        ng-if="staff_in_view.designation=='technologist'"
                        class="opacity-100 text-primary font-semibold"> (practical)</span>:</legend>
                <div ng-if="staff_courses.length > 0" class="grid grid-cols-4 gap-2">
                    {% course %}
                    <span ng-click="toggle_courses_for_deallocation(course)"
                        ng-class="{'chip-selected chip-danger deallocatable': selected_for_deallocation(course.id)}"
                        ng-repeat="course in staff_courses" class="chip whitespace-nowrap" ng-bind="course.code"></span>

                </div>
                <div class="mt-2">
                    <button ng-click="deallocate_courses()" type="button" class="w-full btn btn-danger"
                        ng-if="deallocation_list.length > 0">
                        Deallocate Courses
                    </button>
                </div>
                <div ng-if="!staff_courses.length" class="text-zinc-500 text-center">
                    NO COURSE ASSIGNED
                </div>
            </fieldset>


            <fieldset class="p-fieldset">
                <legend class="p-legend opacity-50 mb-2 mt-4">Allocate Courses<span
                        ng-if="staff_in_view.designation=='technologist'"
                        class="opacity-100 text-primary font-semibold"> (practical)</span>:</legend>
                <div class="flex gap-2.5 mb-2">
                    <div class="flex-1">
                        <select drop="top" placeholder="Select Semester" ng-model="course_semester">
                            <option>HARMATTAN</option>
                            <option>RAIN</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <select drop="top" placeholder="Select Level" ng-model="course_level">
                            <option>100</option>
                            <option>200</option>
                            <option>300</option>
                            <option>400</option>
                            <option>500</option>
                        </select>
                    </div>
                    <div>
                        <button type="button" ng-disabled="!course_semester||!course_level"
                            class="btn btn-icon btn-primary"
                            ng-click="getAllocatableCourses({staff_id:staff_in_view.id, level:course_level,semester:course_semester})">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
                <div ng-if="allocatables.length > 0" class="grid grid-cols-4 gap-2">

                    <span ng-click="toggle_courses_for_allocation(allocatable)"
                        ng-class="{'chip-selected chip-primary allocatable': selected_for_allocation(allocatable.id)}"
                        ng-repeat="allocatable in allocatables" class="chip whitespace-nowrap"
                        ng-bind="allocatable.code"></span>

                </div>
                <div class="mt-2">
                    <button ng-click="allocate_courses()" type="button" class="w-full btn btn-primary"
                        ng-if="allocatables.length > 0" ng-disabled="allocation_list.length==0||!course_semester||!course_level">
                        Allocate Courses
                    </button>
                </div>
                
            </fieldset>


        </div>
    </div>
    <ng-template ng-if="staff_in_view.classes.length">
        <div class="horizontal-divider"></div>
        <div class="p-2">
            <div class="opacity-50 mb-2">Class Advisor of:</div>
            <div>
                <span ng-repeat="set in staff_in_view.classes" class="text-center chip chip-green whitespace-nowrap"
                    ng-bind="set.name+' class'"></span>

            </div>
        </div>
    </ng-template>
</x-popend>
