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
<x-template nav="courses" name="dashboard">



    <div ng-controller="AdminCoursesController" ng-init="init()"
        class="p-2 lg:!p-0 lg:flex justify-between items-stretch max-h-full overflow-hidden">

        <div class="lg:flex-1 right-column" ng-class="{'hidden lg:block':!active_course}">
            <div class="md:flex flex-col justify-between min-h-full dark:bg-zinc-800">

                <div class="p-4">


                    <form class="card" ng-action="addCourse()">

                        <div class="card-header">
                            <div class="card-title px-4">
                                Create Course
                            </div>
                        </div>
                        <div class="card-body md:h-[calc(-13rem+100dvh)]">
                            <div class="paragraph p-4 !pb-0 !text-red-500">
                                Fill Course Details Below
                            </div>

                            <div class="pb-5">

                                <div class="px-4 pb-4">

                                    <div class="flex flex-col gap-4">
                                        <div class="flex gap-2">
                                            <div class="flex-1">
                                                <input class="input" ng-model="data.name" placeholder="Course Title"
                                                    name="name" id="name" />

                                            </div>
                                            <div class="w-[110px]">
                                                <input class="input text-center" ng-model="data.code"
                                                    placeholder="Course code" name="code"
                                                    ng-change="suggestLevelAndSemester()" id="code"
                                                    autocomplete="off" />
                                            </div>
                                        </div>


                                        <div class="lg:flex gap-1 justify-between items-center">

                                            <div class="flex-1">
                                                <label>Level</label>
                                                <select class="input" ng-model="data.level" name="level"
                                                    placeholder="Level" id="level">
                                                    <option value="100">100 LVL</option>
                                                    <option value="200">200 LVL</option>
                                                    <option value="300">300 LVL</option>
                                                    <option value="400">400 LVL</option>
                                                    <option value="500">500 LVL</option>
                                                </select>

                                            </div>

                                            <div class="flex-1">
                                                <label>Semester</label>
                                                <select class="input" ng-model="data.semester" name="semester"
                                                    placeholder="Semester" id="semester">
                                                    <option value="HARMATTAN">
                                                        HARMATTAN</option>
                                                    <option value="RAIN">RAIN</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label>Course Option</label>
                                                <select drop="bottom-right" class="input"  ng-model="data.option" ng-disabled="!data.code">
                                                    <option selected>COMPULSORY</option>
                                                    <option>ELECTIVE</option>
                                                </select>
                                            </div>



                                        </div>

                                        <div ng-if="data.level && data.semester && data.level != 100" class="flex-1">
                                            <div class="flex gap-3">
                                                <div class="flex-1">
                                                    <input class="input"
                                                        placeholder="Prerequisites:(Enter Course Code or Title)"
                                                        type="text" ng-model="data.prerequisites" />
                                                </div>
                                                <button type="button" class="btn btn-icon btn-primary"><i
                                                        class="fa fa-search"
                                                        ng-click="displayPrequisiteCourses()"></i></button>
                                            </div>
                                            <div ng-show="prerequisite_courses.length > 0">
                                                <div class="paragraph mt-3 !mb-0">Select Prerequisites:</div>
                                                <div class="grid grid-cols-3 gap-2 justify-between">

                                                    <div ng-click="toggleSelectPrerequesiteCourse(course)"
                                                        ng-repeat="course in prerequisite_courses"
                                                        class="bg-gray-300 px-3 py-1.5 rounded-md row-span-1 text-center cursor-pointer"
                                                        ng-class="{'text-white primary-bg': courseIndex(course.code) >= 0}"
                                                        ng-bind="course.code">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                       

                                        <div class="flex-1 flex">
                                            <textarea placeholder="Type course outline here" ng-model="data.outline" name="outline" rows="4"
                                                class="input h-48" id="outline"></textarea>
                                        </div>

                                    </div>


                                </div>

                                <div class="p-4">

                                    <div class="flex gap-3 justify-between">

                                        <div class="flex-1 flex flex-col">
                                            <label>Test Units</label>
                                            <select drop="top" class="input" ng-model="data.test" name="test"
                                                manual="true" id="test" ng-change="updateUnits()">
                                                <option value="0">No Test</option>
                                                <option value="1">1 UNIT</option>
                                                <option value="2">2 UNITS</option>
                                                <option value="3">3 UNITS</option>
                                                <option value="4">4 UNITS</option>
                                                <option value="5">5 UNITS</option>
                                            </select>

                                        </div>
                                        <div class="flex-1 flex flex-col">
                                            <label>Lab Units</label>
                                            <select drop="top" class="input"  ng-change="updateUnits()" ng-model="data.lab" name="lab"
                                                id="lab">
                                                <option value="0">No Lab</option>
                                                <option value="1">1 UNIT</option>
                                                <option value="2">2 UNITS</option>
                                                <option value="3">3 UNITS</option>
                                                <option value="4">4 UNITS</option>
                                                <option value="5">5 UNITS</option>
                                            </select>

                                        </div>
                                        <div class="flex-1 flex flex-col">
                                            <label>Exam units</label>
                                            <select drop="top" class="input" ng-model="data.exam"  ng-change="updateUnits()"
                                                type="number" name="exam" id="exam">
                                                <option value="1">1 UNIT</option>
                                                <option value="2">2 UNITS</option>
                                                <option value="3">3 UNITS</option>
                                                <option value="4">4 UNITS</option>
                                                <option value="5">5 UNITS</option>
                                            </select>
                                        </div>

                                        <div class="w-12">
                                            <input type="text" class="input text-center" disabled readonly ng-model="data.units"/>
                                        </div>

                                    </div>

                                </div>




                                <div class="flex items-center gap-2 px-2.5">
                                    <input type="checkbox" id="check" ng-model="check" required
                                        class="checkbox peer" name="check" id="check" /> <label
                                        class="peer-checked:font-bold text-sm" for="check">Have
                                        you verified the above details are correct?</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer flex justify-end items-center">

                            <button class="btn btn-primary" type="submit" ng-disabled="!check">
                                Add Course
                            </button>

                        </div>


                    </form>
                </div>
            </div>
        </div>

        <div ng-class="{'hiddenx lg:block':active_course}" class="left-column lg:w-[60%] ">

            <div class="lg:p-8">


                <form class="input-group pb-3">
                    <input type="search" class="input" ng-model="searchtext" ng-keydown="enterSearch($event)"
                        placeholder="Search for Course..." />
                    <button class="btn btn-primary btn-icon" ng-click="searchForCourse()"><i
                            class="fa fa-search"></i></button>
    
                </form>
    
                <div class="md:overflow-y-auto md:h-[calc(-10rem+100dvh)]" infinite-scroll="loadMore()">
                    <form class="flex justify-between gap-3 w-full mb-4">
                        <div class="flex gap-3">
                            <div class="select mr-2">
                                <label>Choose Level</label>
                                <select name="level" id="level" ng-model="level" title="level" class="rounded"
                                    ng-model="level" ng-change="init()" tips="Choose Level">
                                    <option value="100">100 LVL</option>
                                    <option value="200">200 LVL</option>
                                    <option value="300">300 LVL</option>
                                    <option value="500">500 LVL</option>
                                </select>
                            </div>
    
                            <div class="select">
                                <label>Choose Semester</label>
                                <select ng-disabled="!level" name="semester" id="semester" title="semester"
                                    ng-model="semester" class="rounded" ng-change="init()"
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
                    <div ng-class="{xHide: courses.length == 0}" class="list">
    
                        <div ng-repeat="course in courses track by course.id">
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
                                                <select drop="top" class="input ignorex"
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
                                <div class="flex mt-3 gap-2">
                                    <div class="flex-1">
                                        <button class="w-full btn btn-primary" ng-click="viewCourse(course)"><i class="fa fa-eye"></i> View</button>
                                    </div>
                                    <div class="flex-1"> 
                                        <button ng-click="deleteCourse(course)" class="w-full btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
                                    </div>
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




    <style>
        #main-slot {
            padding: 0px;
        }

        html:not(.dark) #main-slot {
            background: #fff;
        }

        html:not(.dark) .left-column {
            background: rgb(250, 250, 250);
        }

        html:not(.dark) .right-column {
            background: rgba(228, 228, 231, 0.6);
        }
    </style>
    <script src="{{ asset('scripts/upload.js') }}"></script>

</x-template>
