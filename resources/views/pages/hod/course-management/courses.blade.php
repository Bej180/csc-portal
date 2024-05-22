@php
    use App\Models\Course;

    $level = request()->get('level');
    $semester = request()->get('semester');

    $courses = Course::getCourses($level, $semester);

    $getcourse = false;
    $course_id = request()->get('course_id');
    $mobileClose = '';
    if ($course_id) {
        $getcourse = Course::find($course_id);
        $mobileClose = 'hidden lg:block';
    }
    $semester_data = $semester ? "'$semester'" : 'null';
    $level_data = $level ? "'$level'" : 'null';
    $active_id = $course_id ? "'$course_id'" : 'null';
    $staffs = \App\Models\Staff::latest()->get();
@endphp
<x-template nav="courses" name="dashboard" class="half">



    <div  class="full" ng-controller="AdminCoursesController" ng-init="init()">

        <div class="card">


            <form class="input-group pb-3">
                <input type="search" class="input" ng-model="searchtext" ng-keydown="enterSearch($event)"
                    placeholder="Search for Course..." />
                <button class="btn btn-primary btn-icon" ng-click="searchForCourse()"><i
                        class="fa fa-search"></i></button>

            </form>

            <div class="md:overflow-y-auto" infinite-scroll="loadMore()" >
                <form class="flex justify-between gap-3 w-full mb-4">
                    <div class="flex gap-3">
                        <div class="select mr-2">
                            <label>Choose Level</label>
                            <select name="level" id="level" ng-model="level" title="level" class="rounded"
                                ng-model="level" ng-change="loadCourseOnChange()" tips="Choose Level">
                                <option value="100">100 LVL</option>
                                <option value="200">200 LVL</option>
                                <option value="300">300 LVL</option>
                                <option value="500">500 LVL</option>
                            </select>
                        </div>

                        <div class="select">
                            <label>Choose Semester</label>
                            <select ng-disabled="!level" name="semester" id="semester" title="semester"
                                ng-model="semester" class="rounded" ng-change="loadCourseOnChange()"
                                tips="Choose Semester">
                                <option>HARMATTAN</option>
                                <option>RAIN</option>
                            </select>
                        </div>


                    </div>



                </form>
                <div ng-class="{xHide: courses.length > 0}" class="list">
                    @for ($i = 0; $i < 4; $i++)
                        <div
                            class="loading-skeleton flex card !flex-row border rounded-md overflow-clip cursor-pointer dark:border-gray-700 group-hover:bg-slate-500 dark:!bg-zinc-950">
                            <div class="w-24 skeleton"></div>
                            <div class="p-2 flex flex-col gap-2 justify-center flex-1">
                                <p class="text-transparent font-bold skeleton max-w-[70%]"> .</p>
                                <span class=" skeleton w-[55%]">.
                                </span>
                            </div>
                        </div>
                    @endfor
                </div>
                <div ng-class="{xHide: courses.length == 0}" class="grid grid-cols-3 gap-3">

                    <div ng-repeat="course in courses">
                        <div ng-clickx="display_course(course)" ng-class="{'active':course.id==active_id}"
                            class="group eachcourse !justify-between gap-2 card border rounded-lg overflow-clip cursor-pointer dark:border-gray-700 group-hover:bg-slate-500 relative dark:!bg-zinc-950">
                            <div class="flex gap-4 items-start">
                                <div class="avatar shrink-0" ng-bind="course.name.substring(0,1)"></div>
                                <div class="flex flex-col justify-center flex-1 bottom-0 relative w-full gap-1">
                                    <div class="flex justify-between">
                                        <p class="w-full flex-1 text-black font-bold overflow-hidden"><span
                                                ng-bind="course.name"></span></p>

                                        

                                        </span>
                                    </div>

                                    <p class="flex items-center gap-1">
                                        <span
                                            class="text-black/45 dark:text-gray-300 weight-400 text-sm pr-2 border-r border-r-slate-[var(--body-300)] "
                                            ng-bind="course.code"></span>
                                        <span class="divider"></span>
                                        <span class="text-body-200 weight-400"><span ng-bind="course.units"></span>
                                            Units</span>
                                    </p>
                                    <p>
                                    </p>

                                </div>
                            </div>
                          
                            <div class='text-xs'>
                                <p ng-if="course.cordinator">

                                    Cordinator: <b ng-bind="course.cordinator.user.name"></b>
                                </p>

                                <p ng-if="!course.cordinator">
                                    <button ng-if="allocate_now.indexOf(course.id) === -1" type="button"
                                        class="btn btn-secondary w-full" ng-click="showSelection(course.id)">
                                        Add Cordinator
                                    </button>
                                <div ng-if="allocate_now.indexOf(course.id) !== -1"
                                    ng-init="index=allocate_now.indexOf(course.id)">
                                    <div ng-click='allocate_now.splice(index, 1)'
                                        class="hover:text-primary font-semibold">Collapse</div>
                                    <div class="input-group w-full">
                                        <div class="flex-1 w-full">
                                            <select drop="top" class="input ignore"
                                                ng-model="allocate_course_to">
                                                @foreach ($staffs as $staff)
                                                    <option value="{{ $staff->id }}">{{ $staff->user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <button ng-disabled="allocate_course_to.length==0" type="button"
                                                class="btn btn-icon btn-primary btn-adaptive w-full"
                                                controller="makeCourseCordinator(this, course.id, $index)"><i
                                                    class="fa fa-plus icon"></i></button>
                                        </div>
                                    </div>
                                </div>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>



    </div>


    {{-- @include('pages.admin.course-management.add') --}}
    @include('pages.admin.course-management.show-course')
    </div>




    <script src="{{ asset('scripts/upload.js') }}"></script>

</x-template>
