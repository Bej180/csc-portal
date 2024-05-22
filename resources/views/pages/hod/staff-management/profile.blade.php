<x-popend title="Staff Profile" name="display_staff">
    <div class="flex flex-col gap-3 items-center sm:flex-row">
        <img src="/profilepic/{% display_staff.id %}"
            class="w-28 sm:w-24 lg:w-28 aspect-square rounded-md object-cover" />
        <div class="text-center sm:text-left">
            <h1 class="text-2xl font-semibold" ng-bind="display_staff.user.name"></h1>
            <p class="mt-1 text-lg px-2 py-1 uppercase bg-green-50/35 text-[--highlight-text-color]"
                ng-bind="display_staff.designation"></p>
        </div>
    </div>
    <div class="horizontal-divider"></div>
    <div class="grid grid-cols-5 mt-4">
        <p class="px-2 rounded-l-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-1">
            StaffID:</p>
        <p class="px-2 rounded-r-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-4"
            ng-bind="display_staff.staff_id"></p>

        <p
            class="px-2 rounded-l-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-1 bg-green-50/35 text-[--highlight-text-color]">
            Sex:</p>
        <p class="px-2 rounded-r-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-4 bg-green-50/35 text-[--highlight-text-color] font-semibold uppercase"
            ng-bind="display_staff.gender"></p>

        <p class="px-2 rounded-l-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-1">
            Phone:</p>
        <p class="px-2 rounded-r-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-4"
            ng-bind="display_staff.user.phone"></p>

        <p
            class="px-2 rounded-l-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-1 bg-green-50/35 text-[--highlight-text-color]">
            Address:</p>
        <p class="px-2 rounded-r-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-4 bg-green-50/35 text-[--highlight-text-color] font-semibold"
            ng-bind="display_staff.address"></p>

        <p class="px-2 rounded-l-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-1">
            Email:</p>
        <p class="px-2 rounded-r-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-4"
            ng-bind="display_staff.user.email"></p>
    </div>
    <div class="horizontal-divider"></div>
    <div class="p-2" ng-controller="HODCourseAllocationController">
        <div class="opacity-50 mb-2">Courses Offered<span ng-if="display_staff.designation=='technologist'"
                class="opacity-100 text-primary font-semibold"> (practical)</span>:</div>
        <div ng-if="display_staff.courses.length > 0" class="grid grid-cols-4 gap-2">
            {% course %}
            <span ng-click="toggleAppendForDeallocation(allocation.course.id)"
                ng-class="{'chip-selected chip-danger deallocatable': selectedForDeallocation(allocation.course.id)}"
                ng-repeat="allocation in display_staff.courses" class="chip whitespace-nowrap"
                ng-bind="allocation.course.code"></span>

        </div>
        <div class="mt-2">
            <button ng-click="deallocate(display_staff.id)" type="button" class="w-full btn btn-danger"
                ng-disabled="deallocation_courses.length < 1">
                Deallocate
            </button>
        </div>
        <div ng-if="!display_staff.courses.length">
            NO COURSE ASSIGNED
        </div>



        <div class="opacity-50 mb-2 mt-4">Allocate Courses<span ng-if="display_staff.designation=='technologist'"
                class="opacity-100 text-primary font-semibold"> (practical)</span>:</div>
        <div ng-if="display_staff.courses.length > 0" class="grid grid-cols-4 gap-2">
            {% course %}
            <span ng-click="toggleAppendForDeallocation(allocation.course.id)"
                ng-class="{'chip-selected chip-primary allocatable': selectedForDeallocation(allocation.course.id)}"
                ng-repeat="allocation in display_staff.courses" class="chip whitespace-nowrap"
                ng-bind="allocation.course.code"></span>

        </div>
        <div class="mt-2">
            <button ng-click="deallocate(display_staff.id)" type="button" class="w-full btn btn-primary"
                ng-disabled="deallocation_courses.length < 1">
                Allocate Courses
            </button>
        </div>
        <div ng-if="!display_staff.courses.length">
            NO COURSE ASSIGNED
        </div>
    </div>

    <ng-template ng-if="display_staff.classes.length">
        <div class="horizontal-divider"></div>
        <div class="p-2">
            <div class="opacity-50 mb-2">Class Advisor of:</div>
            <div>
                <span ng-repeat="set in display_staff.classes" class="text-center chip chip-green whitespace-nowrap"
                    ng-bind="set.name+' class'"></span>

            </div>
        </div>
    </ng-template>











</x-popend>
