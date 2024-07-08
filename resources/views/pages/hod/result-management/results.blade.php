
<x-template title="Results" nav="results" controller="HODResultsController" ng-init="initializePage()">

    <x-route name="index" class="p-5 w-dvw md:w-full">
        <div class="tabview " role="tablist">
            <div class="tabview-nav-container">
                <div class="tabview-nav-content">
                    <ul class="tabview-nav">
                        <li class="tabview-header" role="presentation" ng-class="{highlight: active_nav=='approved'}" ng-click="active_nav='approved'">
                            <a class="tabview-nav-link tabview-header-action"
                                tabindex="0" role="tab" aria-selected="true">
                                <h1>Approved</h1>
                                <span class="badge badge-success" ng-bind="approved_results.length|more" ng-if="approved_results.length>0"></span>
                            </a>
                        </li>

                        <li class="tabview-header" role="presentation"  ng-class="{highlight: active_nav=='pending'}" ng-click="active_nav='pending'">
                            <a class="tabview-nav-link tabview-header-action"
                                tabindex="-1" role="tab" aria-selected="false">
                                <h1>Pending</h1>
                                <span ng-if="pending_results.length>0" class="badge badge-no-gutter badge-danger" ng-bind="pending_results.length|more"></span>
                            </a>
                        </li>
                        
                    </ul>
                </div>
            </div>
            <div class="tabview-panels">
                <div ng-if="active_nav=='pending'" class="tabview-panel" role="tabpanel" aria-labelledby="pv_id_174_0_header_action">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <div class="flex items-center gap-5">
                                    <h1 class="">Pending Results</h1>
                                    <p class="text-[1rem] text-red-500 font-semibold" ng-bind="pending_results.length"></p>
                                </div>
                            </div>
                        </div>
                        <div>

                            <div class="flex items-center justify-between gap-3 pb-3">
                                <div class="input-group max-w-72">
                                    <input class="input" placeholder="Search..." ng-model="search_input"/>
                                    <button ng-click="SearchIn(search_input)" class="btn btn-primary btn-icon btn-adaptive" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                                <div class="flex justify-content-center">
                                    <toggle ng-model="sorting.order" ng-change="sortResults()"
                                        options="{ASC:'fa fa-sort-alpha-up', DESC:'fa fa-sort-alpha-down'}"
                                        class="btn btn-primary">
                                        <i class="fa fa-sort icon"></i>
                                    </toggle>
                                </div>
                            </div>
                            <div class="table-container responsive-table overflow-auto md:h-[calc(-20rem+100dvh)]">
                                <table ng-if="pending_results.length > 0">
                                    <thead>
                                        <tr>
                                            <th>Course</th>
                                            <th>Staff</th>
                                            <th>Session</th>
                                            <th>Semester</th>
                                            <th>Level</th>
                                            <th>Date</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="pending in pending_results">
                                            <td ng-bind="pending[0].course.code"></td>
                                            <td ng-bind="pending[0].updater.name"></td>
                                            <td ng-bind="pending[0].session"></td>
                                            <td ng-bind="pending[0].semester"></td>
                                            <td ng-bind="pending[0].level"></td>
                                            <td ng-bind="formatDate(pending[0].created_at)"></td>
                                            <td class="flex items-center justify-end gap-2">
                                                <button ng-click="viewResults(pending)" class="flex gap-1 items-center btn btn-secondary" type="button"
                                                    aria-label="View">
                                                    <span class="fa fa-eye"></span>
                                                    <span class="p-button-label">View</span>
                                                </button>
                                                <button controller="approveResult(pending[0])" class="btn btn-primary whitespace-nowrap" type="button" aria-label="Approve">
                                                    <span class="fa fa-check fa-icon"></span> 
                                                    Approve
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div ng-if="pending_results.length === 0 && !initialized">
                                    Loading
                                </div>
                                <div ng-if="pending_results.length === 0 && initialized" class="grid place-items-center">
                                    <img src="{{ asset('svg/404.svg') }}" class="w-52"/>
                                    <p class="text-zinc-400 text-2xl"> NO PENDING RESULTS </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div ng-if="active_nav=='approved'" class="tabview-panel" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-caption">
                                <div class="card-title">
                                    <div class="flex items-center gap-5">
                                        <h1>Approved Results</h1>
                                        <p class="text-[1rem] text-primary font-semibold" ng-bind="approved_results.length"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="flex items-center justify-between gap-3 pb-3">
                                    <div class="input-group max-w-72">
                                        <input class="input" placeholder="Search..." ng-model="search_input"><button ng-click="SearchIn(search_input)"
                                            class="btn btn-icon btn-primary btn-adaptive" type="button">
                                            <span class="fa fa-search"></span>
                                        </button>
                                    </div>
                                    
                                        <toggle ng-model="sorting.order" ng-change="sortResults()"
                                            options="{ASC:'fa fa-sort-alpha-up', DESC:'fa fa-sort-alpha-down'}"
                                            class="btn btn-primary">
                                            <i class="fa fa-sort icon"></i>
                                        </toggle>
                                </div>
                                <div class="responsive-table table-container overflow-scroll md:h-[calc(-20rem+100dvh)]">
                                    
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Course</th>
                                                <th>Staff</th>
                                                <th>Session</th>
                                                <th>Semester</th>
                                                <th>Level</th>
                                                <th>Date</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="(reference_id, approved) in approved_results">
                                                
                                                <td ng-bind="approved[0].course.code"></td>
                                                <td ng-bind="approved[0].updater.name"></td>
                                                <td ng-bind="approved[0].session"></td>
                                                <td ng-bind="approved[0].semester"></td>
                                                <td ng-bind="approved[0].level"></td>
                                                <td ng-bind="formatDate(approved[0].created_at)"></td>
                                                <td class="flex items-center justify-end gap-2">
                                                    <button ng-click="viewResults(approved)" class="flex gap-1 items-center btn btn-secondary" type="button"
                                                        aria-label="View">
                                                        <span class="fa fa-eye"></span>
                                                        <span class="p-button-label">View</span>
                                                    </button>
                                                    <button ng-click="disapproveResult(approved[0])" class="btn btn-danger whitespace-nowrap">
                                                        <span class="fa fa-times fa-icon"></span> 
                                                        Disapprove
                                                    </button>

                                                    
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-route>

    <x-route name="view_results">
        <div class="columns">
            <section class="full">
                <div class="my-3 text-2xl hover:text-primary" ng-click="Route.back()">
                    <i class="fa fa-chevron-left"></i> Results
                </div>
                @include('pages.results.one-course-result')
            </section>
    </x-route>

</x-template>
