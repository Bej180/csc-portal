<x-route name="transcript">

    <main data-v-2934ddcf="" class="w-full h-full p-5 overflow-y-scroll">
        <div data-v-2934ddcf="">
            <div class="flex items-center gap-3 justify-between">
                <h1 class="font-bold text-2xl"><i class="fa fa-chevron-left hover:text-primary"
                        ng-click="route('index')"></i> {% transcriptStudent.user.name %} Transcripts</h1>
                <button ng-click="print()" class="btn btn-primary btn-100" type="button"><i class="fa fa-print"></i>
                    Print</button>
            </div>
        </div>
        <div data-v-2934ddcf="" class="page grid place-items-center">
            <div class="flex flex-col gap-5 visible-on-print">
              <div ng-repeat="(session, sessionData) in transcriptResults" data-v-2934ddcf="" class="div w-[210mm] h-[297mm] bp-10">
                <div data-v-2934ddcf="">
                  <h1 class="text-xl font-bold text-center" data-v-2934ddcf="">FEDERAL UNIVERSITY OF TECHNOLOGY, OWERRI.</h1>
                  <div class="flex justify-center relative" data-v-2934ddcf="">
                    <div class="absolute left-44" data-v-2934ddcf=""><img src="{{asset('images/futo-log.png')}}" alt="futo_logo"
                        class="w-16" data-v-2934ddcf=""/></div>
                    <div class="mx-auto font-semibold text-center" data-v-2934ddcf="">
                      <p data-v-2934ddcf="">OFFICE OF THE REGISTRAR</p>
                      <p data-v-2934ddcf="">(Records &amp; Statistics Unit)</p>
                      <p data-v-2934ddcf="">P.M.B. 1526</p>
                      <p data-v-2934ddcf="">Owerri, Nigeria</p>
                      <p data-v-2934ddcf="">STUDENT'S ACADEMIC TRANSCRIPT</p>
                    </div>
                  </div>
                </div>
                <div data-v-2934ddcf="" class="table-div text-sm grid grid-cols-12 text-center border-t border-t-black">
                  <div data-v-2934ddcf="" class="col-span-6"
                    style="border-width: 0px 0px 0px 1px; border-top-style: initial; border-right-style: initial; border-bottom-style: initial; border-left-style: solid; border-top-color: initial; border-right-color: initial; border-bottom-color: initial; border-left-color: black; border-image: initial;">
                    Name of Student</div>
                  <div data-v-2934ddcf="" class="col-span-1"
                    style="border-width: 0px 0px 0px 1px; border-top-style: initial; border-right-style: initial; border-bottom-style: initial; border-left-style: solid; border-top-color: initial; border-right-color: initial; border-bottom-color: initial; border-left-color: black; border-image: initial;">
                    Sex</div>
                  <div data-v-2934ddcf="" class="col-span-3"
                    style="border-width: 0px 0px 0px 1px; border-top-style: initial; border-right-style: initial; border-bottom-style: initial; border-left-style: solid; border-top-color: initial; border-right-color: initial; border-bottom-color: initial; border-left-color: black; border-image: initial;">
                    Date of Birth</div>
                  <div data-v-2934ddcf="" class="col-span-2"
                    style="border-width: 0px 1px; border-top-style: initial; border-right-style: solid; border-bottom-style: initial; border-left-style: solid; border-top-color: initial; border-right-color: black; border-bottom-color: initial; border-left-color: black; border-image: initial;">
                    Reg. No.</div>
                  <div data-v-2934ddcf="" class="col-span-6 uppercase" style="border-bottom: 0px; border-right: 0px;" ng-bind="transcriptStudent.user.name"></div>
                  <div data-v-2934ddcf="" class="col-span-1 uppercase" style="border-bottom: 0px; border-right: 0px;"></div>
                  <div data-v-2934ddcf="" class="col-span-3 uppercase" style="border-bottom: 0px; border-right: 0px;" ng-bind="transcriptStudent.birthdate"></div>
                  <div data-v-2934ddcf="" class="col-span-2 uppercase" style="border-bottom: 0px;" ng-bind="transcriptStudent.reg_no"></div>
                  <div data-v-2934ddcf="" class="col-span-6" style="border-bottom: 0px; border-right: 0px;">Nationality</div>
                  <div data-v-2934ddcf="" class="col-span-2" style="border-bottom: 0px; border-right: 0px;">State of Origin</div>
                  <div data-v-2934ddcf="" class="col-span-2" style="border-bottom: 0px; border-right: 0px;">Date of Entry</div>
                  <div data-v-2934ddcf="" class="col-span-2" style="border-bottom: 0px;">Mode of Entry</div>
                  <div data-v-2934ddcf="" class="col-span-6" style="border-bottom: 0px; border-right: 0px;"ng-bind="transcriptStudent.country"></div>
                  <div data-v-2934ddcf="" class="col-span-2" style="border-bottom: 0px; border-right: 0px;"></div>
                  <div data-v-2934ddcf="" class="col-span-2" style="border-bottom: 0px; border-right: 0px;" ng-bind="transcriptStudent.entryDate"></div>
                  <div data-v-2934ddcf="" class="col-span-2" style="border-bottom: 0px;"></div>
                  <div style="border-bottom:0;border-right:0;" class="col-span-6" data-v-2934ddcf="">School</div>
                  <div style="border-bottom:0;border-right:0;" class="col-span-2" data-v-2934ddcf="">Department:</div>
                  <div style="border-bottom:0;" class="col-span-4" data-v-2934ddcf="">COMPUTER SCIENCE</div>
                  <div style="border-bottom:0;border-right:0;" class="col-span-6" data-v-2934ddcf="">SICT</div>
                  <div style="border-bottom:0;border-right:0;" class="col-span-2" data-v-2934ddcf="">Option:</div>
                  <div style="border-bottom:0;" class="col-span-4" data-v-2934ddcf="">COMPUTER SCIENCE</div><!-- TABLE STARTS HERE -->
                  <div style="border-right:0;" class="col-span-1 font-semibold" data-v-2934ddcf="">Course Code</div>
                  <div style="border-right:0;" class="col-span-5 font-semibold" data-v-2934ddcf="">Title of course</div>
                  <div style="border-right:0;" class="col-span-1 font-semibold" data-v-2934ddcf="">Units</div>
                  <div style="border-right:0;" class="col-span-1 font-semibold" data-v-2934ddcf="">Grade</div>
                  <div style="border-right:0;" class="col-span-2 font-semibold" data-v-2934ddcf="">Total Grade Points</div>
                  <div class="col-span-2 font-semibold" data-v-2934ddcf="">Cum G.P.A</div>
              
                   <!-- Loop Through Semesters -->
                   <div class="col-span-12">
                  <div  ng-repeat="(semester, semesterData) in sessionData">
                    <div ng-if="semester === 'HARMATTAN'" data-v-2934ddcf=""
                      style="border: 0px;" class="grid grid-cols-12">
                      <div data-v-2934ddcf="" class="col-span-1 border-l border-l-black"></div>
                      <div data-v-2934ddcf="" class="text-left col-span-5 border-l border-l-black pl-1">{% session %} {% semester%} SEMESTER
                      </div>
                      <div data-v-2934ddcf="" class="col-span-1 border-l border-l-black"></div>
                      <div data-v-2934ddcf="" class="col-span-1 border-l border-l-black"></div>
                      <div data-v-2934ddcf="" class="col-span-2 border-l border-l-black"></div>
                      <div data-v-2934ddcf="" class="col-span-2 border-x border-x-black"></div>
                      
                      <!-- Loop Through Results -->
                      <div ng-repeat="result in semesterData.results" data-v-2934ddcf="" class="col-span-12 grid grid-cols-12">
                        <div data-v-2934ddcf="" class="text-left col-span-1 border-l border-l-black pl-1" ng-bind="result.course.code"></div>
                        <div data-v-2934ddcf="" class="text-left col-span-5 pl-1 border-l border-l-black" ng-bind="result.course.name"></div>
                        <div data-v-2934ddcf="" class="col-span-1 border-l border-l-black" ng-bind="result.units"></div>
                        <div data-v-2934ddcf="" class="col-span-1 border-l border-l-black" ng-bind="result.grade"></div>
                        <div data-v-2934ddcf="" class="col-span-2 border-l border-l-black" ng-bind="result.grade_points"></div>
                        <div data-v-2934ddcf="" class="col-span-2 border-x border-x-black"></div>
                      </div>
                      <div class="col-span-12 grid grid-cols-12 h-5" data-v-2934ddcf="">
                        <div class="col-span-1 border-l border-l-black" data-v-2934ddcf=""></div>
                        <div class="col-span-5 border-l border-l-black" data-v-2934ddcf=""></div>
                        <div class="col-span-1 border-l border-l-black" data-v-2934ddcf=""></div>
                        <div class="col-span-1 border-l border-l-black" data-v-2934ddcf=""></div>
                        <div class="col-span-2 border-l border-l-black" data-v-2934ddcf=""></div>
                        <div class="col-span-2 border-x border-x-black" data-v-2934ddcf=""></div>
                      </div>
                    </div>
                
                    <div ng-if="semester === 'RAIN'" ng-repeat="result in secondsemester" data-v-2934ddcf="" class="col-span-12 grid grid-cols-12" style="border: 0px;">
                      <div data-v-2934ddcf="" class="col-span-1 border-l border-l-black"></div>
                      <div data-v-2934ddcf="" class="text-left col-span-5 border-l border-l-black pl-1">{%session%} {% semester %}
                        SEMESTER </div>
                      <div data-v-2934ddcf="" class="col-span-1 border-l border-l-black"></div>
                      <div data-v-2934ddcf="" class="col-span-1 border-l border-l-black"></div>
                      <div data-v-2934ddcf="" class="col-span-2 border-l border-l-black"></div>
                      <div data-v-2934ddcf="" class="col-span-2 border-x border-x-black"></div>
                       <!-- Loop Through Results -->
                      <div ng-repeat="result in semesterData.results" data-v-2934ddcf="" class="col-span-12 grid grid-cols-12">
                        <div data-v-2934ddcf="" class="text-left col-span-1 border-l border-l-black pl-1" ng-bind="result.course.code"></div>
                        <div data-v-2934ddcf="" class="text-left col-span-5 pl-1 border-l border-l-black" ng-bind="result.course.name"></div>
                        <div data-v-2934ddcf="" class="col-span-1 border-l border-l-black" ng-bind="result.units"></div>
                        <div data-v-2934ddcf="" class="col-span-1 border-l border-l-black" ng-bind="result.grade"></div>
                        <div data-v-2934ddcf="" class="col-span-2 border-l border-l-black" ng-bind="result.grade_points"></div>
                        <div data-v-2934ddcf="" class="col-span-2 border-x border-x-black"></div>
                      </div>
              
                    </div>
                  </div>
                </div>
                  <div style="border:0;" class="col-span-12 grid grid-cols-12 h-10" data-v-2934ddcf="">
                    <div class="col-span-1 border-l border-l-black" data-v-2934ddcf=""></div>
                    <div class="col-span-5 border-l border-l-black" data-v-2934ddcf=""></div>
                    <div class="col-span-1 border-l border-l-black" data-v-2934ddcf=""></div>
                    <div class="col-span-1 border-l border-l-black" data-v-2934ddcf=""></div>
                    <div class="col-span-2 border-l border-l-black" data-v-2934ddcf=""></div>
                    <div class="col-span-2 border-x border-x-black" data-v-2934ddcf=""></div>
                  </div>
                  <div data-v-2934ddcf="" class="col-span-12 grid grid-cols-12">
                    <div data-v-2934ddcf="" class="col-span-1 border-r border-r-black"></div>
                    <div data-v-2934ddcf="" class="col-span-5"></div>
                    <div data-v-2934ddcf="" class="col-span-1 border-l border-l-black" ng-bind="sessionData.sessionTotals.totalUnits"></div>
                    <div data-v-2934ddcf="" class="col-span-1 border-l border-l-black"></div>
                    <div data-v-2934ddcf="" class="col-span-2 border-l border-l-black" ng-bind="sessionData.sessionTotals.totalGradePoints"></div>
                    <div data-v-2934ddcf="" class="col-span-2 border-l border-l-black" ng-bind="calculateCGPA(sessionData.sessionTotals)"></div>
                  </div>
                </div>
                <!--v-if-->
                <section class="grid place-content-center text-sm mt-5" data-v-2934ddcf="">
                  <h1 class="uppercase text-center font-semibold" data-v-2934ddcf="">GRADING SYSTEM</h1>
                  <div class="grid grid-cols-4 gap-x-6" data-v-2934ddcf="">
                    <div data-v-2934ddcf="">A - Excellent:</div>
                    <div data-v-2934ddcf="">5 points</div>
                    <div data-v-2934ddcf="">D - Pass:</div>
                    <div data-v-2934ddcf="">2 points</div>
                    <div data-v-2934ddcf="">B - Very Good:</div>
                    <div data-v-2934ddcf="">4 points</div>
                    <div data-v-2934ddcf="">E - Poor Pass:</div>
                    <div data-v-2934ddcf="">1 point</div>
                    <div data-v-2934ddcf="">C - Good:</div>
                    <div data-v-2934ddcf="">3 points</div>
                    <div data-v-2934ddcf="">F - Failure:</div>
                    <div data-v-2934ddcf="">0 points</div>
                  </div>
                  <div class="flex items-center gap-5" data-v-2934ddcf="">
                    <p data-v-2934ddcf="">I - Incomplete</p>
                    <p data-v-2934ddcf="">W - Withdrew</p>
                    <p data-v-2934ddcf="">WP - Withdrew Passing</p>
                    <p data-v-2934ddcf="">WF - Withdrew Failing</p>
                  </div>
                </section>
                <section class="flex items-center justify-center gap-20 mt-10 text-[0.9rem" data-v-2934ddcf="">
                  <div class="text-center font-semibold" data-v-2934ddcf="">
                    <p data-v-2934ddcf="">ODISA C. OKEKE (MRS.)</p>
                    <hr class="border-black w-60" data-v-2934ddcf="">
                    <p data-v-2934ddcf="">FOR: REGISTRAR</p>
                  </div>
                  <div class="text-center font-semibold" data-v-2934ddcf=""><br data-v-2934ddcf="">
                    <hr class="border-black w-60" data-v-2934ddcf="">
                    <p data-v-2934ddcf="">DATE</p>
                  </div>
                </section>
              </div>

              

            </div>
        </div>
    </main>
</x-route>
<style>
    .table-div>div[data-v-2934ddcf] {
        border: 1px solid black;
    }
</style>
