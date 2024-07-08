<x-template title="My Courses" controller="StaffCourseController" ng-init="boot()">

    <x-route class="columns">
        
        <section class="half-60">
            
            <div class="tabview min-h-full" role="tablist">
                <div class="tabview-nav-container">
                    <div class="tabview-nav-content">
                        <ul class="tabview-nav">
                        
                            <li ng-repeat="obj in all_results" class="tabview-header" role="presentation" ng-class="{highlight: active_nav==obj.session}" ng-click="changeNav(obj.session)">
                                <a class="tabview-nav-link tabview-header-action"
                                    tabindex="0" role="tab" aria-selected="true">
                                    <h1 ng-bind="obj.session"></h1>
                                </a>
                            </li>
                            
                        </ul>
                    </div>
                </div>
                
                <div class="tabview-panels">
                    <div ng-repeat="obj in all_results" ng-if="active_nav==obj.session" class="tabview-panel" role="tabpanel">
                        <div class="card">
                            <div class="card-header flex !flex-row items-center !justify-between gap-5">
                                <div class="card-title">
                                    <div class="flex-1 flex items-center gap-3">
                                        <h1 class="">{% obj.session %} Session</h1>
                                        <p class="text-[1rem] text-red-500 font-semibold" ng-bind="obj.results.length"></p>
                                    </div>

                                </div>
                                <button ng-if="obj.no_results.length > 0" class="btn btn-primary" ng-click="addResults(obj)">Add Results</button>
                            </div>
                            <div>

            
                                <div class='py-3'>
                                    <span>Filter</span> 
                                    <check oncheck="onFilter(filter)" name="all" class="chip-green" selected ng-model="filter">All</check>
                                    <check oncheck="onFilter(filter)" name="APPROVED" class="chip-green" ng-model="filter">APPROVED</check>
                                    <check oncheck="onFilter(filter)" name="PENDING" class="chip-green" ng-model="filter">PENDING</check>
                                    <check oncheck="onFilter(filter)" name="DRAFT" class="chip-green" ng-model="filter">DRAFT</check>

                                </div>
                                <div class="table-container responsive-table overflow-auto md:h-[calc(-20rem+100dvh)]">
                                    <table >
                                        <thead>
                                            <tr>
                                                <th><sort property="code"  ng-model="sort"></sort> Course</th>
                                                <th><sort property="uploader" ng-model="sort"></sort> Staff</th>
                                                <th><sort property="semester" ng-model="sort"></sort> Semester</th>
                                                <th><sort property="level" ng-model="sort"></sort> Level</th>
                                                <th><sort property="created_at" ng-model="sort"></sort> Date</th>
                                                <th><sort property="status" ng-model="sort"></sort> Status</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="result in obj.results | orderBy:sort[0]:sort[1]">
                                                <td ng-bind="result.code"></td>
                                                <td ng-bind="result.name"></td>
                                                <td ng-bind="result.semester"></td>
                                                <td ng-bind="result.level"></td>
                                                <td ng-bind="formatDate(result.created_at)"></td>
                                                
                                                <td ng-bind="result.status"></td>
                                                <td class="flex items-center justify-end gap-2">
                                                    <button ng-click="ViewCourseResult(result)" class="flex gap-1 items-center btn btn-secondary btn-adaptive" type="button"
                                                        aria-label="View">
                                                        <span class="fa fa-eye"></span>
                                                        <span class="p-button-label">View</span>
                                                    </button>

                                                    <button ng-disabled="!canEdit(result)" ng-click="displayStudentsEnrolledForCourse(result)" class="flex gap-1 items-center btn btn-primary btn-adaptive" type="button"
                                                        aria-label="Edit">
                                                        <span class="fa fa-edit"></span>
                                                        <span class="p-button-label">Edit</span>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div ng-if="all_results.length === 0 && !initialized">
                                        Loading
                                    </div>
                                    <div ng-if="all_results.length === 0 && initialized" class="grid place-items-center">
                                        <img src="{{ asset('svg/404.svg') }}" class="w-52"/>
                                        <p class="text-zinc-400 text-2xl"> NO RESULTS YET </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </section>
        <section class="half-40">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        My Courses 
                    </div>
                </div>
                <div class="card-body gap-3" ng-if="courses_loaded">
                    <div class="list" ng-if="courses.length > 0">
                        <div class="panel" ng-repeat="course in courses">
                            <div class="panel-body">
                                <div class="panel-caption">
                                    <div class="panel-title">
                                        <div class="flex items-center gap-1 w-full">
                                            <div class="avatar avatar-circle avatar-lg shrink-0">
                                                <span
                                                    class="p-avatar-text" ng-bind="course.course.code.substring(0, 1)"></span>
                                            </div>
                                            <h1 title="course-title"
                                                class="text-[--highlight-text-color] text-lg w-full whitespace-nowrap text-ellipsis overflow-hidden" ng-bind="course.course.name"></h1>
                                        </div>
                                    </div>
                                    <div class="panel-subtitle">
                                        <div class="flex items-center flex-wrap">
                                            <p ng-bind="course.course.code"></p>
                                            <div class="vertical-divider" role="separator"
                                                aria-orientation="vertical">
                                            </div>
                                            <p>
                                                <span ng-bind="course.course.units"></span> Unit<span ng-if="course.course.units>1">s</span>
                                            </p>
                                            <div class="vertical-divider" role="separator"
                                                aria-orientation="vertical">
                                            </div>
                                            <p ng-bind="course.course.option" class="text-xs"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-content mt-2">
                                    <div class="flex flex-wrap items-center gap-3">
                                        
                                        <button class="btn btn-secondary" ng-click="viewCourse(course.id)"><i
                                        class="fa fa-eye"></i> View
                                        details</button>

                                        <button class="btn btn-primary" ng-click="viewCourse(course.id)"><i
                                            class="fa fa-eye"></i> Add Result</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div ng-if="courses.length === 0">
                        No Course has been assigned to you
                    </div>
                </div>
                <div class="flex flex-col gap-4" ng-if="!courses_loaded">
                    <section class="placeholder-glow" ng-repeat="allocation in [1,3,5,6,7,9,0]">
                        <div class="card panel">
                            <div class="flex items-center gap-1 w-full">
                                <div class="placeholder !rounded-full shrink-0 h-8 w-8 mr-2">
                                </div>
                                <h1 class="placeholder w-28"></h1>
                            </div>
                            <div class="mt-4 flex items-center gap-3 justify-between flex-wrap font-medium text-sm">
                                <div class="flex items-center flex-wrap gap-3">
                                    <p class="placeholder w-10"></p>
            
                                    <p class="placeholder w-10"></p>
            
                                    <p class="placeholder w-12"></p>
                                </div>
                                <div class="flex flex-wrap items-center gap-3">
            
                                    <p class="placeholder w-20 h-6"></p>
                                </div>
                            </div>
                        </div>
                    </section>
            
                </div>

            </div>
        </section>
    </x-route>

    <x-route name="add_result">
        @include('pages.staff.result-management.add-results')
    </x-route>

    <x-route name="course_results">
        @include('pages.staff.result-management.single-course-results')
    </x-route>

</x-template>