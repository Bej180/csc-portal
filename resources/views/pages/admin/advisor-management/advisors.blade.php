@php

    $classes = \App\Models\AcademicSet::get()->unique('name');
@endphp

<x-template nav="staffs" title="Admin - Staffs Manager" style="admin-list"  ng-controller="AdminStaffController" ng-init="boot('advisor')">

    <div class="columns">
        
                <section class="half-60">



                    <div class="card">
                        <div ng-if="staffs.length > 0"
                            class="card-header flex flex-col lg:flex-row lg:justify-between">

                            <div class="card-title">
                                
                                Staffs
                            </div>

                            <div class="flex items-center gap-3 pb-3">


                                <div class="input-group max-w-72">
                                    <input class="input" ng-model="search" placeholder="Search">
                                    <button ng-disabled="!search" ng-click="Search(search)" class="btn-primary btn-adaptive btn-icon btn" type="button">
                                        <span class="fa fa-search"></span>
                                    </button>
                                </div>

                            </div>
                        </div>



                        <div ng-if="staffs.length == 0">
                            <div class="grid place-items-center">
                                <img src="{{ asset('svg/404.svg') }}" clas="w-52" />
                                <div class="text-zinc-300 text-2xl">
                                    NO STAFF ADDED
                                </div>
                            </div>
                        </div>
                        <div ng-if="staffs.length > 0">
                            <div class="card-body md:h-[calc(100dvh-16rem)]" infinite-scroll="more()">


                                <div ng-if="initialized && staffs.length === 0"
                                    class="placeholder-glow grid lg:grid-flow-row gap-5 gap-x-5 grid-cols-1 lg:grid-cols-4 py-5">
                                    <div class="col-span-1 flex flex-col" ng-repeat="n in [1,2,3,4]">
                                        <div class="card2x h-full relative cursor-not-allowed">
                                            <div class="card2x-body flex flex-col items-center justify-end">
                                                <div class="gap-2 w-full flex lg:flex-col items-center">
                                                    <div class="w-[70px] h-[70px] placeholder rounded-full shrink-0">
                                                    </div>
                                                    <div class="w-full flex flex-col lg:items-center gap-3 mt-2">
                                                        <div class="placeholder w-32"></div>
                                                        <div class="placeholder w-20"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div ng-if="staffs.length > 0" class="grid grid-cols-1 lg:grid-cols-4 py-5 gap-5">
                                    <div ng-show="staffs" class="col-span-1 flex flex-col"
                                        ng-repeat="staff in staffs">
                                        <div class="card2x h-full relative group cursor-pointer"
                                            ng-click="showStaff(staff.id)">
                                            <div class="card2x-body flex flex-col items-center justify-end">
                                                <div class="gap-2 w-full flex lg:flex-col items-center">
                                                    <div class="student-img lg:h-24">
                                                        <img profile="staff" src="/profilepic/{%staff.id%}" class="w-24 lg:h-24 object-cover rounded-full bg-zinc-100"
                                                            alt="Staff's Image" />

                                                    </div>
                                                    <div class="w-full flex flex-col lg:items-center">
                                                        <h5 class="text-[18px] lg:text-center"> <span
                                                                ng-bind="staff.name" class="font-semibold"></span> <i
                                                                ng-show="staff.class.length > 0"
                                                                class="fa fa-copyright link text-xs"></i></h5>
                                                        <h6 ng-bind="staff.staff.designation" class="uppercase opacity-45"></h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                </div>

                            </div>

                            
                        </div>


                    </div>



                </section>
                <section class="half-40">
                    <form class="card justify-between">
                        <div class="card-header">
                            <div class="card-title">
                                Add staff
                            </div>
                        </div>

                        <div class="card-body">


                            <div class="p-2">


                                <div class="flex flex-col gap-4">

                                    <div class="md:flex gap-4">
                                        <input type="text" class="input row-auto !w-[30%] text-center"
                                            placeholder="Title" ng-model="staffData.title" />
                                        <input type="text" class="input" placeholder="Full Name"
                                            ng-model="staffData.fullname" />
                                    </div>
                                    <div class="flex flex-col">
                                        <input type="number" class="input" placeholder="Staff ID"
                                            ng-model="staffData.staff_id" />
                                    </div>
                                    <div class="flex flex-col">
                                        <input type="text" class="input" placeholder="Email Address"
                                            ng-model="staffData.email" />
                                    </div>
                                    <div class="md:flex gap-4">
                                        <input type="text" class="input mk-phonex" placeholder="Phone Number"
                                            ng-model="staffData.phone" input-mask="9999 999 9999"
                                            autocomplete="off" />
                                        <div>
                                            <label>Gender</label>
                                            <select name="gender" drop="bottom-right" class="input"
                                                ng-model="staffData.gender">
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <textarea class="input h-24" ng-model="staffData.address" placeholder="Contact Address"></textarea>

                                    </div>
                                    <div class="md:flex gap-4">

                                        <input type="date" class="input grid-span-1"
                                            ng-model="student.birthdate" />

                                        <div>
                                            <label>Academic Class</label>
                                            <select class="input" drop="top" ng-model="staffData.set_id">
                                                @foreach ($classes as $class)
                                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label>Designation</label>
                                        <select drop="top" ng-model="staffData.designation">
                                            <option value="lecturer">Lecturer</option>
                                            <option value="technologist">Technologists</option>
                                        </select>
                                    </div>

                                    <div ng-if="staffData.designation">
                                        <div class="flex mb-5">
                                            <div class="relative">
                                                <div class="paragraph !m-0">Select Course Details</div>
                                                <div class="flex gap-3">
                                                    <div class="flex-1">
                                                        <label>Semester</label>
                                                        <select drop="top" ng-model="course.semester" class='input'>
                                                            <option>HARMATTAN</option>
                                                            <option>RAIN</option>
                                                        </select>
                                                    </div>
                                                    <div class="flex-1">
                                                        <label>Level</label>
                                                        <select drop="top" ng-model="course.level" class='input'>

                                                            <option value='100'>100 Level</option>
                                                            <option value='200'>200 Level</option>
                                                            <option value='300'>300 Level</option>
                                                            <option value='400'>400 Level</option>
                                                            <option value='500'>500 Level</option>
                                                        </select>
                                                    </div>
                                                    <button type="button" ng-click="displayCoursesToBeAssigned(course)"
                                                        class="btn btn-icon btn-primary"><i
                                                            class="fa fa-search"></i></button>
                                                </div>

                                            </div>
                                        </div>
                                        <div ng-show="courses.length > 0"
                                            class="grid grid-cols-3 gap-2 justify-between">

                                            <div ng-click="toggleSelectCourse(course)" ng-repeat="course in courses"
                                                class="bg-gray-300 px-3 py-1.5 rounded-md row-span-1 text-center cursor-pointer"
                                                ng-class="{'text-white primary-bg': courseIndex(course.code) >= 0}"
                                                ng-bind="course.code">
                                            </div>
                                        </div>

                                        <div ng-show="courses.length === 0 && level && semester"
                                            class="grid grid-cols-3 gap-2 justify-between placeholder-glow !gap-y-4 place-content-center place-items-center">
                                            <div ng-repeat="course in [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]"
                                                class="placeholder w-14">
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="flex mt-2 gap-2 items-center">
                                    <input type="checkbox" id="check" required class="checkbox peer" />
                                    <label for="check" class="peer-checked:font-bold text-xs">You are verified the
                                        details provided above?</label>

                                </div>




                            </div>







                        </div>

                        <div class="card-footer flex gap-2 justify-end">
                            <button controller="createStaffAccount()" type="button"
                                values="{sending:'Creating Account...', sent: 'Created Account', error: 'Failed'}"
                                class="btn-primary">Create Staff Account
                            </button>
                        </div>

                    </form>
                </section>
       
    </div>

</x-template>
