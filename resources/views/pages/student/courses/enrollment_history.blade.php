<x-template title="Course Enrollment History" nav='courses' controller="StudentCourseRegistrationController"
    ng-init="loadEnrollments()">            

    <div class="columns">

        <x-route class="one-column" name="index">
            <section>

                <div ng-cloak ng-if="loaded && enrollments">

                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-1 text-2xl">
                            <span>Course Registration History</span>
                        </div>

                        <button type="button" class="btn btn-primary" ng-click="displayCourseRegistrationForm()">Register
                            Courses</button>

                    </div>

                    <div class="mt-4">
                        <div class="box">
                            <div class="box-wrapper w-full overflox-x-auto responsive-table min-w-full no-zebra">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="text-center">Session</th>
                                            <th>Semester</th>
                                            <th class="text-center">Level</th>
                                            <th class="!text-center">Total</th>

                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="enrollment in enrollments">
                                            <td class="text-center" ng-bind="enrollment.session"></td>
                                            <td class="uppercase" ng-bind="enrollment.semester"></td>
                                            <td class="text-center" ng-bind="enrollment.level"></td>
                                            <td class="!text-center" ng-bind="enrollment.courses.length"></td>
                                            <td class="flex justify-center">


                                                <button
                                                    controller="showCourseRegistrationDetails(enrollment)"
                                                    class="whitespace-nowrap flex gap-1 text-xs btn btn-primary transition px-1 lg:px-2"
                                                    type="button">
                                                    <x-icon name="visibility"/>
                                                    <label>View Details</label>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <div ng-if="loaded && !enrollments" ng-cloak id="no-courses"
                    class="h-avail flex  p-2 flex-col gap-5 justify-center items-center">
                    <img class="w-72" src="{{ asset('svg/no_courses.svg') }}" alt="no_courses_icon">
                    <div class="flex flex-col items-center gap-5 text-center">
                        <p class="text-white-800">
                            Oops! It looks like you haven't registered for any courses yet. <br>
                            Register your courses before the deadline to ensure you can view them when they become
                            available.
                        </p>

                        <button ng-click="displayCourseRegistrationForm()" type="button" class="btn btn-primary transition">
                            Register Courses
                        </button>
                    </div>
                </div>
                <div ng-if="!loaded">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-1">
                            <span>Course Registration History</span>
                        </div>

                        <button type="button" class="btn btn-primary" disabled>Register
                            Courses</button>

                    </div>

                    <div class="mt-4">
                        <div class="box">
                            <div class="box-wrapper w-full overflox-x-auto responsive-table min-w-full no-zebra">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="text-center">Session</th>
                                            <th>Semester</th>
                                            <th class="text-center">Level</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody class="placeholder-glow h-full overflow-hidden">
                                        <tr ng-repeat="item in range(1, 5)">
                                            <td class="text-center"><div class="placeholder my-2 w-28"></div></td>
                                            <td class="uppercase"><div class="placeholder my-2 w-24"></div></td>
                                            <td class="text-center"><div class="placeholder my-2 w-20"></div></td>
                                            <td class="flex justify-center">

                                                <div class="placeholder my-2 w-16 h-8 rounded-md"></div>
                                               
                                            </td>
                                        </tr>
                                       
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                   
                </div>
            </section>
        </x-route>



        <x-route name="enrollment_details" class="full">
            @include('pages.student.courses.registered-course-details')
        </x-route>

        <x-route name="register_form" class="full">


            <div class="place-items-center w-full grid place-content-center">
                <div>
                    <div class="py-3 flex items-center gap-1 text-bold hover:text-primary" ng-click="gotoIndex()">

                        <x-icon name="chevron_left"/>
                        <span>
                            Course Registration History
                        </span>
                    </div>
                    <form class="popup-wrapper !w-[400px] relative">
                        <div class="popup-header">
                            Course Registeration
                        </div>
                        <div class="popup-body flex flex-col gap-3">
                            <div>

                                <label for="semester" class="font-semibold">Semester</label>
                                <select placeholder="Select Semester" id="semester" ng-model="regData.semester"
                                    class="input" placeholder="Select Semester">
                                    <option>HARMATTAN</option>
                                    <option>RAIN</option>
                                </select>
                            </div>
                            <div>
                                <label for="session" class="font-semibold">Session</label>
                                <select placeholder="Select Session" drop="middle-center"
                                    ng-disabled="!regData.semester" id="session" ng-model="regData.session"
                                    class="input">
                                    @foreach ($sessions as $session)
                                        <option value="{{ $session->name }}">{{ $session->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div>
                                <label class="font-semibold">Level</label>
                                <select placeholder="Select Level" drop="top" ng-disabled='!regData.session'
                                    ng-model="regData.level" class="input">
                                    @foreach ([100, 200, 300, 400, 500] as $level)
                                        <option value="{{ $level }}">{{ $level }} Level</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="popup-footer">
                            <button type="button" controller="displayCourses()" ng-disabled="!regData.level || !regData.semester || !regData.level"
                                class="btn btn-primary w-full">Proceed</button>
                        </div>
                    </form>
                </div>
            </div>
        </x-route>

        <x-route name="reg_courses" class="full">
            @include('pages.student.courses.index')
        </x-route>

        
            @include('pages.student.courses.borrow')
        
    </div>


</x-template>
