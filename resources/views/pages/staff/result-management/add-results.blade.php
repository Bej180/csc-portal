<div ng-if="enrollments" class="p-6 w-full" ng-init="bootAddResult()">


    <div class="flex justify-between items-center w-full">

        <div class="text-2xl flex gap-2 items-center" ng-click="route('index')">
            <span class="fa fa-chevron-left hover:text-primary"></span>
            <span class="border-r border-slate-500 pr-4" ng-bind="enrollments.name"></span> <span
                class="pl-4" ng-bind="enrollments.unitWord"></span>
        </div>

        <div class="flex gap-2">
            <button id="submitResult" class="btn btn-warning btn-flex" type="button"
                ng-click="uploadOGMR(enrollments)">
                <i class="material-symbols-rounded">upload</i> <label>Upload Excel</label>
            </button>
            <button ng-click="saveResultsAsDraft($event)" class="btn btn-white btn-flex" type="button">
                <i class="material-symbols-rounded">edit_note</i> <label>Save As Draft</label>
            </button>
        </div>
    </div>


    <div id="spreadsheetx" class="cardx mt-4 rounded-md overflow-clip">
        <div class="card-header">
            <div class="card-title">
                Status: <b ng-bind="enrollments.status"></b>
            </div>
        </div>
        <div class="card-body">
            <div class="responsive-table whitespace-nowrap no-zebra">
                <table>
                    <thead>
                        <tr>
                            <th class="!text-center">SN</th>
                            <th>NAME</th>
                            <th class="!text-center">REG NO.</th>
                            <th ng-if="enrollments.has_practical" class="score-cell">LAB</th>
                            <th class="score-cell">TEST</th>
                            <th class="score-cell">EXAM</th>
                            <th class="!text-center">TOTAL</th>
                            <th class="!text-center">GRADE</th>
                            <th class="!text-center">REMARK</th>
                        </tr>

                    </thead>
                    <tbody>
                        <tr ng-repeat="student in enrollments.students" data-series="{% $index %}" ng-click="focusInput($event)"
                            class="group focus-within:border-t-2 focus-within:border-b-2 focus-within:border-[#16a34a73] focus-within:!bg-[#ecf4ec] focus-within:dark:!bg-zinc-800"
                            ng-init="updateGrade($event, $index)">

                            <td class="px-2 !text-center uppercase" ng-bind="$index + 1"></td>
                            <td ng-bind="student.student.user.name" class="uppercase"></td>
                            <td class="!text-center" ng-bind="student.reg_no"></td>

                            <td class="score-cell" ng-if="enrollments.has_practical != 0">


                                <div class="flex justify-center items-center">
                                    <div ng-if="student.results.status==='ready'"
                                        class="input w-full rounded-md text-center  input-disabled justify-center !inline-flex items-center cursor-not-allowed"
                                        title="You can't edit this" ng-bind="student.results.lab"></div>
                                    <input ng-if="student.results.status !== 'ready'" autocomplete="off" type="text"
                                        ng-model="student.results.lab"
                                        class="input text-center "
                                        ng-keyup="updateGrade($event, $index, 'lab')" maxlength="2" min="0" max="99">
                                </div>
                            </td>
                            <td class="score-cell">
                                <div class="flex justify-center items-center">
                                    <input autocomplete="off" type="text" ng-model="student.results.test"
                                        class="input text-center "
                                        ng-keyup="updateGrade($event, $index, 'test')" maxlength="2" min="0" max="99">
                                </div>

                            </td>
                            <td class="score-cell">
                                <div class="flex justify-center items-center">
                                    <input autocomplete="off" type="text" ng-model="student.results.exam"
                                        class="input text-center"
                                        ng-keyup="updateGrade($event, $index, 'exam')" maxlength="2" min="0" max="99">
                                </div>

                            </td>
                            <td ng-class="{'text-red-500':student.results.score && student.results.score>100}"
                                ng-bind="student.results.score" class="!text-center"></td>
                            <td class="!text-center" ng-bind="student.results.grade"></td>
                            <td class="!text-center" ng-bind="student.results.remark"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex justify-end">
                <button id="submitResult" class="btn btn-primary btn-flex" type="button"
                    ng-click="uploadResults($event)">
                    <i class="material-symbols-rounded">upload</i> <label class="font-bold">FINISH</label>
                </button>
            </div>
        </div>


    </div>


</div>
