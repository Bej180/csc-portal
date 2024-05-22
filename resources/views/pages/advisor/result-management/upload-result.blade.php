@php
    $year = date('Y');
@endphp
<x-template title="Advisor - Results" nav="results">

    <div class="flex justify-center gap-5 flex-col h-full" x-data="{ semester: null, level: null, result: null, courses: [], session: null }">
        <div class="horizontal-nav flex items-center text-sm font-semibold gap-4 text-body-200">
            <a href="/advisor/results">View Results</a>
            <a href="/advisor/upload-result" class="active">Upload Results</a>
            <a href="/advisor/cgpa-summary-result">CGPA Summary Result</a>
        </div>
        <div class="flex-1">
            <div class="flex items-center justify-center -transition-y-[50%] mt-8">
                <div class="w-[400px] h-[400px] shadow-md bg-white">
                    <form action="{{ route('import.results') }}" class="flex flex-col" method="post"
                        enctype="multipart/form-data">
                        <input type="file" class="input" name="result"
                            x-on:change="result=$el.files.length>0 ? $el.files[0] : null" accept=".xlsx, xls" />

                        <div class="grid grid-cols-3 gap-3 mt-8">
                            <div>
                                <select class="input w-full px-3 py-2" x-on:change="fetchCourse" name="level"
                                    :disabled="!result" disabled>
                                    <option value="">Select Level</option>
                                    <option value="100">100 Level</option>
                                    <option value="200">200 Level</option>
                                    <option value="300">300 Level</option>
                                    <option value="400">400 Level</option>
                                    <option value="500">500 Level</option>
                                </select>
                            </div>
                            <div>
                                <select class="input w-full px-3 py-2" x-on:change="fetchCourse" name="semester"
                                    placeholder="Semeter" :disabled="!level || !result" disabled>
                                    <option value="">Select Semester</option>
                                    <option value="HARMATTAN">Harmattan</option>
                                    <option value="RAIN">Rain</option>
                                </select>
                            </div>

                            <div>
                                <select class="input w-full px-3 py-2" name="course" placeholder="Course"
                                    :disabled="!semester || !level || !result" disabled>
                                    <option value="">Select Course</option>
                                    <template x-for="course in courses" :key="course.id">
                                        <option :value="course.id" x-text="course.code"></option>
                                    </template>
                                </select>
                            </div>

                        </div>

                        <div class="grid grid-cols-3 gap-3 mt-8">
                            <div>
                                <select class="input w-full px-3 py-2" name="session"
                                    :disabled="!semester || !level | !result || !course" disabled>
                                    @foreach (\App\Models\Course::generateSessions() as $session)
                                        <option value="{{ $session }}"
                                            :selected="'{{ $session }}'.indexOf({{ $year - 1 }}) == 0">
                                            {{ $session }}</option>
                                    @endforeach
                                </select>
                            </div>



                        </div>



                        @csrf
                        <button type="submit" class="btn-primary" name="Import">Import Results</button>
                    </form>

                </div>
            </div>
        </div>
    </div>


    <script></script>
</x-template>
