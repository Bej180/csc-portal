
<div ng-if="enrollments.data" class="p-6 w-full" ng-init="init()">


  <div class="flex justify-between items-center w-full">

      <div class="text-2xl flex gap-2 items-center"  ng-click="route('index')">
          <span class="fa fa-chevron-left hover:text-primary"></span>
          <span class="border-r border-slate-500 pr-4">
              {% enrollments.data[0].course_name %}</span> LAB Score <span
              class="pl-4">{% enrollments.data[0].units %}
              {% enrollments.data[0].units > 1 ? 'units' : 'unit' %}</span>
      </div>

      <div class="flex gap-2">
          <button id="submitResult" class="btn btn-primary" type="button" controller="uploadLabScores(results)">Upload Lab Result</button>
          
      </div>
  </div>


  <div id="spreadsheetx" class="card2 mt-4 rounded-md overflow-clip">
      <table class="responsive-table no-zebra">
          <thead>
              <tr>
                  <th class="text-center">SN</th>
                  <th>NAME</th>
                  <th class="text-center">REG NO.</th>
                  <th class="text-center">LAB</th>
              </tr>

          </thead>
          <tbody>
              <tr ng-repeat="student in enrollments.data" data-series="{% $index %}" ng-click="focusInput($event)"
              class="group focus-within:border-t-2 focus-within:border-b-2 focus-within:border-[#16a34a73] focus-within:!bg-[#ecf4ec]" ng-controller="ResultSummerController">

                  <td class="px-2 !text-center" ng-bind="$index + 1"></td>
                  <td ng-bind="student.name"></td>
                  <td class="text-center" ng-bind="student.reg_no"></td>
                 
                  <td class="p-0 text-center">


                      <input autocomplete="off" type="text" id="lab" ng-model="results.data[$index].lab" class="mk-2 !w-[60px] rounded-md input text-center " ng-keyup="updateGrade($index)" maxlength="2" min="0" max="99">
                  </td>
                 
              </tr>
          </tbody>
      </table>


  </div>


</div>

