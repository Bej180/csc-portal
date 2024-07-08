<div ng-show="enrollment_details">

    
    <div id="registered-courses-details-container"
        class="mt-2 border slate-400 rounded p-7 pb-10 md:flex md:flex-col md:gap-2 visible-on-print bg-white dark:bg-black print:!bg-white dark:border-none">
        <div class="hide-on-print">
            <div class="flex items-center justify-between">
                <span class="text-2xl hover:text-primary font-bold" ng-click="route('edit_student')">
                    <i class="fa fa-chevron-left"></i> Student Details
                </span>
            </div>
        </div>

        <div class="flex gap-4 items-center mt-4">
            <avatar user="edit_student" alt="user" class="rounded-full w-14 lg:w-16 shrink-0 xl:w-20 aspect-square"></avatar>
            <div>
                <p class="text-2xl" ng-bind="edit_student.name"></p>
                <p ng-bind="edit_student.reg_no"></p>
                <p class="flex items-center">
                    <span class="inline-flex items-center gap-1">
                        <span>
                            Session: <b ng-bind="enrollment_details.session"></b>
                        </span> 
                        <span class="hover:text-primary">
                            <x-icon name="edit" ng-click="changeEnrollment(enrollment_details,'session')"/>
                        </span>
                    </span>
                    <span class="vertical-divider"></span>
                    <span class="inline-flex items-center gap-1">
                        <span>
                            Semester: <b ng-bind="enrollment_details.semester"></b>
                        </span>
                        <span class="hover:text-primary">
                            <x-icon name="edit" ng-click="changeEnrollment(enrollment_details,'semester')"/>
                        </span>
                    </span>
                    <span class="vertical-divider"></span>
                    <span class="inline-flex items-center gap-1">
                        <span>
                            Level: <b ng-bind="enrollment_details.level"></b>
                        </span> 
                        <span class="hover:text-primary">
                            <x-icon name="edit" ng-click="changeEnrollment(enrollment_details,'level')"/>
                        </span>
                    </span>
                </p>
            </div>
        </div>

        <button class="btn btn-primary btn-faint w-full mt-3" ng-click="addMoreCourseToEnrollment($event, enrollment_details)">
            Add More Course
        </button>



        <div class="mt-4 responsive-table text-sm">
            <table class="mx-auto print:text-black !w-[400px] min-w-[90%]">
                <thead class="print:text-black">
                    <th class="!w-[80px]">Code</th>
                    <th>Title</th>
                    <th class="w-10">Units</th>
                    <th class="!w-32">Type</th>
                    <th class="!w-[50px]"></th>
                </thead>
                <tbody>
                    <tr ng-repeat="enrollment in enrollment_details.courses">
                        <td class="!w-[80px]" ng-bind="enrollment.code"></td>
                        <td class="!text-left" ng-bind="enrollment.name"></td>
                        <td class="!text-center" ng-bind="enrollment.units"></td>
                        <td class="uppercase" ng-bind="enrollment.option"></td>
                        <td class="!w-[50px]">
                            <button type="button" ng-click="deleteCourseFromEnrollment($event, enrollment)" class="btn btn-danger btn-faint rounded-full btn-icon-sm">
                                <x-icon name="delete"/> 
                            </button>
                        </td>
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