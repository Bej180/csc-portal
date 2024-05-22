<x-template nav="results" title="Awaiting Results">

    <div class="flex h-full" ng-controller="AwaitingResultsController" ng-init="init()">
        <div class=" flex-1">
            <div class="scroller">

                <form action="{{route('approveAwaitingResults')}}" method="POST" class="card">
                  @csrf
                    <div class="card-headerx">
                        <div class="flex gap-3 nav-tab">
                            <a href="#" ng-class="{'active':active==100}" ng-click="loadLevel(100)">100
                                L<span>evel</span></a>
                            <a href="#" ng-class="{'active':active==200}" ng-click="loadLevel(200)">200
                                L<span>evel</span></a>
                            <a href="#" ng-class="{'active':active==300}" ng-click="loadLevel(300)">300
                                L<span>evel</span></a>
                            <a href="#" ng-class="{'active':active==400}" ng-click="loadLevel(400)">400
                                L<span>evel</span></a>
                            <a href="#" ng-class="{'active':active==500}" ng-click="loadLevel(500)">500
                                L<span>evel</span></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div ng-show="awaitingResults.length === 0" justify-self-center place-self-center self-center"
                            result-table-skeleton></div>
                        <table ng-show="awaitingResults.length > 0"
                            class="visible-on-print print:text-black responsive-table whitespace-nowrap w-full">
                            <thead class="print:bg-white print:text-black">
                                <tr>
                                    
                                    <th class="w-10">S/N</th>
                                    <th>Name</th>
                                    <th>Reg. No.</th>
                                    <th class="w-10">Program</th>
                                    <th class="w-10">Test</th>
                                    <th class="w-10">Lab</th>
                                    <th class="w-10">Exam</th>
                                    <th class="w-10">Total</th>
                                    <th class="w-10">Grade</th>
                                    <th class="w-10">Remark</th>
                                    <th><x-checkbox ng-checked="selectAll" name="sxx" ng-change="toggleSelect()"
                                      value="{$ result.id %}"/></th>
                                </tr>
                            </thead>
                            <tbody>


                                <tr ng-repeat="result in awaitingResults"
                                    ng-class="{ 'thick-border': shouldApplyThickBorder(result.code) }">
                                    
                                    <td>{% $index + 1 %}</td>
                                    <td ng-bind="result.name"></td>
                                    <td ng-bind="result.reg_no"></td>
                                    <td ng-bind="result.code"></td>
                                    <td ng-bind="result.test"></td>
                                    <td ng-bind="result.lab"></td>
                                    <td ng-bind="result.exam"></td>
                                    <td ng-bind="result.score"></td>
                                    <td ng-bind="processGrade(result.score)"></td>
                                    <td ng-bind="result.remark"></td>
                                    <td><x-checkbox ng-checked="selectAll" name="approve[{% $index + 1 %}]"
                                      ng-value="result.id"/></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="card-body flex justify-end sticky bottom-0 right-10">
                      <button type="submit" class="text-green-500 flex items-center"><span class="material-symbols-rounded">
                        task_alt
                        </span> <span>Approve</span></button>
                    </div>
                  </form>


            </div>

        </div>
    </div>

</x-template>
