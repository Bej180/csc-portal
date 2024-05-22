<x-template ng-controller='StaffResultsController' ng-init="loadResults()">

    <x-route route="index" class="half">
        <section class="half-60">
            <div class="card  h-full">
                <div class="card-header">
                    <div class="card-title">
                        Results Uploaded
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-content">
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
                                                        <p ng-bind="result.status" class="uppercase">
                                                        </p>

                                                    </div>
                                                </div>
                                                <div class="py-2">
                                                    <div class="flex flex-wrap items-center gap-3">
                                                        <button class="btn btn-secondary btn-100" type="button"
                                                            ng-click="ViewCourseResult(result)">View</button>
                                                        <button class="btn btn-primary btn-100"
                                                            ng-disabled="result.status=='approved'">Edit</button>
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
                                                            <div class="placeholder h-12 rounded-full w-12 shrink-0"
                                                                </div>
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
        </section>
        <section>
            <div class="card">

                <div class="card-caption">
                    <div class="card-title">Add Result</div>
                </div>
                <form class="card-body md:max-h-[calc(-12.5rem+100dvh)]">
                    <div class="flex flex-col gap-3">
                        <div class="flex flex-col gap-1 mt-2">
                            <div class="font-bold text-sm text-[--surface-500]" for="course">Course</div>
                            <select placeholder="Select a course" ng-model="results.course_id">
                                @foreach ($allocations as $allocation)
                                    <option value="{{ $allocation->course->id }}">{{ $allocation->course->code }}
                                    </option>
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
                        
                        <button type="button" class="btn btn-primary mt-2" controller="addResults()">Submit</button>
                    </div>
                </form>
            </div>
        </section>




    </x-route>

    <x-route name="add_result">
        @include('pages.staff.result-management.add-results')
    </x-route>

    <x-route name="course_results">
        @include('pages.results.single-course-results')
    </x-route>
</x-template>
