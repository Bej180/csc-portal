@php

    $old_mandatory = old('mandatory', $course->mandatory);
    $old_test = old('test', $course->test);
    $old_exam = old('exam', $course->exam);
    $old_practical = old('practical', $course->practical);
    $old_code = old('code', $course->code);
    $old_name = old('name', $course->name);
    $old_prerequisites = old('prerequisites', $course->prerequisites);
    $old_semester = old('semester', $course->semes);
    $old_level = old('level', $course->level);
    $old_outline = old('outline', $course->outline);

@endphp
<x-template title="Edit Course" nav="courses">
    <div class="flex flex-col gap-5 p-16" ng-init="proceed=false">
        <div class="flex-col box !bg-red-50 p-10 flex gap-4">
            <div class="box-body">
                <div class="opacity-50 mb-5 flex item-center gap-2"><span class="material-symbols-rounded">info</span>
                    <span>UPDATING COURSE INFO</span></div>
                <p>Updating this course's units (exam, test & lab) will automatically warant cloning the course. This is
                    because the result has already been
                    registered by students. So it has to be cloned to so that it doesn't affect the result of previously
                    uploaded
                    results.</p>

            </div>
        </div>
        <form class="box flex-1" action="{{ route('update.course') }}" method="post">
            <div class="box-body">
                <input type="hidden" value="{{ $course->id }}" name="id" />
                @csrf

                <div class=" font-semibold text-center popup-header">Edit Course</div>
                <div class="popup-body lg:flex flex-col gap-10 overflox-y-auto">
                    <div class="flex-1">
                        <fieldset>
                            <legend class="font-semibold">Basic Details</legend>

                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-2">
                                    <fieldset class="flex flex-col relative input">
                                        <legend>Course Title</legend>
                                        <input type="text" name="name" placeholder="Course Title"
                                            value="{{ $old_name }}" />
                                    </fieldset>
                                    @error('name')
                                        <x-error message="{{ $message }}" />
                                    @enderror

                                </div>
                                <div class="grid-span-1">
                                    <fieldset class="flex flex-col relative input">
                                        <legend>Course Code</legend>
                                        <input type="text" name="code" placeholder="Course Code"
                                            value="{{ $old_code }}" />
                                    </fieldset>
                                    @error('code')
                                        <x-error message="{{ $message }}" />
                                    @enderror
                                </div>
                            </div>

                            <div class="lg:flex gap-4 mt-4 items-center justify-between">

                                <div class=" flex-1">
                                    <select class="input w-full" name="prerequisite" id="prerequisite">
                                        <option value="0" {{ $old_prerequisites == '0' ? 'selected' : '' }}>
                                            Prerequisite
                                        </option>
                                    </select>
                                    @error('prerequisite')
                                        <x-error message="{{ $message }}" />
                                    @enderror
                                </div>
                                <div class=" flex-1">
                                    <select class="input w-full" name="mandatory" id="mandatory"
                                        value="{{ $old_mandatory }}">
                                        <option value="" {{ $old_mandatory == '' ? 'selected' : '' }}>Course
                                            Option
                                        </option>
                                        <option value="1" {{ $old_mandatory == '1' ? 'selected' : '' }}>COMPULSORY
                                        </option>
                                        <option value="0" {{ $old_mandatory == '0' ? 'selected' : '' }}>ELECTIVE
                                        </option>
                                    </select>
                                    @error('mandatory')
                                        <x-error message="{{ $message }}" />
                                    @enderror
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="mt-4">
                            <legend class="font-semibold">Unit Allocation</legend>




                            <div class="lg:flex gap-1 justify-between times-center">

                                <div class="flex-1">
                                    <x-tooltip label="Test Units">
                                        <select type="number" class="input" name="test" placeholder="Test Score"
                                            manual="true" id="test" value="{{ $old_test }}">
                                            <option value="">Test Units</option>
                                            <option value="0" {{ $old_test == '0' ? 'selected' : '' }}>0 unit
                                            </option>
                                            <option value="1" {{ $old_test == '1' ? 'selected' : '' }}>1 unit
                                            </option>
                                            <option value="2" {{ $old_test == '2' ? 'selected' : '' }}>2 units
                                            </option>
                                            <option value="3" {{ $old_test == '3' ? 'selected' : '' }}>3 units
                                            </option>
                                            <option value="4" {{ $old_test == '4' ? 'selected' : '' }}>4 units
                                            </option>
                                            <option value="5" {{ $old_test == '5' ? 'selected' : '' }}>5 units
                                            </option>
                                        </select>
                                    </x-tooltip>
                                    @error('test')
                                        <x-error message="{{ $message }}" />
                                    @enderror

                                </div>
                                <div class="flex-1">
                                    <x-tooltip label="Lab Units">
                                        <select type="number" class="input" value="{{ $old_practical }}"
                                            name="practical" placeholder="Practical Unit" manual="true" id="practical">
                                            <option value="">Lab Units</option>
                                            <option value="0" {{ $old_practical == '0' ? 'selected' : '' }}>0 unit
                                            </option>
                                            <option value="1" {{ $old_practical == '1' ? 'selected' : '' }}>1 unit
                                            </option>
                                            <option value="2" {{ $old_practical == '2' ? 'selected' : '' }}>2
                                                units
                                            </option>
                                            <option value="3" {{ $old_practical == '3' ? 'selected' : '' }}>3
                                                units
                                            </option>
                                            <option value="4" {{ $old_practical == '4' ? 'selected' : '' }}>4
                                                units
                                            </option>
                                            <option value="5" {{ $old_practical == '5' ? 'selected' : '' }}>5
                                                units
                                            </option>
                                        </select>
                                    </x-tooltip>
                                    @error('practical')
                                        <x-error message="{{ $message }}" />
                                    @enderror
                                </div>
                                <div class="flex-1">
                                    <x-tooltip label="Exam Units">
                                        <select type="number" name="exam" class="input placeholder="Exam Unit"
                                            manual="true" id="exam" onSelect="exam=$el.value">
                                            <option value="">Exam Units</option>
                                            <option value="1" {{ $old_exam == '1' ? 'selected' : '' }}>1 unit
                                            </option>
                                            <option value="2" {{ $old_exam == '2' ? 'selected' : '' }}>2 units
                                            </option>
                                            <option value="3" {{ $old_exam == '3' ? 'selected' : '' }}>3 units
                                            </option>
                                            <option value="4" {{ $old_exam == '4' ? 'selected' : '' }}>4 units
                                            </option>
                                            <option value="5" {{ $old_exam == '5' ? 'selected' : '' }}>5 units
                                            </option>
                                        </select>
                                    </x-tooltip>
                                    @error('exam')
                                        <x-error message="{{ $message }}" />
                                    @enderror
                                </div>

                            </div>
                        </fieldset>

                        <div class="grid place-items-center mt-5 h-[200px] body-400 rounded-md shadow">
                            <x-tooltip label="Choose or Drag Image here">
                                <input type="file" id="fileInput" accepts="image/*" style="display: none;">
                                <div id="dropZone"
                                    class="drop-zone flex flex-col items-center rounded-md  justify-center">
                                    <img src="{{ asset('svg/course_image_default.svg') }}"
                                        x-bind:src="editData.image '" class="w-full h-full object-cover" />
                                    <p class="text-sm opacity-55">Drag & Drop image here</p>
                                </div>
                            </x-tooltip>
                        </div>
                    </div>

                    <div class="flex-1">
                        <fieldset class="mt-4">
                            <legend>Course Outline</legend>
                            <textarea placeholder="Type course outline here" name="outline" rows="10"
                                class="border-none w-full focus:outline-none input" id="outline" value="{{ $old_outline }}">{{ $old_outline }}</textarea>
                            @error('outline')
                                <x-error message="{{ $message }}" />
                            @enderror
                        </fieldset>


                        <fieldset>
                            <legend class="font-semibold">Assigned to</legend>




                            <div class="lg:flex gap-1 justify-between times-center">

                                <div class="flex-1">
                                    <x-tooltip label="Level">
                                        <select class="input" name="level" value="{{ $old_level }}"
                                            id="level">
                                            <option value="">Level</option>
                                            <option value="100" {{ $old_level == '100' ? 'selected' : '' }}>100
                                                level
                                            </option>
                                            <option value="200" {{ $old_level == '200' ? 'selected' : '' }}>200
                                                level
                                            </option>
                                            <option value="300" {{ $old_level == '300' ? 'selected' : '' }}>300
                                                level
                                            </option>
                                            <option value="400" {{ $old_level == '400' ? 'selected' : '' }}>400
                                                level
                                            </option>
                                            <option value="500" {{ $old_level == '500' ? 'selected' : '' }}>500
                                                level
                                            </option>
                                        </select>
                                    </x-tooltip>
                                    @error('level')
                                        <x-error message="{{ $message }}" />
                                    @enderror

                                </div>

                                <div class="flex-1">
                                    <select name="semester" class="input" placeholder="Semester"
                                        value="{{ $old_semester }}" id="semester">
                                        <option value="HARMATTAN"
                                            {{ $old_semester == 'HARMATTAN' ? 'selected' : '' }}>
                                            Harmattan</option>
                                        <option value="RAIN" {{ $old_semester == 'RAIN' ? 'selected' : '' }}>Rain
                                        </option>
                                    </select>
                                    @error('semester')
                                        <x-error message="{{ $message }}" />
                                    @enderror
                                </div>



                            </div>
                        </fieldset>
                    </div>

                </div>
                <div class="flex items-center gap-2">
                    <x-input type="checkbox" ng-model="checkbox" required class="checkbox" class="peer"
                        value="on" name="check" id="check">Have you verified that the above details are
                        correct?</x-input>
                </div>
                <div class="flex gap-3 justify-end items-center popup-footer">
                    <a href="/admin/courses?course_id={{ $course->id }}&semester={{ $course->semester }}&level={{ $course->level }}"
                        class="btn-white" type="button" ng-click="editData=false">
                        Cancel
                    </a>
                    <submit class="btn btn-primary" value="Update">
                        Update
                    </submit>

                </div>
            </div>
        </form>
    </div>
</x-template>
