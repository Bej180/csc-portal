@php

    $classes = \App\Models\AcademicSet::get()->unique('name');
@endphp
<x-template nav="students" title="Admin - Students Manager" style="admin-list" controller="AdminStudentController"
    ng-init="bootStudentAccounts()">

    <div class="columns">

        <section class="half-60">

            <div class="card">

                <div ng-if="!loaded" class="placeholder-glow grid lg:grid-flow-row gap-5 grid-cols-1 lg:grid-cols-4 md:!mt-8">
                    <div class="col-span-1 flex flex-col" ng-repeat="n in [1,2,3,4]">
                        <div class=" h-full relative cursor-not-allowed">
                            <div class="flex flex-col items-center justify-end">
                                <div class="gap-2 w-full flex lg:flex-col items-center">
                                    <div class="w-[70px] h-[70px] placeholder rounded-full shrink-0">
                                    </div>
                                    <div class="w-full flex flex-col lg:items-center gap-3">
                                        <div class="placeholder w-36"></div>
                                        <div class="placeholder w-28"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div ng-cloak ng-if="loaded && students.length === 0" class="card-body">
                    <div class="grid place-items-center">
                        <img src="{{ asset('svg/404.svg') }}" class="w-52" />
                        <div class="text-zinc-200">
                            NO STUDENT ADDED
                        </div>
                    </div>
                </div>
                <div ng-cloak ng-if="loaded && students.length > 0">
                    <div class="card-header flex md:!flex-row items-center justify-between">
                        <div class="page-title">
                            Students
                        </div>
                        <div>
                            <form class="input-groupx flex gap-2">



                                <select ng-model="sorting.attr" ng-change="sortStudent()" placeholder="Sort by">
                                    <option value="name">Name</option>
                                    <option value='cgpa'>CGPA</option>
                                    <option value='reg_no'>Reg Number</option>
                                    <option value="level">Level</option>
                                </select>

                                <div class="flex justify-content-center" ng-init="sort=false">
                                    <toggle ng-model="sorting.order" ng-change="sortStudent()"
                                        options="{ASC:'fa fa-sort-alpha-up', DESC:'fa fa-sort-alpha-down'}"
                                        class="btn btn-primary" type="button" aria-haspopup="true"
                                        ng-click="sort=!sort" aria-controls="overlay_menu"
                                        ng-class="{'btn-primary':sort, 'btn-secondary':!sort}">
                                        <i class="fa fa-sort icon"></i>
                                    </toggle>
                                </div>


                                <input type="input flex-none" class="input !w-32" placeholder="Search Student"
                                    ng-model="searchinput" />

                                <div class="search-student-btn">
                                    <button type="button" ng-click="searchStudent(searchinput)"
                                        class="btn btn-primary btn-icon"><i class="fa fa-search"></i></button>

                                </div>

                            </form>

                        </div>
                    </div>



                    <div class="card-body" infinite-scroll="more()">

                        <div class="p-5">

                            

                            <div class="grid lg:grid-flow-row gap-5 gap-x-5 gap-y-8 grid-cols-1 lg:grid-cols-4">
                                <div ng-show="students" class="col-span-1 flex flex-col"
                                    ng-repeat="account in students">
                                    <div class="cardx h-full relative group cursor-pointer"
                                        ng-click="showStudent(account)">
                                        <div class="cardx-body flex flex-col items-center justify-end">
                                            <div class="gap-2 w-full flex lg:flex-col items-center">
                                                <div class="student-img lg:h-24">
                                                    <avatar user="account"
                                                        class="w-24 lg:h-24 object-cover rounded-full"
                                                        alt="Students Info"></avatar>
                                                </div>
                                                <div class="w-full flex flex-col lg:items-center">
                                                    <h5 class="text-[18px] lg:text-center font-semibold"
                                                        ng-bind="account.name">
                                                    </h5>

                                                    <div class="text-sm" ng-bind="account.student.reg_no"></div>
                                                    <div class="text-xs">
                                                        <span
                                                            class="pr-2 border-r border-slate-500/50">{% account.level %}
                                                            Level</span><span class="pl-2">{% account.cgpa %}
                                                            CGPA</span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>

                </div>

                

            </div>

            @include('pages.admin.student-management.profile')


        </section>

        <section class="half-40">
            <form class="card !p-6 gap-3" ng-action="createStudentAccount()">

                <div class="card-header">
                    <div class="card-title">
                        New Student Account
                    </div>
                </div>

                <div class="card-body">

                    <div class="pt-5">


                        <div class="flex flex-col gap-4">

                            <div class="md:flex gap-4">
                                <input type="text" class="input row-auto md:w-[55%]" placeholder="Surname"
                                    ng-model="student.surname" />
                                <input type="text" class="input" placeholder="Other Name"
                                    ng-model="student.othernames" />
                            </div>
                            <div class="flex flex-col">
                                <input type="text" class="input mk-reg_no" placeholder="Reg Number"
                                    ng-model="student.reg_no" maxlength="11" />
                            </div>
                            <div class="flex flex-col">
                                <input type="text" class="input" placeholder="Email Address"
                                    ng-model="student.email" />
                            </div>
                            <div class="md:flex gap-4">
                                <input type="text" class="input mk-phonex" placeholder="Phone Number"
                                    ng-model="student.phone" input-mask="9999 999 9999" autocomplete="off" />
                                <div>
                                    <label>Gender</label>
                                    <select name="gender" drop="bottom-right" class="input"
                                        ng-model="student.gender">
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <textarea class="input h-24" ng-model="student.address" placeholder="Contact Address"></textarea>

                            </div>
                            <div class="md:flex gap-4">

                                <input type="date" class="input grid-span-1" ng-model="student.birthdate" />

                                <div>
                                    <label>Academic Class</label>
                                    <select class="input" drop="top" ng-model="student.set_id">
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>





                    </div>
                </div>

                <div class="card-footer flex gap-2 justify-end">
                    <button ng-disabledx="!student.set_id" type="button" class="btn-primary" ng-submit>Create
                        Student Account</button>
                </div>










            </form>
        </section>



    </div>
</x-template>
