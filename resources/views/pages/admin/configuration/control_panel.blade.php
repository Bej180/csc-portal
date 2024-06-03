<x-template>
    <x-wrapper>
        <div class="card !rounded-2xl" ng-controller="AdminControlPanelController" ng-init="initConfiguration()">
            <div class="card-body">
                <div class="card-header">
                    <div class="card-title">Settings</div>
                </div>
                <section class="md:flex md:gap-5">
                    <div class="flex-1 grid w-full overflow-y-scroll gap-3 md:h-[calc(100dvh-10.7rem)]">
                        <fieldset class="p-fieldset p-component">
                            <legend class="p-fieldset-legend">
                                <div class="flex items-center">
                                    <span class="material-symbols-rounded">school</span>
                                    <p>School Details</p>
                                </div>
                            </legend>
                            <div>
                                <div class="p-fieldset-content">

                                    <div ng-if="active_session" class="flex flex-col gap-2 justify-between">
                                        <div class="flex gap-1 items-center">
                                            <div class="text-sm lg:text-base flex flex-col">
                                                <p>Current Session
                                                </p>
                                            </div>
                                            <div class="chip p-component font-semibold" aria-label="2022-2023">
                                                <span class="fa fa-calendar">
                                                </span>
                                                <div class="chip-text" ng-bind="active_session.name"></div>
                                            </div>
                                        </div>


                                        <div class="flex gap-1 items-center">
                                            <div class="text-sm lg:text-base flex flex-col">
                                                <p>Current Semester: </p>



                                            </div>

                                            <toggle class="chip font-semibold"
                                                options="{RAIN:'fa fa-flag', HARMATTAN:'fa fa-sun'}"
                                                ng-model="active_session.active_semester"
                                                ng-change="updateActiveSemester()">
                                            </toggle>
                                            </span>
                                        </div>
                                    </div>
                                    <div ng-if="!active_session"
                                        class="text-red-500 text-center text-2xl lg:text-3xl opacity-45">
                                        <i class="fa fa-exclamation-triangle"></i> NO SESSION CREATED
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="p-fieldset p-component">
                            <legend class="p-fieldset-legend">
                                <div class="flex items-center gap-1">
                                    <span class="fa fa-calendar">
                                    </span>
                                    <p>Create New Session</p>
                                </div>
                            </legend>
                            <form values="{sending:'Submitting', sent:'Submitted'}">
                                <div class="p-fieldset-content">
                                    <div class="flex flex-col gap-2 justify-between">
                                        <div class="flex flex-col text-sm font-medium gap-1">
                                            <span>
                                                Name:
                                            </span>
                                            <input class="input mk-session" ng-model="new_session.name"
                                                placeholder="Session Name e.g. 2020/2021" maskx="9999/9999">
                                        </div>



                                        <div class="flex flex-col gap-1">
                                            <div class="flex gap-1 items-center mb-2">
                                                <div class="text-sm lg:text-base flex flex-col">
                                                    <p>Active Semester: </p>



                                                </div>
                                                <span class="chip font-semibold">
                                                    <toggle options="{HARMATTAN:'fa fa-sun', RAIN:'fa fa-flag'}"
                                                        ng-model="new_session.active_semester">
                                                    </toggle>
                                                </span>


                                            </div>

                                            <div class="flex gap-1 items-center mb-2">
                                                <div class="text-sm lg:text-base flex flex-col">
                                                    <p>Course Registration: </p>



                                                </div>
                                                <span class="chip font-semibold">
                                                    <toggle options="{OPEN: 'fa fa-lock-open', CLOSED: 'fa fa-lock'}"
                                                        ng-model="new_session.course_registration_status"></toggle>
                                                </span>


                                            </div>
                                        </div>


                                        <div class="flex flex-col">
                                            <button type="button" controller="createSession()" class="btn btn-primary">Create Session</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </fieldset>
                    </div>
                    <div class="flex-1 grid w-full overflow-y-scroll md:h-[calc(100dvh-10.7rem)]">
                        <fieldset class="p-fieldset p-component">
                            <legend class="p-fieldset-legend">
                                <div class="flex items-center gap-1">
                                    <span class="fa fa-book">
                                    </span>
                                    <p> Course Registration</p>
                                </div>
                            </legend>
                            <div class="p-toggleable-content">
                                <div class="p-fieldset-content">
                                    <div class="flex flex-col gap-2 overflow-y-auto">
                                        <div class="flex gap-1 items-center">
                                            <div class="text-sm lg:text-base flex flex-col">
                                                <p>Status
                                                </p>


                                            </div>

                                            <div class="chip p-component font-semibold" aria-label="Open">
                                                <toggle ng-change="updateCourseRegistrationStatus()"
                                                    options="{OPEN: 'fa fa-lock-open', CLOSED: 'fa fa-lock'}"
                                                    ng-model="course_registration_status"></toggle>
                                            </div>
                                        </div>





                                        <form ng-action="reOpenCourseRegistration()" class="px-2 mt-8">
                                            <div class="font-semibold mt-2">
                                                Enter the Session and Semester you want to reopen
                                            </div>
                                            <div class="flex gap-2">
                                                <div class="flex-1">
                                                    <input class="input mk-session text-center"
                                                        ng-model="reopen.session"
                                                        placeholder="Enter the Session You want to reopen" />
                                                </div>
                                                <div>
                                                    <select drop="top" placeholder="Choose Semester"
                                                        ng-model="reopen.semester">
                                                        <option>HARMATTAN</option>
                                                        <option>RAIN</option>
                                                    </select>
                                                </div>
                                                <button class="btn-icon btn-primary"><i
                                                        class="fa fa-lock-open"></i></button>
                                            </div>

                                        </form>



                                        <div ng-if="open_semesters.length > 0">
                                            <div class="!mb-0 mt-4 paragraph">Open Semesters (Click on Any to close it)
                                            </div>
                                            <div class="flex flex-col gap-3">
                                                <button
                                                    type="button"ng-repeat="(index, open_semester) in open_semesters"
                                                    class="uppercase rounded-md px-2 py-0.5 border hover:bg-red-100 hover:text-red-600 border-zinc-200 bg-zinc-100 flex justify-between items-center"
                                                    ng-click="closeSemester($event, open_semester, index)"
                                                    title="Click to Close Course Registration">
                                                    <span>{% open_semester.semester%}</span>
                                                    <span>{%open_semester.session %}</span>
                                                </button>


                                            </div>
                                        </div>
                                        <div ng-if="!open_semesters || open_semesters.length == 0"
                                            class="grid place-items-center">
                                            <div class="flex flex-col justify-between opacity-50 pt-12">
                                                <div>NO SEMESTER'S COURSE REGISTRATION IS OPEN</div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </section>
            </div>
        </div>

    </x-wrapper>
</x-template>
