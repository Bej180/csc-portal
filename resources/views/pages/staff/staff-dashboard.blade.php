
<x-template title="Staff Dashboard">

    <main class="w-full h-full overflow-y-scroll md:flex">
        <section
            class="h-full p-5 cursor-context-menu w-full md:max-w-96 lg:max-w-[25rem] overflow-y-scroll flex flex-col justify-between gap-5">
            <div>
                <div class="panel">
                    <div class="panel-header">
                        <div><x-profile-pic :user="$auth" alt="user-img"
                                class="aspect-square w-20 rounded-full object-cover" />
                            <div>
                                <p class="font-semibold text-2xl">{{ $auth->name }}</p>
                                <p class="font-medium text-[--primary-700]">{{ $myAccount->staff_id }}</p>
                                <p class="text-sm">STAFF</p>
                            </div>
                        </div>
                        <div class="panel-icons"></div>
                    </div>
                    <div id="pv_id_161_content" class="p-toggleable-content" role="region"
                        aria-labelledby="pv_id_161_header">
                        <div class="panel-content"></div>
                    </div>
                </div>
            </div>
            <div class="mt-3 lg:mt-0">
                <div class="card h-full">

                    <div class="card-body">
                        <div class="card-caption">
                            <div class="card-title">Overview</div>
                        </div>
                        <div class="card-content">
                            <section class="grid gap-4">
                                <div
                                    class="bg-[--primary-200] p-4 flex flex-col justify-between overflow-hidden rounded-md min-h-32">
                                    <header class="w-full flex items-center justify-between">
                                        <p class="font-medium uppercase">results uploaded</p><span
                                            class="material-symbols-rounded">book_2</span>
                                    </header>
                                    <div>
                                        <h1 class="font-medium text-4xl">0</h1>
                                        <h1 class="font-medium text-4xl"></h1>
                                    </div>
                                </div>
                                <div
                                    class="bg-[--yellow-200] p-4 flex flex-col justify-between overflow-hidden rounded-md min-h-32">
                                    <header class="w-full flex items-center justify-between">
                                        <p class="font-medium uppercase">general info</p><span
                                            class="material-symbols-rounded">info</span>
                                    </header>
                                    <div>
                                        <h1 class="font-medium text-4xl"></h1>
                                        <h1 class="font-medium text-4xl"></h1>
                                    </div>
                                    <div>
                                        <p class="text-sm">Session: <span class="text-lg font-medium" ng-bind="configuration.active_session"></span>
                                        </p>
                                        <p class="text-sm">Semester: <span class="text-lg font-medium" ng-bind="configuration.active_semester"></span></p>
                                    </div>
                                </div>

                                @if($pendingLabScores == 0)
                                <div class="bg-[--blue-200] p-4 flex flex-col justify-between overflow-hidden rounded-md min-h-32">
                                    <header class="w-full flex items-center justify-between">
                                        <p class="font-medium uppercase">Pending Lab Scores</p>
                                        <i class="material-symbols-rounded">checklist_rounded</i>
                                    </header>
                                    <div>
                                        <h1 class="font-medium text-4xl">{{$pendingLabScores}}</h1>
                                        <h1 class="font-mediumtext-4xl"></h1>
                                    </div>
                                </div>
                                @endif
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="h-full w-dvw p-5 cursor-context-menu md:w-full md:flex-grow overflow-hidden">
            <div class="card h-full">
                <div class="card-body">
                    <div class="card-caption">
                        <div class="card-title">
                            <h1>Your Courses: <span
                                    class="text-lg text-[--primary-color]">{{ $courses->count() }}</span></h1>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="overflow-y-scroll" style="height: calc(-12.5rem + 100dvh);">
                            <div class="list">
                                @foreach ($courses as $course)
                                    <div class="card border w-full">
                                        <div class="card-body">
                                            <div class="card-caption">
                                                <div class="card-title">
                                                    <div class="flex items-center gap-1 w-full">
                                                        <div class="avatar avatar-circle avatar-lg shrink-0"
                                                            style="background-color: rgb(222, 233, 252); color: rgb(26, 37, 81);">
                                                            <span
                                                                class="p-avatar-text">{{ substr($course->course->code, 0, 1) }}</span>
                                                        </div>
                                                        <h1 title="course-title"
                                                            class="text-[--highlight-text-color] text-lg w-full whitespace-nowrap text-ellipsis overflow-hidden">
                                                            {{ $course->course->name }}</h1>
                                                    </div>
                                                </div>
                                                <div class="card-subtitle">
                                                    <div class="flex items-center flex-wrap">
                                                        <p>{{ $course->course->code }}</p>
                                                        <div class="vertical-divider" role="separator"
                                                            aria-orientation="vertical" style="align-items: center;">
                                                        </div>
                                                        <p>{{ $course->course->units }}
                                                            {{ str_plural('Unit', $course->course->units) }}</p>
                                                        <div class="vertical-divider" role="separator"
                                                            aria-orientation="vertical" style="align-items: center;">
                                                        </div>
                                                        <p>{{ $course->course->option }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-content">
                                                <div class="flex flex-wrap items-center gap-3"><button
                                                        class="btn btn-primary" type="button"
                                                        aria-label="Add result"><span class="p-button-label">Add
                                                            result</span></button>
                                                            <button class="btn btn-secondary" ng-click="viewCourse({{$course->id}})"><i
                                                            class="fa fa-eye"></i> View
                                                            details</button></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>


</x-template>
