
<x-popend title="Staff Profile" name="display_staff" ng-controller="AccountSetting">
    <div class="flex flex-col gap-3 items-center sm:flex-row">
        <img src="/profilepic/{% staff_in_view.id %}"
            class="w-28 sm:w-24 lg:w-28 aspect-square rounded-md object-cover" />
        <div class="text-center sm:text-left">
            <h1 class="text-2xl font-semibold" ng-bind="staff_in_view.user.name"></h1>
            <div> 
                <toggle ng-model="staff_in_view.designation" class="inline-block mt-1 text-lg px-2 py-1 uppercase bg-green-50/35 dark:bg-[#22242259] text-[--highlight-text-color]"
                                                options="{technologist:'fa fa-edit', lecturer:'fa fa-edit'}"
                                                
                                                ng-change="updateActiveSemester()">
                                            </toggle>
                <span class="!bg-transparent !text-zinc-400 dark:!text-zinc-300 text-xs">Click on the <i class="fa fa-edit"></i> above to toggle Designation</span> 
            </div>
        </div>
    </div>
    <div class="horizontal-divider"></div>
    <div class="grid grid-cols-5 mt-4">
        <p class="px-2 rounded-l-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-1">
            StaffID:</p>
        <p class="px-2 rounded-r-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-4"
            ng-bind="staff_in_view.staff_id"></p>

        <p
            class="px-2 rounded-l-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-1 bg-green-50/35 dark:bg-[#22242259] text-[--highlight-text-color]">
            Sex:</p>
        <p class="px-2 rounded-r-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-4 bg-green-50/35 dark:bg-[#22242259] text-[--highlight-text-color] font-semibold uppercase"
            ng-bind="staff_in_view.gender"></p>

        <p class="px-2 rounded-l-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-1">
            Phone:</p>
        <p class="px-2 rounded-r-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-4"
            ng-bind="staff_in_view.user.phone"></p>

        <p
            class="px-2 rounded-l-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-1 bg-green-50/35 dark:bg-[#22242259] text-[--highlight-text-color]">
            Address:</p>
        <p class="px-2 rounded-r-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-4 bg-green-50/35 dark:bg-[#22242259] text-[--highlight-text-color] font-semibold"
            ng-bind="staff_in_view.address"></p>

        <p class="px-2 rounded-l-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-1">
            Email:</p>
        <p class="px-2 rounded-r-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-4"
            ng-bind="staff_in_view.user.email"></p>
    </div>
    <div class="horizontal-divider"></div>
    <div class="px-2" ng-controller="StaffCourseAllocationController">

        <button type="button" class="text-sm font-semibold flex items-center justify-between w-full"
            ng-click="toggleDisplay()">

            <span class="font-[600]">Course Allocation</span>
            <span class="fa"
                ng-class="{'fa-chevron-up':!display_course_allocations, 'fa-chevron-down': display_course_allocations}"></span>
        </button>

        <div ng-if="display_course_allocations" class="flex flex-col gap-4 mt-2">

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
                        <select placeholder="Select Semester" ng-model="course_semester">
                            <option>HARMATTAN</option>
                            <option>RAIN</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <select placeholder="Select Level" ng-model="course_level">
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
                        ng-if="allocatables.length > 0"
                        ng-disabled="allocation_list.length==0||!course_semester||!course_level">
                        Allocate Courses
                    </button>
                </div>

            </fieldset>


        </div>
    </div>

    <div ng-init="show_class=false" ng-if="staff_in_view.designation != 'technologist'">
        <div class="horizontal-divider"></div>

        <div class="px-2">
            <button type="button" class="text-sm font-semibold flex items-center justify-between w-full"
                ng-click="show_class=!show_class">

                <span class="font-[600]">Class Advisory</span>
                <span class="fa" ng-class="{'fa-chevron-up':!show_class, 'fa-chevron-down': show_class}"></span>
            </button>

            <div ng-if="show_class" class="flex flex-col gap-4 mt-2">

                <fieldset class="p-fieldset" ng-if="staff_in_view.classes.length > 0">
                    <legend class="p-legend opacity-50 mb-2">Class Advisor of::</legend>

            
                    <div class="grid gap-2 grid-cols-2">
                        <span ng-repeat="set in staff_in_view.classes"
                        ng-click="toggle_mark_class(set)"
                        ng-class="{'chip-selected chip-danger': selected_for_class_removal(set.id)}"
                            class="text-center chip whitespace-nowrap" ng-bind="set.name+' class'"></span>

                    </div>

                    <div class="mt-2">
                        <button ng-click="update_advisory_list()" type="button" class="w-full btn btn-danger"
                            ng-if="remove_class_list.length > 0">
                            Remove from Class
                        </button>
                    </div>

                </fieldset>


                <fieldset class="p-fieldset">
                    <legend class="p-legend opacity-50 mb-2 mt-4">Make Class advisor</legend>


                    <form class="flex gap-3 mt-2 justify-between" ng-init="sessions=generateSessions(5)">

                        <div class="flex-1">
                            <select ng-model="session" customize="true" mask="9999/9999" options="sessions"
                                placeholder="Select Class"></select>
                        </div>

                        <button bg-if="session" class="btn btn-primary shrink-0"
                            controller="makeStaffAdvisor(staff_in_view.id, session)">Make Class
                            Advisor</button>
                    </form>

                </fieldset>


            </div>
        </div>
    </div>

    <div class="horizontal-divider"></div>

    <div class="px-2">
        <button type="button" class="text-sm font-semibold flex items-center justify-between w-full"
            ng-click="show_class=!show_class">

            <span class="font-[600]">Reset Login Details</span>
            <span class="fa"
                ng-class="{'fa-chevron-up':!show_class, 'fa-chevron-down': show_class}"></span>
        </button>
        <form class="flex flex-col gap-2"> 
            <div class="input-group">
                <label class="input-group-prepend" for="reset_email_address">Email</label>
                <input class="input" type="text" id="reset_email_address" ng-model="staff_in_view.email" placeholder="Enter Staff Email Address"/>
            </div>
            <div class="input-group">
                <label class="input-group-prepend" for="reset_username">Username</label>
                <input class="input" type="text" id="reset_username" ng-model="staff_in_view.username" placeholder="Enter Staff Username"/>
        
            </div>
            <label class="flex gap-1 items-center"><input class="checkbox" type="checkbox" ng-model="staff_in_view.reset_password"/> <span class="peer-checked:font-semibold">Reset Password</span></label>
            <div class="info !p-3 !rounded-md" ng-if="staff_in_view.reset_password">Password will be reset to <span ng-bind="staff_in_view.gender | his">staff's</span> <b>StaffID</b></div>
            <button type="button" class="btn btn-primary" controller="updateUserLogins(staff_in_view)">Save Changes</button>
        </form>
    </div>






</x-popend>
