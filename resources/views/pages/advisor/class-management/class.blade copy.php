@php

    $advisor = auth()->user()->advisor;
    $class = $advisor->class;
    $class_id = request()->get('class_id');
    if ($class_id) {
        $class = $advisor->classes()->where('id', '=', $class_id)->get()?->first();
    }
    dd($class);
    $invitationLink = invitation_url($class->token);
    $classes = $advisor
        ->classes()
        ->where('id', '!=', $class?->id)
        ->get();

    $students = $class->students;

@endphp


<x-template nav="class">
    <div ng-controller="ClassController"
        ng-init="initiate({{ $class->id }}, {{ $invitationLink ? "'$invitationLink'" : 'false' }})">
        <div ng-init="init()" ng-controller="StudentController"
            class="lg:flex gap-5 px-0 justify-between items-stretch max-h-full min-h-full overflow-hidden">

            <div class="lg:flex-1 relative " ng-class="{'hidden lg:block':student}">
                <div class="scroller relative">
                    <div class="flex justify-between items-center">
                        <div class="lg:invisible flex items-center cursor-pointer" ng-click="back()">
                            <span class="material-symbols-rounded">arrow_back</span>
                            <span>Back</span>
                        </div>



                    </div>



                    <div ng-show="!student" ng-class="{'lg-visible': !student}" view-student-skeleton>
                    </div>
                    <div ng-show="student">

                        <div class="">
                            <div class="flex flex-col lg:m-5">
                                <div
                                    class=" flex flex-col lg:flex-row text-center justify-center gap-5 items-center lg:text-left lg:justify-start p-4">
                                    <img src="{% student.image %}" class="w-28 h-28 object-cover rounded-full" />
                                    <div>
                                        <p class="text-2xl lg:text-3xl font-bold mb-3" ng-bind="student.user.name"></p>
                                        <p class="font-bold" ng-bind="student.reg_no"></p>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div>
                                        <div class="p-4 my-2">
                                            <div class="font-bold mb-4">Basic Information</div>
                                            <div class="flex flex-col lg:flex-row justify-between flex-wrap gap-3">
                                                <div class="flex lg:flex-col">
                                                    <span>Phone</span>
                                                    <span class="font-semibold" ng-bind="student.user.phone"></span>
                                                </div>


                                                <div class="flex lg:flex-col gap-2">
                                                    <span>Email</span>
                                                    <span class="font-semibold" ng-bind="student.user.email"></span>
                                                </div>


                                                <div class="flex lg:flex-col gap-2">
                                                    <span>Level</span>
                                                    <span class="font-semibold" ng-bind="student.level"></span>
                                                </div>


                                                <div class="flex lg:flex-col gap-2">
                                                    <span>CGPA</span>
                                                    <span class="font-semibold" ng-bind="student.cgpa"></span>
                                                </div>



                                                <div class="flex lg:flex-col gap-2">
                                                    <span>Address</span>
                                                    <span class="font-semibold" ng-bind="student.address"></span>
                                                </div>



                                            </div>
                                        </div>


                                        <div class="p-4 my-2">
                                            <div class="font-bold mb-4">Progress</div>
                                            <div class="mt-2 lg:grid grid-cols-3 lg:gap-5">
                                                <!-- DASHBOARD CARD -->
                                                <div
                                                    class="overflow-hidden grid-span-1 card-blue rounded-md p-4 flex flex-col justify-between ">
                                                    <div class="flex items-center gap-2">
                                                        <span class="material-symbols-rounded">
                                                            groups
                                                        </span>
                                                        <p class="text-lg">Students</p>
                                                    </div>
                                                    <div class="flex justify-end">
                                                        <p class="text-[2.5rem] font-semiboold">20</p>
                                                    </div>
                                                </div>

                                                <div
                                                    class="overflow-hidden grid-span-1 card-green rounded p-4 flex flex-col justify-between">
                                                    <div class="flex items-center gap-2">
                                                        <span class="material-symbols-rounded">
                                                            auto_stories
                                                        </span>
                                                        <p class="text-lg">Semester Courses</p>
                                                    </div>
                                                    <div class="flex justify-end">
                                                        <p class="text-[2.5rem] font-semiboold">71</p>
                                                    </div>
                                                </div>


                                                <div
                                                    class="overflow-hidden grid-span-1 card-red rounded p-4 flex flex-col justify-between">
                                                    <div class="flex items-center gap-2">
                                                        <span class="material-symbols-rounded">
                                                            bar_chart
                                                        </span>
                                                        <p class="text-lg">Results Uploaded</p>
                                                    </div>
                                                    <div class="flex justify-end ">
                                                        <p class="text-[2.5rem] font-semiboold">49</p>
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

            </div>
            <div ng-class="{'hidden lg:block': student}"
                class="lg:w-[380px] lg:bg-zinc-50 lg:border-r lg:border-zinc-200 dark:border-none dark:bg-zinc-950/50">
                <div class="scroller relative" ng-controller="SearchController">


                    <div ng-hide="query" class="boxx lg:rounded-md mx-3 lg:overflow-clip !shadow-none lg:p-4">

                        <div ng-if="invitationLink" class="">

                            <div class="input">


                                <div>
                                    <span class="text-slate-400">Invitation Link:</span>
                                    <div ng-bind="invitationLink" contenteditable class="text-xs italic"></div>
                                </div>
                                <div class="flex justify-between gap-1 mt-2">
                                    <a class="link" href="javascript:;" data-clipboard-action="copy"
                                        data-clipboard-target="#invitation-link"><i class="far fa-copy"></i> Copy</a>
                                    <a class="link" ng-click="withdrawInviteLink()"><i class="fas fa-cut"></i> Withdraw</a>
                                    <a class="link" ng-click="generateInviteLink('Regenerated Invitation Link')"><i class="fa fa-undo"></i> Regenerate</a>
                                </div>
                                <input type="hidden" id="invitation-link" ng-model="invitationLink" />



                            </div>

                        </div>

                        <div ng-if="!invitationLink" class="flex justify-end">
                            <button type="button" ng-click="generateInviteLink('Generated Invitation Link')"
                                class="btn btn-primary input-sm">Generate Invitation Link</button>
                        </div>
                        <div class="box-body dark:text-white flex flex-col gap-4">
                            <div class="relative">
                                <h1 class="font-bold text-2xl sticky">CSC {{ $class->name }}</h1>
                                <div> {{ $class->students()->count() }} Students</div>
                                <div class="flex justify-between">
                                    <div>Admission Year: <span class="text-semibold">{{ $class->start_year }}</span>
                                    </div>
                                    <div>Graduation Year: <span class="text-semibold">{{ $class->end_year }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="font-semibold">My Classes:</div>
                                <div>
                                    @foreach ($classes as $cl)
                                        <span class="pill link"><a
                                                href="?class_id={{ $cl->id }}">{{ $cl->name }}</a></span>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>

                    <form style="width:100%;position: sticky;top: -20px;background: rgb(100, 100, 100, .5);z-index: 1;"
                        class="flex items-center justify-between gap-2 w-full flex-wrap p-5">

                        <div class="flex-1">
                            <input type="search" ng-model="query" class="input bg-white dark:bg-black !rounded-lg"
                                ng-keyup="search()" placeholder="Enter Student Name or Reg No"
                                ng-keydown="keyDown()" />
                        </div>

                    </form>



                    <div class="student-list" ng-show="!typing && !query">
                        @foreach ($students as $student)
                            <div student_id="{{ $student->id }}" ng-click="show($event)" class="student">
                                <x-profile-pic :user="$student" alt="student_pic"
                                    class="w-16 h-16 rounded-md object-cover" />
                                <div class="flex-1">
                                    <div class="font-2xl font-bold">{{ $student->user->name }}</div>
                                    <div class="text-sm">{{ $student->reg_no }}</div>
                                    <div class=" text-xs">

                                        <span class="pr-2 border-r border-slate-500/50">{{ $student->level }}
                                            Level</span><span class="pl-2">{{ $student->calculateCGPA() }}
                                            CGPA</span>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="student-list" ng-show="query">
                        <div ng-show="typing || results.length === 0">
                            @for ($i = 0; $i < 4; $i++)
                                <div class="student loading-skeleton flex gap-4 items-center">



                                    <div class="skeleton w-16 h-16 rounded-md object-cover min-h-16"></div>
                                    <div class="flex-1 flex flex-col gap-2">
                                        <div class="skeleton font-2xl font-bold w-[40%]"></div>
                                        <div class="skeleton text-sm w-[25%]"></div>
                                        <div class="skeleton text-xs w-[30%]"></div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                        <div ng-show="!typing && results.length > 0"
                            ng-repeat="student in results track by student.id">

                            <div student_id="{% student.id %}" ng-click="show($event)" class="student">
                                <img ng-src="/profilepic/{% student.id %}" alt="student_pic"
                                    class="w-16 h-16 rounded-md object-cover" />
                                <div class="flex-1">
                                    <div class="font-2xl font-bold">{% student.name %}</div>
                                    <div class="text-sm">{% student.reg_no %}</div>
                                    <div class=" text-xs">

                                        <span class="pr-2 border-r border-slate-500/50">{% student.level %}
                                            Level</span><span class="pl-2">{% student.cgpa %} CGPA</span>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('plugins/clipboard/clipboard.min.js') }}"></script>

