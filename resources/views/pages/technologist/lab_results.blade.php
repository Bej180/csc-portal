<x-template>
    <main class="half" ng-controller='TechnologistController'
        ng-init="initializePage()">
        <section class="half-60">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="material-symbols-rounded">computer_rounded</i> Uploaded Lab Scores
                    </div>
                </div>


                <div class="mt-4 table-container responsive-table no-zebra !shadow-none overflow-auto">
                    <table ng-if="results.length > 0">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Session</th>
                                <th>Semester</th>
                                <th>Level</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="result in results track by result.id" ng-class="{'text-primary':result.status == 'APPROVED'}">
                                <td ng-class="{'font-semibold':result.status == 'APPROVED'}"><span ng-bind="result.course.code" ng-class="{'font-semibold':result.status == 'APPROVED'}"></span> Lab </td>
                                <td ng-bind="result.session"></td>
                                <td ng-bind="result.semester"></td>
                                <td ng-bind="result.level"></td>
                                <td ng-bind="formatDate(result.created_at)"></td>
                                <td class="flex items-center justify-between gap-2">
                                    <button class="flex gap-1 items-center btn btn-secondary" type="button"
                                        aria-label="View">
                                        <span class="fa fa-eye"></span>
                                        <span class="p-button-label">View</span>
                                    </button>
                                    
                                    <span class="whitespace-nowrap" ng-class="{'text-primary':result.status == 'APPROVED'}">
                                        <i class="fa" ng-class="{'fa-check':result.status == 'APPROVED'}"></i>
                                        <span ng-bind="result.status"></span>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div ng-if="results.length === 0 && !initialized">
                        Loading
                    </div>
                    <div ng-if="results.length === 0 && initialized" class="grid place-items-center">
                        <img src="{{ asset('svg/404.svg') }}" class="w-52" />
                        <p class="text-zinc-400 text-2xl"> NO PENDING LAB SCORE </p>
                    </div>
                </div>
            </div>
        </section>
        {{-- <section ng-if="!enrollments" class="p-5 h-full w-full md:w-[60%] lg:flex-grow overflow-x-hidden">
            <div class="card  h-full">
                <div class="card-header">
                    <div class="card-title">
                        Results Uploaded
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-content">
                        <div>
                           
                        </div>
                        <div class="h-full mt-5">
                            <div class="flex items-center -mt-5 gap-3 pb-3 max-w-96">
                                <div class="input-group">
                                    <input class="input " placeholder="Search">
                                    <button class="btn-icon btn-primary" type="button"> <span
                                            class="fa fa-search"></span>

                                    </button>
                                </div>
                            </div>
                            <div class="flex flex-col gap-5">
                                <div ng-if="all_results" ng-repeat="result in all_results">
                                    <div class="flex flex-col gap-4">
                                        <div class="card  border">
                                            <div class="card-body">
                                                <div class="card-caption">
                                                    <div class="card-title">
                                                        <div class="flex items-center gap-1 w-full">
                                                            <div class="avatar  avatar-circle avatar-lg shrink-0"
                                                                style="background-color: rgb(222, 233, 252); color: rgb(26, 37, 81);">
                                                                <span class="avatar-text"
                                                                    ng-bind="result.course.code.substring(0,1)"></span>
                                                            </div>
                                                            <h1 title="course-title"
                                                                class="text-[--highlight-text-color] text-lg w-full whitespace-nowrap text-ellipsis overflow-hidden"
                                                                ng-bind="result.course.code">
                                                            </h1>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center flex-wrap">
                                                            <p ng-bind="result.session"></p>
                                                            <div class="vertical-divider" role="separator"
                                                                aria-orientation="vertical"
                                                                style="align-items: center;">
                                                            </div>
                                                            <p ng-bind="result.course.semester"></p>
                                                            <div class="vertical-divider" role="separator"
                                                                aria-orientation="vertical"
                                                                style="align-items: center;">
                                                            </div>

                                                        </div>
                                                        <p ng-bind="result.course.approve===0?'Approve':'Unapproved'">
                                                        </p>

                                                    </div>
                                                </div>
                                                <div class="py-2">
                                                    <div class="flex flex-wrap items-center gap-3">
                                                        <button class="btn btn-outlined" type="button"
                                                            aria-label="View">
                                                            <span class="p-button-label">View</span>
                                                        </button>
                                                        <button class="btn btn-primary" type="button" aria-label="Edit"
                                                            disabled="">
                                                            <span class="p-button-label">Edit</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div ng-if="!all_results" ng-repeat="n in [1,2,3,4]">
                                    <div class="flex flex-col gap-4">
                                        <div class="card  border">
                                            <div class="card-body">
                                                <div class="card-caption placeholder-glow">
                                                    <div class="card-title flex flex-col gap-3">
                                                        <div class="flex items-center gap-1 w-full">
                                                            <div class="placeholder h-12 rounded-full w-12 shrink-0" </div>
                                                                <span class="avatar-text"></span>
                                                            </div>
                                                            <h1 title="course-title" class="placeholder w-32"></h1>
                                                        </div>
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex items-center flex-wrap gap-3">
                                                                <p class="placeholder w-20"></p>

                                                                <p class="placeholder w-20"></p>


                                                            </div>
                                                            <p class="placeholder w-20">
                                                            </p>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
        </section>--}}
        <section ng-if="!enrollments">
            <div class="card">

                <div class="card-caption">
                    <div class="card-title">Add Lab Score</div>
                </div>
                <form class="card-body md:max-h-[calc(-12.5rem+100dvh)]">
                    <div class="flex flex-col gap-3">
                        <div class="flex flex-col gap-1 mt-2">
                            <div class="font-bold text-sm text-[--surface-500]" for="course">Course</div>
                            <select placeholder="Select a course" ng-model="results.course_id">
                                @foreach ($courses as $course)
                                    <option value="{{ $course->course->id }}">{{ $course->course->code }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-1 mt-2">
                            <div class="font-bold text-sm text-[--surface-500]" for="session">Session</div>
                            <select ng-model="results.session" class="input">
                                @foreach ($sessions as $session)
                                    <option>{{ $session->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-1 mt-2">
                            <div class="font-bold text-sm text-[--surface-500]" for="semester">Semester</div>
                            <select drop="top" placeholder="Select semester" ng-model="results.semester"
                                class="input">
                                <option>HARMATTAN</option>
                                <option>RAIN</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary mt-2" controller="addResults()">Submit</button>
                    </div>
                </form>
            </div>
        </section>

        @include('pages.technologist.add-lab-score')

    </main>
</x-template>
