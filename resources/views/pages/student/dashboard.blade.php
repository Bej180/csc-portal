@php

    $user = \App\Models\User::active();
    $student = $user->student;
    $records = $student->courses;
    $set = $student->academicSet;
    $enrolledCourses = $student->courses;
    $results = $student->results->count();
    $cgpa = $student->calculateCGPA();
    $materials = $student->getMaterials();

@endphp
<x-template nav="home" title="Student Dashboard">
    <script src="{{ asset('js/apexchart.js') }}"></script>
    @if ($records && $records->count() === 0)
        <div id="no-courses" class="h-avail justify-center flex p-2 relative flex-col gap-5 items-center">
            <img class="w-72" src="{{ asset('svg/no_courses.svg') }}" alt="no_courses_icon" />
            <div class="flex flex-col items-center gap-5 text-center">
                <p class="text-white-800 text-zinc-500">
                    Oops! It looks like you haven't registered for any courses yet. <br>
                    Register your courses before the deadline to ensure you can view them when they become available.
                </p>

                <a href="{{ route('course.enroll') }}">
                    <button type="button" class="btn btn-primary transition">
                        Register Courses
                    </button>
                </a>
            </div>
        </div>
    @else
        <div class="columns">
            <section class="half-60">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Overview 
                        </div>
                    </div>
                    <div class="card-body flex flex-col gap-5 py-6">


                        <div class="flex flex-col gap-5 h-full justify-between items-stretch">
                            <div class="flex-1 flex flex-col gap-6">
                                <div class="flex flex-col lg:flex-row gap-4">
                                    <div
                                        class="dashboard-cards !grid-cols-1 flex flex-col justify-between flex-1 gap-4">




                                        <div
                                            class="bg-[--blue-200] p-4 flex flex-col justify-between overflow-hidden rounded-md min-h-32">
                                            <header class="w-full flex items-center justify-between">
                                                <p class="font-medium uppercase">Enrolled in</p><span
                                                    class="material-symbols-rounded">groups</span>
                                            </header>
                                            <div>
                                                <h1 class="font-medium text-4xl">{{ $enrolledCourses->count() }}</h1>
                                                <h1 class="font-medium text-4xl"></h1>
                                            </div>
                                        </div>



                                        <div
                                            class="bg-[--red-200] p-4 flex flex-col justify-between overflow-hidden rounded-md min-h-32">
                                            <header class="w-full flex items-center justify-between">
                                                <p class="font-medium uppercase">Results</p><span
                                                    class="material-symbols-rounded">work</span>
                                            </header>
                                            <div>
                                                <h1 class="font-medium text-4xl">{{ $results }}</h1>
                                                <h1 class="font-medium text-4xl"></h1>
                                            </div>
                                        </div>





                                    </div>
                                    <div>
                                        <div
                                            class="card2 !bg-[--primary-200] lg:w-[300px] xtext-white !rounded-lg h-full p-8">
                                            <div class=" text-4xl font-extrabold">GPA</div>
                                            <div class="font-semibold">Grading Point Average</div>
                                            <div class="text-7xl font-extrabold mt-5">
                                                {{ $cgpa }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @include('charts.student-statistics')



                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="half-40">

                <div class="card">
                    <div clas="card-body">

                    <div class="lg:py-5 flex flex-col gap-3">

                        <card title="Calendar">
                            <div class="card2-body no-max-h" style="height:initial;">
                                <x-calendar />
                            </div>
                        </card>



                        <card title="Materials">
                            <div class="card2-body">
                                @if (!count($materials))
                                    <span class="opacity-50">
                                        No Materials yet
                                    </span>
                                @else
                                    @foreach ($materials as $material)
                                        <div
                                            class="flex justify-between gap-2 !text-xs w-full border-b border-zinc-300 dark:border-zinc-800 last:border-none py-2.5 last:pb-0">
                                            <div class="w-[calc(100%-3rem)] flex gap-2">
                                                <img src="{{ asset('svg/icons/' . $material->extension . '.png') }}"
                                                    class="w-5 h-5" />
                                                <div class="flex-1">
                                                    <p
                                                        class="font-semibold whitespace-nowrap text-ellipsis overflow-hidden w-[130px]">
                                                        {{ $material->name }}</p>
                                                    <span class="font-extralight text-xs">.{{ $material->extension }},
                                                        {{ formatFileSize($material->size) }}</span>
                                                    <p class="italic text-xs opacity-60">Shared
                                                        {{ timeago($material->created_at) }}</p>
                                                </div>
                                            </div>
                                            <div class="shrink-0 w-2.6rem">


                                                <x-tooltip label="Save">
                                                    <a target="blank" rel="download"
                                                        href="{{ asset('storage/' . $material->url) }}"
                                                        class="!text-sm !w-3.5 !h-3.5"><i class="fa fa-download"></i>
                                                    </a>
                                                </x-tooltip>
                                            </div>
                                        </div>
                                    @endforeach
                                @endempty
                        </div>
                        <div class="card2-footer">
                            {{ $materials->links() }}
                        </div>
                    </card>

                    <x-todo />
                    </div>

                </div>

            </section>
        </div>
    </div>
@endif
<style>
    #main-slot {
        padding: 0px;
        margin: 0px;
    }
</style>

</x-template>
