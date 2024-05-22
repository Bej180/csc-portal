<div class="visible-on-print print:text-black responsive-table no-zebra whitespace-nowrap w-full">

    <table ng-init="has_practical=view_course_results[0].course.has_practical==1">

        <thead class="print:bg-white print:text-black">
            <tr>
                <th class="w-10">S/N</th>
                <th class="text-left">Name</th>
                <th class=" !text-center">Reg. No.</th>
                <th class="w-10">Program</th>
                <th class="w-10">Test</th>
                <th ng-if="has_practical" class="w-10">Lab</th>
                <th class="w-10">Exam</th>
                <th class="w-10">Total</th>
                <th class="w-10">Grade</th>
                <th class="w-10">Remark</th>
            </tr>
        </thead>
        <tbody>
            <tr class="hover:!bg-transparent">
                <th class="w-10 !p-0 !text-center"></th>
                <th></th>
                <th></th>
                <th class="w-10 !p-0 !text-center"></th>

                <th ng-if="has_practical" class="w-10 !p-0 !text-center">20</th>
                <th class="w-10 !p-0 !text-center" ng-bind="has_practical?20:30"></th>
                <th class="w-10 !p-0 !text-center" ng-bind="has_practical?60:70"></th>

                <th class="w-10 !p-0 !text-center">100</th>
                <th class="w-10 !p-0"></th>
                <th class="w-10 !p-0"></th>
            </tr>

            <tr ng-repeat="result in view_course_results track by result.id">
                <td ng-bind="$index + 1" class="!text-center"></td>
                <td class="!text-left" ng-bind="result.student.user.name"></td>
                <td ng-bind="result.student.reg_no" class="!text-center"></td>
                <td class="!text-center" ng-bind="result.course.code"></td>
                <td ng-bind="result.test||'-'" class="!text-center"></td>
                <td ng-bind="result.lab||'-'" ng-if="has_practical" class="!text-center"></td>
                <td ng-bind="result.exam||'-'" class="!text-center"></td>
                <td ng-bind="result.score||'-'" class="!text-center"></td>
                <td ng-bind="result.grade||'-'" class="!text-center"></td>
                <td ng-bind="result.remark||'-'" class="!text-center"></td>
            </tr>

        </tbody>
    </table>
</div>
