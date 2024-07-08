<x-template nav="classes" ng-init="initPage()" controller="AdvisorClassController">
    <x-route name="index" class="half">
        <div class="half-40">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        My Classes
                    </div>
                </div>

                <div class="card-body">
                    <div class="flex flex-col gap-4 mt-3">

                        <div class="panel" ng-click="loadClassOnPanel(set)"
                            ng-class="{'panel-active':isActivePanel(set)}" ng-repeat="set in classes">
                            <div class="panel-header">
                                <div class="flex items-center gap-2 flex-wrap">

                                    <div class="mr-2 label avatar avatar-lg">

                                        <span class="p-avatar-text" ng-bind="set.start_year"></span>

                                    </div>
                                    <div>
                                        <span class="font-bold md:text-lg">CSC <span class="font-bold"
                                                ng-bind="set.name"></span></span>

                                        <div class="flex items-center">
                                            {% set.students.length %} students
                                            <span class="vertical-divider"></span>
                                            <span class="text-sm">
                                                <span class="status-indicator"
                                                    ng-class="{'status-active':set.is_active}"></span>
                                                {% set.is_active? 'Active' : 'Inactive' %}
                                            </span>
                                        </div>
                                    </div>


                                </div>
                            </div>

                        </div>
                    </div>



                </div>
            </div>
        </div>
        <section>
            <div class="card">
                <div class="card-header">
                    <div class="card-title lg:!text-3xl lg:py-5 text-primary">
                        CSC {% active_class.name %} Class List
                    </div>

                </div>



                <div class="card-body">
                    <div class="flex justify-between items-center">

                    <form class="input-group !w-80">
                        <select ng-model="sort" placeholder="Sort By">
                            <option value="cgpa">CGPA</option>
                            <option value="name">Name</option>
                            <option value="reg_no">Reg No</option>
                        </select>
                        <input type="search" ng-model="query" class="input" ng-keyup="search()"
                            placeholder="Enter Student Name or Reg No" ng-keydown="keyDown()" />

                        <button type="button" class="btn-icon btn btn-primary"><i class="fa fa-search"></i></button>


                    </form>
                    <button type="button" ng-click="addStudents()" class="btn btn-primary"><i class="fa fa-user-plus"></i> Add Students</button>
                </div>
                    <div class="py-4">

                        <div ng-if="invitationLink" class="bg-white dark:bg-zinc-800 flex flex-col gap-1 p-3 rounded-md">


                            <div class="flex gap-2">
                                <span class="text-slate-400">Invitation Link:</span>
                                <div class="flex-1">
                                <input ng-model="invitationLink" id="invitation-link"
                                    class="w-full focus:outline-none focus:text-primary bg-transparent border-none text-xs italic" />
                                </div>
                            </div>
                            <div class="flex gap-2 mt-2">
                                <button class="link" href="javascript:;" data-clipboard-action="copy"
                                    data-clipboard-target="#invitation-link"><i class="far fa-copy"></i> Copy</button>
                                <button class="link" controller="withdrawInviteLink()"><i class="fas fa-cut"></i>
                                    Withdraw</button>
                                <button class="link" controller="generateInviteLink('regenerate')"><i
                                        class="fa fa-undo"></i> Regenerate</button>
                            </div>



                        </div>

                        <div ng-if="!invitationLink" class="flex justify-end">
                            <button type="button" controller="generateInviteLink('generate')"
                                class="btn btn-primary">Generate Invitation Link</button>
                        </div>
                    </div>

                    <div class="font-bold text-2xl flex justify-between">
                        <div>
                            Students <span class="text-primary">{% active_class.students.length %}</span>
                        </div>
                        <div>
                            <menu drop="right" name="option" ng-model="option"
                                change="get_courses(this, courses, $index)" placeholder="Sort By">
                                <item value="cgpa">CGPA</item>
                                <item value="name">Name</item>
                                <item value="reg_no">Reg No</item>
                            </menu>
                        </div>
                    </div>
                    <div class="student-list" ng-show="!typing && !query">

                        <div ng-repeat="student in active_class.students" ng-click="displayStudent(student)"
                            class="student">
                            <avatar :user="student" alt="student_pic" class="w-16 h-16 rounded-md object-cover">
                            </avatar>
                            <div class="flex-1">
                                <div class="font-2xl font-bold" ng-bind="student.user.name"></div>
                                <div class="text-sm" ng-bind="student.reg_no"></div>
                                <div class=" text-xs">

                                    <span class="pr-2 border-r border-slate-500/50">{% student.level %}
                                        Level</span><span class="pl-2">{% student.cgpa %}
                                        CGPA</span>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('pages.advisor.class-management.student_profile')
        </section>
    </x-route>

    @include('pages.advisor.class-management.add-student')
    @include('pages.advisor.class-management.student_transcript')



    {{-- <script src="{{ asset('plugins/clipboard/clipboard.min.js') }}"></script> --}}

</x-template>
