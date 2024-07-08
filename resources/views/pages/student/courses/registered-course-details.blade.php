<div ng-show="enrollment_details">

    <div class="hide-on-print">
        <div class="flex items-center justify-between">
            <span class="text-2xl hover:text-primary font-bold" ng-click="gotoIndex()">
                <i class="fa fa-chevron-left"></i> Course Registration Details
            </span>

            <printer></printer>

        </div>
    </div>

    <div class="grid place-items-center">
        <div id="registered-courses-details-container"
            class="mt-2 border slate-400 rounded p-7 pb-10 md:flex md:flex-col md:gap-2 visible-on-print max-w-[800px] bg-white dark:bg-black print:!bg-white dark:border-none">
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
                   
                        <avatar user="account" alt="user"
                        class="rounded-full w-14 lg:w-16 xl:w-20 aspect-square"></avatar>
                </div>


                <div
                    class="flex-1 text-[.78rem] gap-4 text-body-800 items-center whitespace-nowrap md:text-sm print:text-black">

                    <div class="w-full flex flex-col">
                        <div class="grid grid-cols-2 gap-1">
                            <div class="col-span-1 flex gap-2">
                                <div class="w-[90px] shrink-0">Full Name:</div>
                                <div class="flex-1 uppercase font-semibold break-words whitespace-break-spaces" ng-bind="account.name"></div>
                            </div>

                            <div class="col-span-1 flex gap-2">
                                <div class="w-[90px] shrink-0">Session:</div>
                                <div class="flex-1 uppercase font-semibold" ng-bind="enrollment_details.session"></div>
                            </div>

                            
                            
                        </div>

                        <div class="grid grid-cols-2 gap-1">
                            

                            <div class="col-span-1 flex gap-2">
                                <div class="w-[90px] shrink-0">Reg. No.:</div>
                                <div class="flex-1 uppercase font-semibold" ng-bind="account.reg_no"></div>
                            </div>
                            
                            <div class="col-span-1 flex gap-2">
                                <div class="w-[90px] shrink-0">Semester:</div>
                                <div class="flex-1 uppercase font-semibold" ng-bind="enrollment_details.semester"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-1">
                            <div class="col-span-1 flex gap-2">
                                <div class="w-[90px] shrink-0">Entry Mode:</div>
                                <div class="flex-1 uppercase font-semibold">UTME</div>
                            </div>
                            <div class="col-span-1 flex gap-2">
                                <div class="w-[90px] shrink-0">Level:</div>
                                <div class="flex-1 uppercase font-semibold" ng-bind="enrollment_details.level"></div>
                            </div>
                        </div>

                    </div>



                </div>
            </div>

            <div class="mt-4 responsive-table text-sm">
                <table class="mx-auto print:text-black !w-[400px] min-w-[90%]">
                    <thead class="print:text-black">
                        <th class="!w-[80px]">Code</th>
                        <th>Title</th>
                        <th class="w-10">Units</th>
                        <th class="!w-32">Type</th>
                    </thead>
                    <tbody>
                        <tr ng-repeat="enrollment in enrollment_details.courses">
                            <td class="!w-[80px]" ng-bind="enrollment.code"></td>
                            <td class="!text-left" ng-bind="enrollment.name"></td>
                            <td class="!text-center" ng-bind="enrollment.units"></td>
                            <td class="uppercase" ng-bind="enrollment.option"></td>
                        </tr>

                        <tr>
                            <td></td>
                            <td class="uppercase">Total</td>
                            <td ng-bind="enrollment_details.totalUnits"></td>
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


            body {
                background: #fff;
            }

            td,
            th {
                background: transparent !important;
            }
        }
    </style>
</div>