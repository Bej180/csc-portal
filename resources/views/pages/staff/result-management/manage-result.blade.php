

<div class="flex justify-between items-center w-full">
  @if (count($enrolledStudents) === 0)
      <form action="?" class="flex flex-1 items-center gap-2">
          <div>
              <select name="course" class="input" ng-model="course">
                  <option value="">Select Course</option>
                  @foreach ($courses as $course)
                      <option value="{{ $course->id }}">{{ $course->code }}</option>
                  @endforeach
              </select>
          </div>

          <div>
              <select name="session" class="input" ng-model="session">
                  <option value="">Select Session</option>
                  @foreach (\App\Models\Course::generateSessions() as $session)
                      <option value="{{ $session }}">{{ $session }}</option>
                  @endforeach
              </select>
          </div>

          <div>
              <select name="semester" class="input" ng-model="semester">
                  <option value="">Select Semester</option>
                  <option value="HARMATTAN">Harmattan</option>
                  <option value="RAIN">Rain</option>
              </select>
          </div>
          <div>
              <button class="btn btn-primary">Generate Result Sheet</button>
          </div>



      </form>
  @else
      <div class="text-2xl">
          <span
              class="border-r border-slate-500 pr-4">{{ $enrolledStudents[0]['course_name'] }}</span>
          <span class="pl-4">{{ $enrolledStudents[0]['units'] }}
              {{ str_plural('unit', $enrolledStudents[0]['units']) }}</span>
      </div>
  @endif
  <div class="flex gap-2">
      <button id="submitResult" class="btn btn-primary" type="button">Upload Result</button>
      <button class="btn btn-white" type="button">Clear Results</button>
  </div>
</div>


<div id="spreadsheetx" class="card2 mt-4 rounded-md overflow-clip" data-course="{{ $course_id }}">
  <table class="responsive-table no-zebra">
      <thead>
          <tr>
              <th class="text-center">SN</th>
              <th>NAME</th>
              <th class="text-center">REG NO.</th>
              <th class="text-center">LAB</th>
              <th class="text-center">TEST</th>
              <th class="text-center">EXAM</th>
              <th class="text-center">TOTAL</th>
              <th class="text-center">GRADE</th>
              <th class="text-center">REMARK</th>
              <th></th>
          </tr>

      </thead>
      <tbody>
          @foreach ($enrolledStudents as $n => $student)
              <?php
              
              $result = \App\Models\Enrollment::result($student->reg_no, $course_id, $semester, $session);
              
              // This extracts these fields from the result table if the result exist
              $defaults = [
                  'lab' => '',
                  'exam' => '',
                  'test' => '',
                  'score' => '',
                  'grade' => '',
                  'remark' => '',
              ];
              
              if ($result) {
                  foreach ($defaults as $key => $value) {
                      if (isset($result->$key)) {
                          $defaults[$key] = $result->$key;
                      }
                  }
              }
              extract($defaults);
              
              $gradings = [];
              
              $gradings = $result?->getGrading();
              ?>
              <tr data-series="{{ $n }}" ng-click="focusInput($event)"
                  class="group focus-within:border-t-2 focus-within:border-b-2 focus-within:border-[#16a34a73] focus-within:!bg-[#ecf4ec]"
                  ng-controller="ResultSummerController"
                  ng-init="lab=parseInt('{{ $lab }}'); test=parseInt('{{ $test }}'); exam=parseInt('{{ $exam }}'); score=parseInt('{{ $score }}'); grade='{{ $grade }}'; remark='{{ $remark }}'; exists={{ $result ? 'true' : 'false' }}">


                  <td class="text-center">{{ $n + 1 }}</td>
                  <td>{{ $student->name }}</td>
                  <td class="text-center">{{ $student->reg_no }}</td>
                  <input type='hidden' id='reg_no' value='{{ $student->reg_no }}' />
                  <input type='hidden' id='course_id' value='{{ $course_id }}' />
                  <input type='hidden' id='session' value='{{ $session }}' />
                  <input type='hidden' id='semester' value='{{ $semester }}' />
                  <input type='hidden' id='level' value='{{ $student->level }}' />
                  <input type='hidden' id='reg_no' value='{{ $student->reg_no }}' />
                  <input type='hidden' id='course_id' value='{{ $course_id }}' />
                  <td class="p-0 text-center">


                      <input type="number" id="lab" ng-model="lab"
                          class="text-center focus:outline-none" maxlength="2" min="0"
                          max="99" ng-input="onInput($event)" ng-keyup="onKeyUp($event)"
                          ng-click="onClick($event)" ng-keydown="onKeyDown($event)" />
                  </td>
                  <td class="p-0 text-center">
                      <input type="number" id="test" ng-model="test"
                          class="text-center focus:outline-none" maxlength="2" min="0"
                          max="99" ng-input="onInput($event)" ng-keyup="onKeyUp($event)"
                          ng-click="onClick($event)" ng-keydown="onKeyDown($event)" />
                  </td>
                  <td class="p-0 text-center">
                      <input type="number" id="exam" ng-model="exam"
                          class="text-center focus:outline-none" maxlength="2" min="0"
                          max="99" ng-input="onInput($event)" ng-keyup="onKeyUp($event)"
                          ng-click="onClick($event)" ng-keydown="onKeyDown($event)" />
                  </td>
                  <td id="score" ng-class="{'text-red-500':score && score>100}" ng-bind="score"
                      class="text-center"></td>
                  <td ng-data-grade="{% exam ? grade : '' %}"
                      class="text-center grade student-{{ $n }}" id="grade"
                      ng-bind="exam ? grade : ''"></td>
                  <td id="remark" class="text-center"
                      ng-bind="!lab ? 'FAILED' : (exam ? remark: '')">
                  </td>
                  <td class="text-center">

                      <submit type="button" disabled="{%score > 100%}" class="btn btn-primary"
                          ng-click="saveResult($event)" value="Save"></submit>
                  </td>
              </tr>
          @endforeach
      </tbody>
  </table>


</div>