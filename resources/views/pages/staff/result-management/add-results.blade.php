<div ng-if="enrollments.data" class="p-6 w-full" ng-init="init()">

    <div class="flex justify-between items-center w-full">

        <div class="text-2xl flex gap-2 items-center" ng-click="route('index')">
            <span class="fa fa-chevron-left hover:text-primary"></span>
            <span class="border-r border-slate-500 pr-4" ng-bind="enrollments.data[0].course.name"></span> <span
                class="pl-4">{% enrollments.data[0].course.units %}
                {% enrollments.data[0].course.units > 1 ? 'units' : 'unit' %}</span>
        </div>

        <div class="flex gap-2">
            <button id="submitResult" class="btn btn-primary btn-flex" type="button"
                controller="uploadResults(enrollments.data, results)">
                <i class="material-symbols-rounded">upload</i> <label>Upload Result</label>
            </button>
            <button controller="saveResultsAsDraft(enrollments.data, results.session)" class="btn btn-white btn-flex" type="button">
                <i class="material-symbols-rounded">edit_note</i> <label>Save As Draft</label>
            </button>
        </div>
    </div>


    <div id="spreadsheetx" class="card2 mt-4 rounded-md overflow-clip">
        <table class="responsive-table no-zebra">
            <thead>
                <tr>
                    <th class="!text-center">SN</th>
                    <th>NAME</th>
                    <th class="!text-center">REG NO.</th>
                    <th ng-if="enrollments.data[0].course.has_practical != 0" class="!text-center">LAB</th>
                    <th class="!text-center">TEST</th>
                    <th class="!text-center">EXAM</th>
                    <th class="!text-center">TOTAL</th>
                    <th class="!text-center">GRADE</th>
                    <th class="!text-center">REMARK</th>
                </tr>

            </thead>
            <tbody>
                <tr ng-repeat="student in enrollments.data" data-series="{% $index %}" ng-click="focusInput($event)"
                    class="group focus-within:border-t-2 focus-within:border-b-2 focus-within:border-[#16a34a73] focus-within:!bg-[#ecf4ec]"
                    ng-controller="ResultSummerController" ng-init="updateGrade($event, student.results, enrollments.data[0].course)">

                    <td class="px-2 !text-center" ng-bind="$index + 1"></td>
                    <td ng-bind="student.student.user.name"></td>
                    <td class="text-center" ng-bind="student.reg_no"></td>

                    <td class="p-0 !text-center" ng-if="enrollments.data[0].course.has_practical != 0">


                        <div class="flex justify-center items-center">
                            <div ng-if="student.results.status==='ready'"
                                class="input !w-[60px] rounded-md text-center  input-disabled justify-center !inline-flex items-center cursor-not-allowed"
                                title="You can't edit this" ng-bind="student.results.lab"></div>
                            <input ng-if="student.results.status !== 'ready'" autocomplete="off" type="text"
                                id="lab" ng-model="student.results.lab"
                                class="mk-2 !w-[60px] rounded-md input text-center "
                                ng-keyup="updateGrade($event, student.results, enrollments.data[0].course)" maxlength="2" min="0" max="99">
                        </div>
                    </td>
                    <td class="p-0 !text-center">
                        <div class="flex justify-center items-center">
                            <input autocomplete"off" type="text" id="test" ng-model="student.results.test"
                                class="mk-2 !w-[60px] rounded-md input text-center "
                                ng-keyup="updateGrade($event, student.results, enrollments.data[0].course)" maxlength="2" min="0" max="99">
                        </div>

                    </td>
                    <td class="p-0 !text-center">
                        <div class="flex justify-center items-center">
                            <input autocomplete="off" type="text" ng-model="student.results.exam"
                                class="mk-2 !w-[60px] rounded-md input text-center "
                                ng-keyup="updateGrade($event, student.results, enrollments.data[0].course)" maxlength="2" min="0" max="99">
                        </div>

                    </td>
                    <td id="score" ng-class="{'text-red-500':student.results.score && student.results.score>100}"
                        ng-bind="student.results.score" class="!text-center"></td>
                    <td class="!text-center" id="grade" ng-bind="student.results.grade"></td>
                    <td id="remark" class="!text-center" ng-bind="student.results.remark"></td>
                </tr>
            </tbody>
        </table>


    </div>


</div>
