
<x-template title="Results">

  <section class="p-5 w-dvw md:w-full" ng-controller="StaffLabResultsController" ng-init="initializePage()">
      <div class="tabview " role="tablist" ng-init="active_nav='pending'">
          <div class="tabview-nav-container">
              <div class="tabview-nav-content">
                  <ul class="tabview-nav">
                      <li class="tabview-header" role="presentation"  ng-class="{highlight: active_nav=='pending'}" ng-click="active_nav='pending'">
                          <a id="pv_id_174_0_header_action" class="tabview-nav-link tabview-header-action"
                              tabindex="-1" role="tab" aria-selected="false" aria-controls="pv_id_174_0_content">
                              <h1>Pending</h1>
                              <span ng-if="pending_results.length>0" class="badge badge-no-gutter badge-danger -mt-4" ng-bind="pending_results.length"></span>
                          </a>
                      </li>
                      <li class="tabview-header" role="presentation" ng-class="{highlight: active_nav=='approved'}" ng-click="active_nav='approved'">
                          <a class="tabview-nav-link tabview-header-action"
                              tabindex="0" role="tab" aria-selected="true" aria-controls="pv_id_174_1_content">
                              <h1>Approved</h1>
                              <span class="badge badge-success -mt-4" ng-bind="approved_results.length" ng-if="approved_results.length>0"></span>
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
                                  <h1 class="">Pending Lab Scores</h1>
                                  <p class="text-[1rem] text-red-500 font-semibold" ng-bind="pending_results.length"></p>
                              </div>
                          </div>
                      </div>
                      <div>

                          <div class="flex items-center justify-between gap-3 pb-3">
                              <div class="input-group max-w-72">
                                  <input class="input" placeholder="Search..." />
                                  <button class="btn btn-primary btn-icon btn-adaptive" type="button">
                                      <i class="fa fa-search"></i>
                                  </button>
                              </div>
                              <div class="flex justify-content-center" ng-init="sort=false">
                                  <button class="btn-icon ignore" type="button" aria-haspopup="true" ng-click="sort=!sort"
                                      aria-controls="overlay_menu"  ng-class="{'btn-primary':sort, 'btn-secondary':!sort}">
                                      <i class="fa fa-sort icon"></i>
                                  </button>
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
                                      <tr ng-repeat="pending in pending_results track by pending.id">
                                          <td>{% pending.course.code %} Lab Score</td>
                                          <td ng-bind="pending.uploader.name"></td>
                                          <td ng-bind="pending.session"></td>
                                          <td ng-bind="pending.semester"></td>
                                          <td ng-bind="pending.level"></td>
                                          <td ng-bind="formatDate(pending.created_at)"></td>
                                          <td class="flex items-center justify-between gap-2">
                                              <button class="flex gap-1 items-center btn btn-secondary" type="button"
                                                  aria-label="View">
                                                  <span class="fa fa-eye"></span>
                                                  <span class="p-button-label">View</span>
                                              </button>
                                              <button controller="approveLabScore(pending)" class="btn btn-secondary whitespace-nowrap" type="button" aria-label="Approve">
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
                                  <p class="text-zinc-400 text-2xl"> NO PENDING LAB SCORE </p>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div ng-if="active_nav=='approved'" id="pv_id_174_1_content" class="tabview-panel" role="tabpanel"
                  aria-labelledby="pv_id_174_1_header_action" style="">
                  <div class="card ">
                      <div class="card-body">
                          <div class="card-caption">
                              <div class="card-title">
                                  <div class="flex items-center gap-5">
                                      <h1>Approved Lab Scores</h1>
                                      <p class="text-[1rem] text-primary font-semibold" ng-bind="approved_results.length"></p>
                                  </div>
                              </div>
                          </div>
                          <div class="card-content">
                              <div class="flex items-center justify-between gap-3 pb-3">
                                  <div class="input-group max-w-72">
                                      <input class="input " placeholder="Search..."><button
                                          class="btn btn-icon btn-primary btn-adaptive" type="button">
                                          <span class="fa fa-search"></span>
                                      </button>
                                  </div>
                                  <div class="flex justify-content-center"  ng-init="sort=false">
                                      <button class="btn-icon ignore" type="button" aria-haspopup="true" 
                                          aria-controls="overlay_menu" ng-click="sort=!sort" ng-class="{'btn-primary':sort, 'btn-secondary':!sort}">
                                          <span class="fa fa-sort icon"></span>
                                      </button>
                                  </div>
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
                                          <tr ng-repeat="approved in approved_results track by approved.id">
                                              <td ng-bind="approved.course.code"></td>
                                              <td ng-bind="approved.uploader.name"></td>
                                              <td ng-bind="approved.session"></td>
                                              <td ng-bind="approved.semester"></td>
                                              <td ng-bind="approved.level"></td>
                                              <td ng-bind="formatDate(approved.created_at)"></td>
                                              <td class="flex items-center justify-between gap-2">
                                                  <button class="flex gap-1 items-center btn btn-secondary" type="button"
                                                      aria-label="View">
                                                      <span class="fa fa-eye"></span>
                                                      <span class="p-button-label">View</span>
                                                  </button>
                                                  <button disabled class="btn btn-primary whitespace-nowrap">
                                                      <span class="fa fa-check fa-icon"></span> 
                                                      Approved
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
  </section>

</x-template>
