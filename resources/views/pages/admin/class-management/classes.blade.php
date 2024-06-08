<x-template nav="classes" controller="AdminClassController">



    <main class="columns" ng-controller="AdminClassController" ng-init="loadClasses()">

        <section class="half-60">

            <div class="card">
                <div ng-if="classes.length > 0" class="card-header flex flex-col lg:flex-row lg:justify-between">
                    <div class="card-title">
                        Classes
                    </div>
                    <div class="flex items-center gap-3 pb-3">

                        <select placeholder="Search By" ng-model="searchby">
                            <option value="class_name">Class Name</option>
                            <option value="class_id">Class Id</option>
                            <option value="advisor_id">Advisor Id</option>
                            <optin value="Admission Year">Admission Year</optin>
                        </select>

                        <div class="input-group max-w-72">
                            <input class="input" placeholder="Search">
                            <button class="btn-primary btn-icon btn" type="button">
                                <span class="fa fa-search"></span>
                            </button>
                        </div>

                    </div>
                </div>

                <div class="card-body"
                    ng-class="{'flex items-center justify-center h-full':initiated && classes.length == 0}">

                    <div class="flex flex-col gap-4" ng-class="{'items-center': initiated && classes.length == 0}">


                        <div ng-if="classes.length > 0" ng-repeat="class in classes" class="panel cursor-context-menu"
                            ng-click="displayClass(class)">

                            <div class="panel-header">

                                <div class="flex items-center gap-2 flex-wrap">

                                    <div class="mr-2 label avatar avatar-lg">

                                        <span class="p-avatar-text" ng-bind="class.start_year"></span>

                                    </div>

                                    <span class="font-bold md:text-lg" ng-bind="class.name"></span>

                                </div>

                                <div class="p-panel-icons">

                                    <div class="flex items-center gap-2 flex-nowrap">

                                        <button class="btn-icon rounded" type="button" title="Edit class details">
                                            <span class="p-button-icon pi pi-file-edit">
                                            </span>
                                            <span class="p-button-label">&nbsp;
                                            </span>
                                        </button>
                                        <button class="btn-icon hover:btn-danger rounded-full" type="button"
                                            title="Delete class">
                                            <span class="fa fa-trash">
                                            </span>
                                        </button>

                                    </div>



                                </div>

                            </div>

                            <div>

                                <div class="panel-body">

                                    <div class="flex items-center flex-wrap gap-2 justify-between text-sm">


                                        <p class="w-full text-base">

                                        <div ng-if="class.advisor">
                                            Advisor:
                                            <span class="font-bold" ng-bind="class.advisor.user.name"></span>
                                        </div>
                                        <div ng-if="!class.advisor">

                                            No Class Advisor

                                        </div>

                                        </p>

                                        <div class="chip">

                                            <p>Enrolment Year:
                                                <span class="font-bold" ng-bind="class.start_year"></span>
                                            </p>

                                        </div>

                                        <div class="chip">

                                            <p>Students:
                                                <span class="font-bold" ng-bind="class.students.length"></span>
                                            </p>

                                        </div>

                                        <div class="chip">

                                            <p>Status:
                                                <span class="font-bold"
                                                    ng-bind="class.inactive?'Inactive':'Active'"></span>
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div ng-if="!initiated && classes.length == 0" ng-repeat="n in [1, 2, 3]"
                            class="placeholder-glow panel p-4">
                            <div class="panel-header">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <div class="h-12 w-12 mr-2 rounded-full placeholder"></div>
                                    <span class="placeholder w-32"></span>
                                </div>
                            </div>
                            <div>
                                <div class="panel-body">
                                    <div class="flex items-center flex-wrap gap-2 justify-between text-sm">
                                        <p class="placeholder w-36"></p>
                                        <div class="placeholder w-36"></div>
                                        <div class="placeholder w-36"></div>
                                        <div class="placeholder w-36"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div ng-if="initiated && classes.length == 0"
                            class="flex flex-col gap-2 justify-center items-center">
                            <img src="{{ asset('svg/no_courses.svg') }}" class="w-52 md:w-56 lg:w-58" alt="">
                            <h4 class="paragraph text-center text-3xl"><i class="fa fa-exclamation-triangle"></i> NO
                                CLASS HAS BEEN ADDED YET</h4>
                        </div>

                    </div>
                </div>
            </div>



        </section>

        <section class="half-40">

            <div class="card gap-2 !justify-start">
                <div class="card-header">
                    <div class="card-title">
                        New Class
                    </div>
                </div>

                <div class="card-body">


                    <form class="flex flex-col gap-3">

                        <p class="text-red-500 font-medium text-sm mt-4">Required Fields
                        </p>

                        <div class="flex flex-col gap-1 mt-2">
                            <label class="label" for="className">Class Name
                            </label>
                            <input class="input mk-session" mask="9999/9999" ng-model="createData.name"
                                id="className" placeholder="Class Name">

                        </div>

                        <div class="flex flex-col gap-1 mt-2">
                            <label class="label" for="enrolmentYear">Year of Enrolment
                            </label>
                            <input class="input mk-4" id="enrolmentYear" ng-model="createData.start_year"
                                placeholder="Year of Enrolment">
                        </div>

                        <div class="flex flex-col gap-1 mt-2">
                            <label class="label" for="currentLevel">Current Level
                            </label>
                            <input class="input mk-3" id="currentLevel" placeholder="Current Level">
                        </div>


                        <div class="flex flex-col gap-1 mt-2">
                            <label class="label ignore" for="classAdvisor">Class Advisor</label>
                            <select drop="top" class="input" placeholder="Select an Advisor"
                                ng-model="createData.advisor_id">
                                @foreach ($staffs as $staff)
                                    <option value="{{ $staff->id }}">{{ $staff->user->name }}</option>
                                @endforeach
                            </select>

                        </div>
                        <button type="button" controller="createClass()" class="btn btn-primary">Add Class</button>


                    </form>



                </div>

            </div>

        </section>

        @include('pages.admin.class-management.view-class')


    </main>
</x-template>
