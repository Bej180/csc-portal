<x-template ng-controller='TechnologistController' ng-init="initializePage()">

    <main class="half" ng-controller='TechnologistAttendanceController' ng-init="initializeAttendance()">
        <section class="half-60">
            <x-route name="index" class="card">

                <div class="card-header">
                    <div class="card-title">
                        <i class="material-symbols-rounded">computer_rounded</i> Attendance
                    </div>
                </div>

                <div class="card-body pt-3">

                    <div class="flex flex-col gap-5">
                        <div class="shadow-sm rounded-md bg-white border border-zinc-200 p-5"
                            ng-repeat="attendance in attendance_lists">
                            <div class="panel-header font-bold">
                                <div>
                                    <span ng-bind="attendance.course.code"></span> PRACTICAL
                                </div>
                            </div>
                            <div class="panel-body gap-2 flex justify-between">
                                <div>
                                    <p>Title: <b ng-bind="attendance.title"></b></p>
                                    <p ng-if='attendance.students.length === 0'>No Student Enrolled in course</p>
                                    <i ng-if='attendance.students.length > 0' class="text-xs opacity-50">
                                        <span ng-bind="attendance.students.length"></span> student
                                        <span ng-if="attendance.students.length>1">s</span>
                                        <span ng-if="attendance.present>0||attendance.absent>0">
                                          (Present: <span ng-bind="attendance.present"></span>,
                                        Absent: <span ng-bind="attendance.absent"></span>,
                                        Unmarked: <span ng-bind="attendance.unmarked"></span>)
                                        </span>
                                    </i>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-primary"
                                        ng-click="markAttendance(attendance)">Mark Attendance</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </x-route>
            <x-route name="mark_attendance" class="card">

                <div class="card-header">
                    <div class="card-title"><i class="fa fa-chevron-left hover:text-primary"
                            ng-click="route('index')"></i>
                        {% active_attendance_list.course.code %} PRACTICAL ATTENDANCE
                        (Students: <b class="text-primary" ng-bind="active_attendance_list.students.length"></b>)
                    </div>
                    <div>Title: <b ng-bind="active_attendance_list.title"></b></div>
                </div>


                <div class="mt-4 table-container responsive-table no-zebra !shadow-none overflow-auto">
                    <table>
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Name</th>
                                <th>Reg Num</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="student in active_attendance_list.students track by student.id">
                                <td ng-bind="$index + 1" class="!text-center"></td>
                                <td ng-bind="student.student.user.name"></td>
                                <td ng-bind="student.reg_no"></td>
                                <td class="flex items-center justify-end gap-2">
                                    <button ng-disabled="student.status=='PRESENT'"
                                        class="hover:underline disabled:font-bold opacity-30 disabled:opacity-100 disabled:text-primary flex gap-1 items-center"
                                        type="button"
                                        ng-click="mark( $event, 'PRESENT', active_attendance_list.id, student)">
                                        PRESENT
                                    </button>
                                    <button ng-disabled="student.status=='ABSENT'"
                                        class="hover:underline opacity-30 disabled:opacity-100 disabled:font-bold disabled:text-red-500 flex gap-1 items-center"
                                        type="button"
                                        ng-click="mark( $event, 'ABSENT', active_attendance_list.id, student)">
                                        ABSENT
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>

            </x-route>
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
      </section> --}}
        <section ng-if="!enrollments">
            <div class="card">

                <div class="card-caption">
                    <div class="card-title">Add Attendance</div>
                </div>
                <form class="card-body md:max-h-[calc(-12.5rem+100dvh)]">
                    <div class="flex flex-col">

                        <div class="font-bold text-sm text-[--surface-500]" for="course">Title</div>
                        <input type="text" class="input mt-1" ng-model="attendance.title"
                            placeholder="Enter Title" />
                    </div>
                    <div class="flex flex-col gap-3">
                        @if (count($courses) > 1)
                            <div class="flex flex-col gap-1 mt-2">
                                <div class="font-bold text-sm text-[--surface-500]" for="course">Course</div>
                                <select placeholder="Select a course" ng-model="attendance.course_id">
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" ng-init="attendance.course_id={{ $courses[0]->id }}"
                                ng-model="attendance.course_id" value="{{ $courses[0]->id }}" />
                        @endif
                        <div class="flex flex-col gap-1 mt-2">
                            <div class="font-bold text-sm text-[--surface-500]" for="session">Session</div>
                            <select drop="top" ng-model="attendance.session" class="input">
                                @foreach ($sessions as $session)
                                    <option>{{ $session->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="button" class="btn btn-primary mt-2" controller="createAttendanceList()">Start
                            Attendance</button>
                    </div>
                </form>
            </div>
        </section>

        @include('pages.technologist.add-lab-score')

    </main>

</x-template>
