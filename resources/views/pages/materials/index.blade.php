<x-template nav="materials" title="Materials">

    <div ng-controller="MaterialController" class="lg:flex gap-3">
        <div class="flex-1 shrink-0">
            <div class="scroller">
                <div class="p-4">
                    <div class="card2">

                        <div class="card2-header">
                            Courses
                        </div>
                        @forelse($staff->courses as $course)
                            <div class="card2-body">
                                <div class="flex gap-3 items-center">
                                    <img src="{{ asset('svg/course_image_default.svg') }}" alt="course-image"
                                        class="rounded-lg">
                                    <div class="flex-1">
                                        <h4>{{ $course->name }}</h4>
                                        <p class="flex gap-2 text-slate-400"> <span
                                                class=" border-r-2 border-zinc-200 pr-2">{{ $course->units }}
                                                units</span><span>{{ $course->level }} level </p>
                                        <div class="mt-3 flex flex-col lg:flex-row gap-3 flex-wrap">
                                            <button type="button" class="btn btn-primary">Upload Results</button>
                                            <button type="button" class="btn btn-primary"
                                                ng-click="popend('upload_materials')">Share Materials</button>
                                        </div>
                                    </div>
                                </div>
                            </div>




                        @empty
                            <img class="w-72" src="{{ asset('svg/no_courses.svg') }}" alt="no_courses_icon">
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:w-[400px]">
            <div class="scroller">


                @if ($materials)
                    <div class="mt-4 flex flex-col gap-4">
                        @foreach ($materials as $material)
                            <div class="card2 flex flex-col justify-between">
                                <div class="card2-header">
                                    <div
                                        class="flex gap-2 items-center xwhitespace-nowrap text-ellipsis overflow-hidden w-full">
                                        <img src="{{ asset('svg/icons/' . $material['extension'] . '.png') }}"
                                            class="w-5 h-5" /> {{ $material['name'] }}
                                    </div>
                                </div>

                                <div class="card2-body">
                                    <span class="font-extralight text-xs">.{{ $material['extension'] }},
                                        {{ formatFileSize($material['size']) }}</span>
                                    <p class="italic text-xs opacity-60">Shared
                                        {{ timeago($material['created_at']) }}</p>

                                    <p class="mt-2 text-right text-xs">
                                        For: {{ $material['code'] }}
                                    </p>


                                </div>
                                <div class="card2-footer flex justify-between items-center">
                                    <x-tooltip label="Delete">
                                        <a href="#">

                                            <i class="fa fa-trash"></i> Delete
                                        </a>
                                    </x-tooltip>


                                    <x-tooltip label="Save">
                                        <a target="blank" rel="download"
                                            href="{{ asset('storage/' . $material['url']) }}"
                                            class="material-symbols-roundedx !text-sm !w-3.5 !h-3.5"><i
                                                class="fa fa-download"></i> Download</a>
                                    </x-tooltip>

                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                @endif

            </div>

        </div>
        @include('pages.materials.insert')

    </div>

    <style>
        #main-slot {
            padding: 0px !important;
            margin: 0px !important;
        }
    </style>
</x-template>
