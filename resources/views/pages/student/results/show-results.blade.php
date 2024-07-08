<x-template nav="results" title="Results" controller="StudentResultsController" ng-init="init()">
    <x-route name="index" class="columns">
        <section class="half-40">

            <div class="list">

                <div class="shadow bg-white p-4 flex flex-col justify-between overflow-hidden rounded-md min-h-32">
                    <header class="w-full flex items-center justify-between">
                        <p class="font-medium uppercase">Summary</p><span class="material-symbols-rounded">groups</span>
                    </header>
                    <div>
                        <h1 class="font-medium text-4xl">CGPA <span ng-bind="cgpa||0" class="font-bold"></span></h1>
                        <h1 class="text-sm opacity-40"></h1>
                    </div>
                </div>

                <div class="bg-[--blue-200] p-4 flex flex-col justify-between overflow-hidden rounded-md min-h-32">
                    <header class="w-full flex items-center justify-between">
                        <p class="font-medium uppercase">Enrolled In</p><span
                            class="material-symbols-rounded">groups</span>
                    </header>
                    <div>
                        <h1 class="font-medium text-4xl" ng-bind="totalEnrollments.length"></h1>
                        <h1 class="text-sm opacity-40"></h1>
                    </div>
                </div>

                <div class="bg-[--green-200] p-4 flex flex-col justify-between overflow-hidden rounded-md min-h-32">
                    <header class="w-full flex items-center justify-between">
                        <p class="font-medium uppercase">Awaiting Results</p><span
                            class="material-symbols-rounded">groups</span>
                    </header>
                    <div>
                        <h1 class="font-medium text-4xl" ng-bind="awaitingResults.length"></h1>
                        <h1 class="text-sm opacity-40"></h1>
                    </div>
                </div>


                <div class="bg-[--orange-200] p-4 flex flex-col justify-between overflow-hidden rounded-md min-h-32">
                    <header class="w-full flex items-center justify-between">
                        <p class="font-medium uppercase">Unsettle Results</p><span
                            class="material-symbols-rounded">groups</span>
                    </header>
                    <div>
                        <h1 class="font-medium text-4xl" ng-bind="unsettledResults.length"></h1>
                        <h1 class="text-sm opacity-40"></h1>
                    </div>
                </div>


            </div>

        </section>
        <section>

            <div class="grid md:grid-cols-2 gap-5">
                <div ng-repeat="result in results" ng-click="displayResults(result)">
                    <div class="shadow hover:shadow-md bg-white p-4 flex flex-col justify-between overflow-hidden rounded-md min-h-32">
                        <header class="w-full flex items-center justify-between">
                            <p class="font-medium uppercase">{%result.semester%} {%result.session%}</p><span
                                class="material-symbols-rounded">groups</span>
                        </header>
                        <div>
                            <h1 class="font-medium text-4xl" ng-bind="result.results.length||0"></h1>
                            <h1 class="text-sm opacity-40 flex items-center justify-between">
                                <span> 
                                    GPA <span ng-bind="result.gpa"></span>
                                </span>
                                <span ng-if="result.carryover.length>0" class="text-red-500">
                                    FAILED <span ng-bind="result.carryover.length"></span>
                                </span>
                            </h1>
                        </div>
                    </div>
                </div>


                <div ng-if="!results||results.length==0" ng-repeat="i in [1,3,4,5, 8, 10, 9]" ng-click="displayResults(result)">
                    <div class="placeholder-glow shadow bg-white p-4 flex flex-col justify-between overflow-hidden rounded-md min-h-32">
                        <header class="w-full flex items-center justify-between">
                            <p class="placeholder w-[70%]"></p>
                        </header>
                        <div>
                            <h1 class="font-medium placeholder w-5 h-8"></h1>
                            <h1 class="mt-2 flex items-center justify-between">
                                <span class="placeholder w-20"> 
                                </span>
                                <span class="placeholder w-20">
                                </span>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>


        </section>
    </x-route>
    <x-route name="display_results" class="columns">
        <div class="w-full">
            <div class="text-2xl hover:text-primary font-semibold" ng-click="route('index')">
                <i class="fa fa-chevron-left"></i> Results
            </div>
        <div class="box shadow-md flex flex-col justify-between min-h-[calc(-2rem+100%)]">
            <div class="box-wrapper w-full overflox-x-auto responsive-table min-w-full no-zebra !shadow-none">
                <div class="print:visible">
                    <div class="flex flex-col items-center">
                        <img src="http://127.0.0.1:8000/images/futo-log.png" alt="futo-logo" width="35">
                        <h1 class="text-sm font-semibold text-body-400 md:text-base xl:text-lg print:text-black">
                            FEDERAL UNIVERSITY OF TECHNOLOGY, OWERRI
                        </h1>
                        <p class="text-xs text-body-400 font-semibold md:text-sm xl:text-base print:text-black">DEPARTMENT OF
                            COMPUTER SCIENCE (SICT)</p>
                    </div>
                </div> 
                <table class="visible-on-print">
                    <thead>
                        <tr>
                            <th class="w-24">Code</th>
                            <th>Title</th>
                            <th class="w-20">Units</th>
                            <th class="w-20">Test</th>
                            <th class="w-20">Lab</th>
                            <th class="w-20">Exam</th>
                            <th class="w-20">Total</th>
                            <th class="w-20">Grade</th>
                            <th class="w-20">Remark</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr ng-class="{'!text-red-600':result.grade == 'F'}" ng-repeat="result in display_results.results">
                            <td class="uppercase whitespace-nowrap" ng-bind="result.course.code"></td>
                            <td>
                                <span ng-bind="result.course.name"></span>
                                <span ng-if="result.remark=='FAILED'" class="text-xs opacity-40">
                                    ({% result.settled?'SETTLED':'UNSETTLED' %})
                                </span>
                            </td>
                            <td class="!text-center" ng-bind="result.course.units"></td>
                            <td class="!text-center" ng-bind="result.test"></td>
                            <td class="!text-center" ng-bind="result.lab"></td>
                            <td class="!text-center" ng-bind="result.exam"></td>
                            <td class="!text-center" ng-bind="result.score"></td>
                            <td class="uppercase" ng-bind="result.grade"></td>
                            <td class="uppercase" ng-bind="result.remark"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="pb-3 px-3 flex items-center justify-between w-full">
                <div class="flex items-center gap-2 rounded bg-primary-50 text-sm p-2 print:visble">
                    <p class="text-body-300">GPA:
                        <span class="link font-semibold" ng-bind="display_results.gpa"></span>
                    </p>
                    <p class="text-body-300">CGPA:
                        <span class="link font-semibold" ng-bind="cgpa"></span>
                    </p>
                </div>

                <!-- Display this button only if there are results to show -->
                <button type="button" ng-click="print()" class="btn btn-primary">Print
                    Result
                </button>
                <!-- Display this button only if there are results to show -->
            </div>

        </div>
        </div>

    </x-route>





    {{-- 
        <form action="?" class="flex items-center gap-2 w-full flex-wrap mb-4"
            ng-init="session='{{ $session ?? '' }}'; semester= '{{ $semester ?? '' }}';">
            <div class="select">
                <select name="session" ng-model="session" id="session" title="session" class="rounded">
                    <option value="">Select Session</option>
                    @foreach ($sessions as $_session)
                        <option value="{{ $_session->session }}" {{ $_session->session == $session ? 'selected' : '' }}>
                            {{ $_session->session }}</option>
                    @endforeach
                </select>
            </div>

            <div class="select">
                <select ng-disabled="!session" ng-model="semester" name="semester" id="semester" title="semester"
                    class="rounded">
                    <option value="">Select Semester</option>
                    <option value="HARMATTAN">Harmattan</option>
                    <option value="RAIN">Rain</option>
                </select>
            </div>

            <div>

                <button ng-disabled="!semester" type="button" class="btn btn-primary">
                    Search
                </button>
            </div>
        </form>
        <div class=" flex flex-col">
            @if (!$approved || count($approved) === 0)
                <div class="flex items-center justify-center text-3xl opacity-25 font-extrabold mt-10 print:mt-0">
                    <div>
                        @if (!$unapproved && !$approved)
                            Select Section and Semester above to view results
                        @elseif (count($unapproved) > 0)
                            Congrats, Results are ready.<br>
                            But, waiting for approval.
                        @else
                            Your results are not ready yet
                        @endif
                    </div>
                </div>
            @else
                <div class="cd">
                    <div class="cd-b table">
                        <table class="overflow-y-visible visible-on-print">
                            <thead>
                                <tr>
                                    <th class="w-24">Code</th>
                                    <th>Title</th>
                                    <th class="w-20">Units</th>
                                    <th class="w-20">Test</th>
                                    <th class="w-20">Lab</th>
                                    <th class="w-20">Exam</th>
                                    <th class="w-20">Total</th>
                                    <th class="w-20">Grade</th>
                                    <th class="w-20">Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($approved as $record)
                                    @php
                                        $result = \App\Models\Enrollment::result(
                                            $record->reg_no,
                                            $record->course_id,
                                            $record->semester,
                                            $record->session,
                                        );
                                        $grading = $result->getGrading();
                                        //g $reg_no, int $course_id, string $semester, string $session
                                    @endphp
                                    <tr>
                                        <td class="uppercase whitespace-nowrap">{{ $record->course->code }}</td>
                                        <td>{{ $record->course->name }}</td>
                                        <td class="!text-center" ng-bind="result->course->units }}</td>
                                        <td align="center">{{ $result->test }}</td>
                                        <td align="center">{{ $result->lab }}</td>
                                        <td align="center">{{ $result->exam }}</td>
                                        <td>{{ $result->score }}</td>
                                        <td class="uppercase">{{ $grading['alphaGrade'] }}</td>
                                        <td class="uppercase">{{ $result->score < 40 ? 'Failed' : 'Passed' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="pb-3 px-3 flex items-center justify-between w-full">
                        <div class="flex items-center gap-2 rounded bg-primary-50 text-sm p-2 print:visble">
                            <p class="text-body-300">GPA:
                                <span class="link font-semibold">{{ $GPA['GPA'] }}</span>
                            </p>
                            <p class="text-body-300">CGPA:
                                <span class="link font-semibold">{{ $student->calculateCGPA('GPA') }}</span>
                            </p>
                        </div>

                        <!-- Display this button only if there are results to show -->
                        <button type="button" ng-click="print()" class="btn btn-primary">Print
                            Result
                        </button>
                        <!-- Display this button only if there are results to show -->
                    </div>

                </div>

            @endif
        </div> --}}
</x-template>
