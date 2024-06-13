

    <div class="hide-on-print">
        <div class="flex items-center justify-between">
            <span class="text-2xl hover:text-primary font-bold" ng-click="route('index')">
                <i class="fa fa-chevron-left"></i> Course Registration Details
            </span>

            <printer></printer>

        </div>
    </div>

    <div class="grid place-items-center">
        <div id="registered-courses-details-container"
            class="mt-2 border slate-400 rounded p-7 pb-10 md:flex md:flex-col md:gap-2 visible-on-print max-w-[800px] bg-white">
            <div class="flex flex-col items-center">
                <img src="{{ asset('images/futo-log.png') }}" alt="futo-logo" class="w-20" />
                <h1 class="text-sm font-semibold text-body-400 md:text-base xl:text-lg print:text-black">
                    FEDERAL UNIVERSITY OF TECHNOLOGY, OWERRI
                </h1>
                <p class="text-xs text-body-400 font-semibold md:text-sm xl:text-base print:text-black">DEPARTMENT
                    OF
                    COMPUTER SCIENCE (SICT)</p>
            </div>

            <div class="flex gap-3 items-center mt-8 w-fit" id="student-info">
                <div>
                    <img src="/profilepic/{% course_reg.user.id %}" alt="user"
                        class="rounded-full w-14 lg:w-16 xl:w-20 aspect-square" />
                </div>


                <div
                    class="flex-1 text-[.78rem] gap-4 text-body-800 items-center whitespace-nowrap md:text-sm print:text-black">

                    <div class="w-full flex flex-col">
                        <div class="grid grid-cols-2 gap-1">
                            <div class="col-span-1 flex gap-2">
                                <div class="w-[90px]">Full Name:</div>
                                <div class="flex-1 uppercase font-semibold" ng-bind="course_reg.user.name"></div>
                            </div>

                            <div class="col-span-1 flex gap-2">
                                <div class="w-[90px]">School:</div>
                                <div class="flex-1 uppercase font-semibold">SICT</div>
                            </div>

                            
                            
                        </div>

                        <div class="grid grid-cols-2 gap-1">
                            

                            <div class="col-span-1 flex gap-2">
                                <div class="w-[90px]">Reg. No.:</div>
                                <div class="flex-1 uppercase font-semibold" ng-bind="course_reg.student.reg_no"></div>
                            </div>
                            
                            <div class="col-span-1 flex gap-2">
                                <div class="w-[90px]">Department:</div>
                                <div class="flex-1 uppercase font-semibold">Computer Science</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-1">
                            <div class="col-span-1 flex gap-2">
                                <div class="w-[90px]">Entry Mode:</div>
                                <div class="flex-1 uppercase font-semibold">UTME</div>
                            </div>
                            <div class="col-span-1 flex gap-2">
                                <div class="w-[90px]">Level:</div>
                                <div class="flex-1 uppercase font-semibold" ng-bind="course_reg.level"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-1">
                        
                            <div class="col-span-1 flex gap-2">
                                <div class="w-[90px]">Session:</div>
                                <div class="flex-1 uppercase font-semibold" ng-bind="course_reg.session"></div>
                            </div>
                            <div class="col-span-1 flex gap-2">
                                <div class="w-[90px]">Semester:</div>
                                <div class="flex-1 uppercase font-semibold" ng-bind="course_reg.semester"></div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="w-full">
                        <div class="grid grid-cols-4 gap-1">
                            <div class="col-span-1">Full Name:</div>
                            <div class="col-span-1 uppercase font-semibold" ng-bind="course_reg.user.name"></div>
                            <div class="col-span-1">School:</div>
                            <div class="col-span-1 uppercase font-semibold">SICT</div>
                        </div>

                        <div class="grid grid-cols-4 gap-1">
                            <div class="col-span-1">Registration Number:</div>
                            <div class="col-span-1 uppercase font-semibold" ng-bind="course_reg.student.reg_no"></div>
                            <div class="col-span-1">Department:</div>
                            <div class="col-span-1 uppercase font-semibold">Computer Science</div>
                        </div>

                        <div class="grid grid-cols-4 gap-1">
                            <div class="col-span-1">Entry Mode:</div>
                            <div class="col-span-1 uppercase font-semibold">UTME</div>
                            <div class="col-span-1">Level:</div>
                            <div class="col-span-1 uppercase font-semibold" ng-bind="course_reg.level"></div>
                        </div>

                        <div class="grid grid-cols-4 gap-1">
                            <div class="col-span-1">Session:</div>
                            <div class="col-span-1 uppercase font-semibold" ng-bind="course_reg.session"></div>
                            <div class="col-span-1">Semester:</div>
                            <div class="col-span-1 uppercase font-semibold" ng-bind="course_reg.semester"></div>
                        </div>
                    </div> --}}



                </div>
            </div>

            <div class="mt-4 responsive-table text-sm">
                <table class="mx-auto print:text-black !w-[400px] min-w-[90%]">
                    <thead class="print:text-black">
                        <th class="!w-24">Code</th>
                        <th class="!w-36">Title</th>
                        <th class="w-10">Units</th>
                        <th class="!w-32">Type</th>
                    </thead>
                    <tbody>
                        <tr ng-repeat="enrollment in course_reg.enrollments">
                            <td class="!w-[80px]" ng-bind="enrollment.course.code"></td>
                            <td class="!text-left" ng-bind="enrollment.course.name"></td>
                            <td ng-bind="enrollment.course.units"></td>
                            <td class="uppercase" ng-bind="enrollment.course.option"></td>
                        </tr>

                        <tr>
                            <td></td>
                            <td class="uppercase">Total</td>
                            <td ng-bind="course_reg.totalUnits"></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div
                class="mt-20 grid grid-cols-2 gap-x-4 gap-y-16 text-[.8rem]
                md:w-[80%] md:self-center md:gap-x-12 md:text-sm
                lg:w-[60%]">
                <div
                    class="text-body-400 print:text-black font-semibold p-1 border-t-2 border-t-[var(--body-400)] border-dashed text-center">
                    Student's Signature
                </div>
                <div
                    class="text-body-400 print:text-black font-semibold p-1 border-t-2  border-t-[var(--body-400)]  border-dashed text-center">
                    Date
                </div>
                <div
                    class="text-body-400 print:text-black font-semibold p-1 border-t-2  border-t-[var(--body-400)]  border-dashed text-center">
                    Advisor's Signature
                </div>
                <div
                    class="text-body-400  print:text-black  font-semibold p-1 border-t-2  border-t-[var(--body-400)]  border-dashed text-center">
                    HOD's Signature
                </div>
            </div>
        </div>
    </div>


    <style>
        @media print {
            #registered-courses-details-container {
                border: none;
                padding: 0px;
            }

            #user-info {
                flex-direction: row;
            }

            body {
                background: #fff;
            }

            td,
            th {
                border: none !important;
            }
        }
    </style>
