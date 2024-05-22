@php
    $courses = \App\Models\Course::getAllCourses();
@endphp

<x-popend title="Add Staff" name="addStaff">
    <form>
        @csrf

        <div class="my-5 font-semibold text-center">
            New Staff Account
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-bottom nav-justified">
                <li class="nav-item"><a id="nextTab" class="nav-link show active" href="#course_allocation"
                        data-bs-toggle="tab">Course Allocation</a></li>
                <li class="nav-item"><a class="nav-link" href="#personal_details" data-bs-toggle="tab">Personal
                        Details</a></li>

            </ul>
            <div class="tab-content pt-5" ng-controller="AddStaffController">

                <div class="tab-pane active" id="course_allocation">
                    <div>
                        <div class="flex mb-5">
                            <div class="relative">
                                <div class="paragraph">Select Course Details</div>
                                <div class="flex gap-3">
                                    <div class="flex-1">
                                        <select ng-model="semester" ng-change="loadCourses()" class='input'>
                                            <option value=''>--Semester--</option>
                                            <option value='HARMATTAN'>Harmattan</option>
                                            <option value='RAIN'>Rain</option>
                                        </select>
                                    </div>
                                    <div class="flex-1">
                                        <select ng-model="level" ng-change="loadCourses()" class='input'>
                                            <option value=''>--Level--</option>
                                            <option value='100'>100 Level</option>
                                            <option value='200'>200 Level</option>
                                            <option value='300'>300 Level</option>
                                            <option value='400'>400 Level</option>
                                            <option value='500'>500 Level</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div ng-show="courses.length > 0" class="grid grid-cols-3 gap-2 justify-between">

                            <div ng-click="toggleSelectCourse(course)" ng-repeat="course in courses"
                                class="bg-gray-300 px-3 py-1.5 rounded-md row-span-1 text-center cursor-pointer"
                                ng-class="{'text-white primary-bg': courseIndex(course.code) >= 0}"
                                ng-bind="course.code">
                            </div>
                        </div>

                        <div ng-show="courses.length === 0 && level && semester"
                            class="grid grid-cols-3 gap-2 justify-between placeholder-glow !gap-y-4 place-content-center place-items-center">
                            <div ng-repeat="course in [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]" class="placeholder w-14">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="personal_details">
                    <div class="lg:grid grid-cols-2 gap-4">

                        <input type="text" class="input row-auto" placeholder="First Name"
                            ng-model="staffData.firstname" />
                        <input type="text" class="input" placeholder="Last Name" ng-model="staffData.lastname" />
                        <input type="text" class="input" placeholder="Middle Name" ng-model="staffData.middlename" />
                        <input type="text" class="input" placeholder="Phone Number" ng-model="staffData.phone" />
                        <input type="text" class="input" placeholder="Email Address" ng-model="staffData.email" />
                        <select name="gender" class="input" ng-model="staffData.gender">
                            <option value="">Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="lg:grid grid-cols-2 gap-3 mt-4 ">
                        <input type="text" class="input w-full" ng-model="staffData.address"
                            placeholder="Contact Address" />

                        <input type="date" class="input grid-span-1" ng-model="staffData.birthdate" />

                    </div>

                    <div class="flex gap-2 mt-5 justify-end popup-footer">
                        <submit submit="create_staff_account" values="{sending:'Creating Account...', sent: 'Created Account', error: 'Failed'}" state="buttonStates.create_staff_account" class="btn-primary" ng-click="createStaffAccount()" value="Create Staff Account"></submit>
                    </div>

                </div>





            </div>
        </div>










    </form>
</x-popend>