</x-template>
{{-- <x-template title="Class List" nav='class'>
    <div class="scroller">
        <div class="w-full lg:col-span-1">
            <x-page-header>
                <span>Students</span>

                <button type="button" class="btn-primary transition text-sm">
                    Print Class List
                </button>

            </x-page-header>


            <div id="class-list-container" class="rounded px-3 py-3 overflow-y-auto mt-4">


                <form action="" id="student-search-form"
                    class="row-span-1 flex items-center gap-1  lg:flex-col lg:items-start lg:row-span-1">


                    <div
                        class="flex items-center justify-between rounded border border-[var(--primary)] w-full md:w-auto lg:w-full">
                        <input type="search" name="searchStudent" id="student-search" class="input m-0 full">
                        <button type="submit" class="btn-primary !py-1 !m-0">
                            <span class="material-symbols-rounded">search</span>
                        </button>
                    </div>
                </form>


                <ul class="row-span-6 mt-4 border-b border-b-[var(--black-100)] overflow-y-auto lg:row-span-5">
                    <table class="responsive-table">
                        <thead>
                            <tr>
                                <td></td>
                                <td>Name</td>
                                <td>Reg Number</td>
                                <td>Level</td>
                                <td>CGPA</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                <tr>
                                    <td>
                                        <img src="{{ asset('images/user.jpg') }}" alt="student_img"
                                            class="w-10 h-10 object-cover rounded-full" />
                                    </td>
                                    <td>{{ $student->user->name }}</td>
                                    <td>{{ $student->reg_no }}</td>
                                    <td>{{ $student->level }}</td>
                                    <td>{{ $student->calculateCGPA() }}
                                </tr>
                            @endforeach
                        </tbody>


                    </table>
                </ul>

            </div>
        </div>

        <div x-show="detailsOpen" id="full-student-details"
            class="fixed top-12 left-[50%] -translate-x-[50%] w-[100dvw] h-[100dvh] pb-16 overflow-y-auto border border-slate-300 bg-white
        lg:relative lg:col-span-3 lg:w-auto lg:rounded lg:pb-4 lg:top-0 mt-[.5rem] lg:h-[unset]">
            <div class="flex gap-4 items-center p-4 bg-primary-50 rounded-t h-36 cursor-context-menu relative">
                <span @click="detailsOpen = false"
                    class="material-symbols-rounded grid center overflow-hidden w-5 h-5 rounded text-red-500 absolute top-0 right-2 z-50 cursor-pointer lg:-z-10">close</span>
                <img src="../../assets/images/user.jpg" alt="student_img" class="w-28 h-28 object-cover rounded-full">
                <div class="z-20">
                    <h1 class="font-bold text-xl text-body-500">Student Full Name</h1>
                    <p class="text-body-300">Student ID</p>
                </div>
                <img src="../../assets/images/frame.svg" alt="frame" class="absolute right-0 bottom-0 w-28">
            </div>

            <div class="p-4">
                <div class="border border-slate-300 rounded p-2">
                    <p class="text-sm font-semibold text-secondary-800">Basic Information</p>

                    <ul class="text-sm flex gap-x-10 gap-y-5 flex-wrap whitespace-nowrap mt-2">
                        <li>
                            <p class="text-body-300">Phone</p>
                            <p class="text-body-400 font-semibold">08012345678</p>
                        </li>
                        <li>
                            <p class="text-body-300">Email</p>
                            <p class="text-body-400 font-semibold">amalagucosmos@example.com</p>
                        </li>
                        <li>
                            <p class="text-body-300">Level</p>
                            <p class="text-body-400 font-semibold">500</p>
                        </li>
                        <li>
                            <p class="text-body-300">CGPA</p>
                            <p class="text-body-400 font-semibold">4.55</p>
                        </li>
                        <li>
                            <p class="text-body-300">S/N</p>
                            <p class="text-body-400 font-semibold">123</p>
                        </li>
                        <li class="md:w-full">
                            <p class="text-body-300">Address</p>
                            <p class="text-body-400 font-semibold">148, Something Street, Owerri, Nigeria</p>
                        </li>
                    </ul>
                </div>

                <div id="chart-container" class="border border-slate-300 rounded p-2 mt-2 h-72 overflow-y-auto">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-secondary-800">Progress</p>
                        <div class="flex items-center gap-4 text-xs font-semibold text-body-400">
                            <div class="flex gap-1">
                                <label for="bar">bar chart</label>
                                <input type="radio" name="chartType" id="bar" checked>
                            </div>

                            <div class="flex gap-1">
                                <label for="line">line chart</label>
                                <input type="radio" name="chartType" id="line">
                            </div>
                        </div>
                    </div>


                    <!-- Create an API to get the student's GPA and I will use it to populate these charts using Javascript -->
                    <canvas id="bar-chart" class="text-xs text-body-400 mt-2 w-full overflow-auto"></canvas>

                    <canvas id="line-chart" class="text-xs text-body-400 mt-2 w-full overflow-auto hidden"></canvas>
                </div>
            </div>

            <div class="grid center -mt-4 select-none lg:hidden">
                <button @click="detailsOpen = false" type="button"
                    class="flex flex-col items-center text-secondary-800 hover:text-[var(--danger-600)]">
                    <span class="material-symbols-rounded overflow-hidden">expand_less</span>
                    <span class="text-sm font-semibold transition -mt-2">close</span>
                </button>
            </div>

        </div>
    </div>
</x-template> --}}
