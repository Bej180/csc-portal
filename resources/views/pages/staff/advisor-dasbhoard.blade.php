@php

    
@endphp
<x-template nav="home" title="Advisor's Dashboard">
    <main class="half" ng-controller="AdvisorController" ng-init="initDashboard()">
        <div class="half-60">

            <div class="card lg:w-auto lg:h-full cursor-context-menu lg:col-span-5">
                <div class="card-header">
                    <div class="card-title">Overview</div>
                </div>
                <div class="card-body flex flex-col gap-4" infinite-scroll="moreCourses()">

                    <section class="grid gap-3 lg:grid-cols-3">

                        <div class="bg-[--orange-200] p-component rounded-lg" 
                            >
                            <div class="panel-header" >
                                <div class="md:flex flex-wrap md:items-center md:gap-2">
                                    <img src="/profilepic/{% advisor.myAccount.id %}" alt="advisor"
                                        class="aspect-square w-12 rounded-full object-cover" />
                                    <div>
                                        <p class="font-semibold text-xl" ng-bind="advisor.myAccount.user.name"></p>
                                        <p class="font-medium text-[--primary-700]" ng-bind="advisor.myAccount.staff_id"></p>
                                    </div>
                                </div>
                                <div class="panel-icons" ></div>
                            </div>

                        </div>

                        <div
                            class="bg-[--purple-200] p-4 flex flex-col justify-between overflow-hidden rounded-md min-h-32">
                            <header class="w-full flex items-center justify-between">
                                <p class="font-medium uppercase">COURSES</p>
                                <span class="material-symbols-rounded">group</span>
                            </header>
                            <div>
                                <h1 class="font-medium text-4xl" ng-bind="advisor.count_courses"></h1>
                                <h1 class="font-medium text-4xl">
                                </h1>
                            </div>


                        </div>
                        <div
                            class="bg-[--blue-200] p-4 flex flex-col justify-between overflow-hidden rounded-md min-h-32">
                            <header class="w-full flex items-center justify-between">
                                <p class="font-medium uppercase">students</p>
                                <span class="material-symbols-rounded">group</span>
                            </header>
                            <div>
                                <h1 class="font-medium text-4xl" ng-bind="advisor.count_students"></h1>
                                <h1 class="font-medium text-4xl">
                                </h1>
                            </div>


                        </div>

                    </section>





                    <div class="mt-8">
                        <div class="card-header">
                            <div class="card-title">
                                My Courses
                            </div>
                        </div>
                        <div class="flex flex-col gap-4">
                                <section ng-repeat="allocation in advisor.courses">
                                    <div class="card bg-white border">
                                        <div class="flex items-center gap-1 w-full">
                                            <div class="avatar avatar-circle shrink-0"
                                                style="background-color: rgb(222, 233, 252); color: rgb(26, 37, 81); font-weight: 500;">
                                                <span
                                                    class="avatar-text" ng-bind="allocation.course.code.substring(0,1)"></span>
                                            </div>
                                            <h1 title="course-title"
                                                class="text-[--highlight-text-color] font-semibold w-full" ng-bind="allocation.course.name"></h1>
                                        </div>
                                        <div
                                            class="flex items-center gap-3 justify-between flex-wrap font-medium text-sm">
                                            <div class="flex items-center flex-wrap">
                                                <p ng-bind="allocation.course.code"></p>
                                                <div class="vertical-divider">
                                                </div>
                                                <p>{% allocation.course.units%} Units</p>
                                                <div class="vertical-divider">
                                                </div>
                                                <p ng-bind="allocation.course.option"></p>
                                            </div>
                                            <div class="flex flex-wrap items-center gap-3">
                                                
                                                <button class="btn btn-secondary" ng-click="viewCourse(allocation.course.id)"><i
                                                    class="fa fa-eye"></i> View
                                                    details</button>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                        </div>
                    </div>
                </div>




            </div>


        </div>
        <section>
            <div class="card cursor-context-menu lg:col-span-2">

                <div class="card-header">
                    <div class="card-title">Top 5 Students</div>
                </div>
                <section class="card-body grid items-start gap-2" infinite-scroll="moreStudents()">
                        <div ng-repeat="student in advisor.my_students" class="rounded-md border px-4 py-3 min-h-20">
                            <div class="flex items-center gap-1 w-full">
                                <img src="/profilepic/{%student.id%}" alt="student"
                                    class="w-7 aspect-square rounded-full object-cover" />
                                <h1
                                    class="text-[--highlight-text-color] hover:text-[--blue-500] hover:cursor-pointer font-semibold w-full" ng-bind="student.user.name"></h1>
                            </div>
                            <div class="flex mt-1 text-xs items-center justify-between">
                                <p class="font-medium text-slate-600" ng-bind="student.reg_no"></p>
                                <p class="text-slate-600">CGPA: <span class="font-bold" ng-bind="student.cgpa"></span>
                                </p>
                            </div>
                        </div>

                </section>
            </div>
        </section>
    </main>



</x-template>
