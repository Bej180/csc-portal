<x-template title="{{ $title }}" nav='courses' ng-controller="StudentCourseRegistrationController"
    ng-init="loadEnrollments()">

    <div class="columns">

        <x-route class="one-column">
            <section>

                <div ng-if="enrolled.enrollments.length > 0">

                    <x-page-header>
                        Course Registration History

                        <button type="button" class="btn btn-primary" ng-click="route('register_form')">Register
                            Courses</button>

                    </x-page-header>

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
                                    <tbody>
                                        <tr ng-repeat="enrollment in enrolled.enrollments">
                                            <td class="text-center" ng-bind="enrollment.session"></td>
                                            <td class="uppercase" ng-bind="enrollment.semester"></td>
                                            <td class="text-center" ng-bind="enrollment.level"></td>
                                            <td class="flex justify-center">


                                                <button
                                                    ng-click="viewCourseRegistrationDetails(enrollment.level, enrollment.semester, enrollment.session)"
                                                    class="text-xs btn btn-primary transition px-1 lg:px-2"
                                                    type="button">
                                                    View <span class="hidden lg:inline">Details</span>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <div ng-if="enrolled.enrollments.length === 0" id="no-courses"
                    class="h-avail flex  p-2 flex-col gap-5 justify-center items-center">
                    <img class="w-72" src="{{ asset('svg/no_courses.svg') }}" alt="no_courses_icon">
                    <div class="flex flex-col items-center gap-5 text-center">
                        <p class="text-white-800">
                            Oops! It looks like you haven't registered for any courses yet. <br>
                            Register your courses before the deadline to ensure you can view them when they become
                            available.
                        </p>

                        <a href="/course-registration">
                            <button type="button" class="btn btn-primary transition">
                                Register Courses
                            </button>
                        </a>
                    </div>
                </div>
            </section>
        </x-route>

        <x-route name="course_registration_details" class="full">
            @include('pages.student.courses.registration.registered-course-details')
        </x-route>

        <x-route name="register_form" class="full">


            <div class="place-items-center w-full grid place-content-center">
                <div>
                    <div class="py-3 flex items-center gap-1 text-bold hover:text-primary" ng-click="route('index')">

                        <i class="fa fa-chevron-left"></i>
                        <span>
                            Course Registration History
                        </span>
                    </div>
                    <form class="popup-wrapper !w-[400px] relative" ng-action="displayCourses()">
                        <div class="popup-header">
                            Course Registeration
                        </div>
                        <div class="popup-body flex flex-col gap-3">
                            <div>

                                <label for="semester" class="font-semibold">Semester</label>
                                <select placeholder="Select Semester" id="semester" ng-model="regData.semester"
                                    class="input ignore" placeholder="Select Semester">
                                    <option value="HARMATTAN">
                                        HARMATTAN</option>
                                    <option value="RAIN">RAIN</option>
                                </select>
                            </div>
                            <div>
                                <label for="session" class="font-semibold">Session</label>
                                <select placeholder="Select Session" drop="middle-center"
                                    ng-disabled="!regData.semester" id="session" ng-model="regData.session"
                                    class="input ignore">
                                    @foreach ($sessions as $session)
                                        <option value="{{ $session->name }}">{{ $session->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div>
                                <label class="font-semibold">Level</label>
                                <select placeholder="Select Level" drop="top" ng-disabled='!regData.session'
                                    ng-model="regData.level" class="input ignore">
                                    @foreach ([100, 200, 300, 400, 500] as $level)
                                        <option value="{{ $level }}">{{ $level }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="popup-footer">
                            <button type="submit" ng-disabled="!regData.level || !regData.semester || !regData.level"
                                class="btn btn-primary">Fetch Courses</button>
                        </div>
                    </form>
                </div>
            </div>
        </x-route>

        <x-route name="reg_courses" class="full">
            @include('pages.student.courses.registration.index')
        </x-route>

        
            @include('pages.student.courses.registration.borrow')
        
    </div>


</x-template>
