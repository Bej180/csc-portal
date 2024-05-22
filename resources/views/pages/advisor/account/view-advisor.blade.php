<div ng-show="advisor">

    <div class="flex flex-col lg:m-5 bg-white dark:bg-inherit rounded-md p-8">
        <div
            class="bg-slate-50/10 h-36 flex flex-col lg:flex-row text-center justify-center gap-3 items-center lg:text-left lg:justify-start p-4 relative border-b-4">
            <div>
                <p class="text-2xl lg:text-3xl font-bold mb-3" ng-bind="advisor.user.name"></p>
                <p class="font-bold" ng-bind="advisor.staff_id"></p>
            </div>

            <img src="{% advisor.image %}"
                class="w-28 h-28 object-cover rounded-full absolute right-10 -bottom-[2.8rem] border-4" />
        </div>
        <div class="flex-1 bg-white dark:bg-zinc-800">
            <div>
                <div class="p-4 my-2">
                    <div class="mb-1 font-semibold text-slate-900 dark:text-slate-200">Basic Information</div>
                    <div class="lg:flex flex-wrap gap-3">
                        <div>
                            Phone
                            <div class="font-semibold" ng-bind="advisor.phone">
                            </div>
                        </div>


                        <div>
                            Email
                            <div class="font-semibold" ng-bind="advisor.user.email">
                            </div>
                        </div>





                        <div>
                            Address
                            <div class="font-semibold" ng-bind="advisor.address">

                            </div>
                        </div>

                    </div>
                </div>



                <div class="p-4 my-2">
                    <div class="mb-1 font-semibold text-slate-900 dark:text-slate-200">Class Information</div>
                    <div class="lg:flex gap-5">
                        <div class="lg:flex lg:flex-col">
                            <span>Class</span>
                            <span class="font-semibold" ng-bind="advisor.academic_set.name"></span>
                        </div>

                        <div class="lg:flex lg:flex-col">
                            <span>Level</span>
                            <span class="font-semibold" ng-bind="500"></span>
                        </div>

                        <div class="lg:flex lg:flex-col">
                            <span>No of Students</span>
                            <span class="font-semibold" ng-bind="advisor.studentsCount"></span>
                        </div>


                    </div>
                </div>


                <div class="p-4 my-2">
                    <div class="flex items-center space-x-2 text-base">
                        <h4 class="font-semibold text-slate-900 dark:text-slate-200">Students</h4>
                        <span
                            class="rounded-full bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700">{% advisor.studentsCount %}</span>
                    </div>
                    <div class="mt-3 flex -space-x-2 overflow-hidden p-2">
                        <img ng-repeat="student in advisor.students track by student.id"
                            class="inline-block h-12 w-12 object-cover rounded-full ring-2 ring-white"
                            src="{% student.picture %}" alt="{% student.user.name %}" />
                    </div>
                    <div class="mt-3 text-sm font-medium" ng-show="(advisor.studentsCount - 3) > 0">
                        <a href="#" class="text-blue-500">+ {% advisor.studentsCount-3 %} others</a>
                    </div>

                </div>



            </div>

            <div class="flex justify-end px-4 pb-3">
                <button class="btn-primary" ng-click="openEditor()">
                    Edit Advisor Details
                </button>
            </div>

            @include('popups.edit-advisor')
        </div>

    </div>


</div>

<div ng-show="!advisor">
    <img src="{{ asset('images/no-advisor.png') }}" />
</div>
