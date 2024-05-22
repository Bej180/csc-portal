<x-template title="Student Enrollments" nav='student_course_reg'>



    <div class="p-5" ng-controller="AdvisorStudentController">
        <x-route name="index">
            <div class="card">
                <div class="card-header">
                    <div class="card-title !font-bold">
                        Students Course Registrations
                    </div>
                </div>
                <div class="card-body box !pt-8">
                    <div
                        class="md:h-[calc(-11rem+100dvh)] responsive-table min-w-full box-wrapper w-full overflow-x-auto">
                        <table class="">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Name</th>
                                    <th>Reg No</th>
                                    <th>Date</th>
                                    <th class="text-center">Session</th>
                                    <th>Semester</th>

                                    <th class="text-center">Level</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($enrollments as $enrollment)
                                    <tr>
                                        <th>{{ $loop->index + 1 }}</th>
                                        <th>{{ $enrollment->student->user->name }}</th>
                                        <th> {{ $enrollment->student->reg_no }}</th>
                                        <th> {{ $enrollment->created_at->format('d/m/Y') }}</th>
                                        <td class="text-center">{{ $enrollment->session }}</td>
                                        <td class="uppercase">{{ $enrollment->semester }}</td>
                                        <td class="text-center">{{ $enrollment->level }}</td>
                                        <td class="flex justify-center">

                                            <div class="flex gap-1">

                                                <button class="text-xs btn btn-primary transition"
                                                    ng-click="viewEnrollments({
                                                      student_id:'{{ $enrollment->student->id }}',
                                                      semester:'{{ $enrollment->semester }}',
                                                      level:'{{ $enrollment->level }}',
                                                      session:'{{ $enrollment->session }}'
                                                    })"
                                                    type="button">
                                                    View
                                                </button>

                                                <button class="text-xs btn btn-secondary transition" type="button">
                                                    Approve
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </x-route>
        <x-route name="reg_details">
            <div class="scrollerx" ng-if="enrolledData">

                <div class="cursor-pointer hover:text-primary flex gap-1 p-4 items-center" ng-click="route('index')">
                    <i class="fa fa-chevron-left"></i> Student Registration Details
                </div>


                <div class="grid place-items-center ">
                    <div id="registered-courses-details-container"
                        class="mt-2 border slate-400 rounded p-7 pb-10 md:flex md:flex-col md:gap-2 visible-on-print w-[800px] bg-white">
                        <div class="flex flex-col items-center">
                            <img src="{{ asset('images/futo-log.png') }}" alt="futo-logo" width="35">
                            <h1 class="text-sm font-semibold text-body-400 md:text-base xl:text-lg print:text-black">
                                FEDERAL UNIVERSITY OF TECHNOLOGY, OWERRI
                            </h1>
                            <p class="text-xs text-body-400 font-semibold md:text-sm xl:text-base print:text-black">
                                DEPARTMENT
                                OF
                                COMPUTER SCIENCE (SICT)</p>
                        </div>

                        <div class="flex gap-3 items-center mt-8 w-fit mx-auto" id="student-info">
                            <div>
                                <img src="/profilepic/{% enrolledData.student.id %}" alt="user"
                                    class="rounded-full w-16 lg:w-24 xl:w-28" />
                            </div>

                            <div
                                class="flex-1 text-[.78rem] gap-4 text-body-800 items-center whitespace-nowrap md:text-sm print:text-black">

                                <div class="grid grid-cols-4 gap-3">
                                    <div class="col-span-1">Full Name:</div>
                                    <div class="col-span-1 uppercase font-semibold"
                                        ng-bind="enrolledData.student.user.name"></div>
                                    <div class="col-span-1">Registration Number:</div>
                                    <div class="col-span-1 uppercase font-semibold"
                                        ng-bind="enrolledData.student.reg_no"></div>
                                </div>

                                <div class="grid grid-cols-4 gap-3">
                                    <div class="col-span-1">School:</div>
                                    <div class="col-span-1 uppercase font-semibold">SICT</div>
                                    <div class="col-span-1">Department:</div>
                                    <div class="col-span-1 uppercase font-semibold">Computer Science</div>
                                </div>

                                <div class="grid grid-cols-4 gap-3">
                                    <div class="col-span-1">Entry Mode:</div>
                                    <div class="col-span-1 uppercase font-semibold">UTME</div>
                                    <div class="col-span-1">Level:</div>
                                    <div class="col-span-1 uppercase font-semibold" ng-bind="enrolledData.level"></div>
                                </div>

                                <div class="grid grid-cols-4 gap-3">
                                    <div class="col-span-1">Session:</div>
                                    <div class="col-span-1 uppercase font-semibold" ng-bind="enrolledData.session">
                                    </div>
                                    <div class="col-span-1">Semester:</div>
                                    <div class="col-span-1 uppercase font-semibold" ng-bind="enrolledData.semester">
                                    </div>
                                </div><!--end-->



                            </div>
                        </div>

                        <div class="mt-4 responsive-table">
                            <table class="mx-auto print:text-black !w-[400px] min-w-[90%]">
                                <thead class="print:text-black">
                                    <th class="!w-24">Code</th>
                                    <th class="!w-36">Title</th>
                                    <th class="w-10">Units</th>
                                    <th class="!w-32">Type</th>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="enrollment in enrolledData.enrollments">

                                        <td class="!w-[80px]" ng-bind="enrollment.course.code"></td>
                                        <td class="!text-left" ng-bind="enrollment.course.name"></td>
                                        <td ng-bind="enrollment.course.units"></td>
                                        <td class="uppercase" ng-bind="enrollment.course.option"></td>
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <td class="uppercase">Total</td>
                                        <td ng-bind="enrolledData.total"></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="flex justify-end">
                            <button type="button" class="btn btn-primary"><i class="fa fa-check"></i> Approve</button>
                        </div>
                    </div>
                </div>

            </div>
        </x-route>
    </div>



</x-template>
