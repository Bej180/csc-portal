@php

    $course = request()->get('course');
    $class_id = request()->get('class');

@endphp

<x-template title="Admin - Results" nav="results">


    <div class="p-6 pb-0" ng-controller="ResultController">
        <style>
            @media screen and (max-width: 367.5px) {
                #search-label {
                    top: .3rem
                }
            }
        </style>


        <div class="w-full mt-4">
            <form action="/" method="get" class="flex items-center gap-x-2 w-full relative mb-4">

                <label for="student-search" class="text-body-200 absolute top-3 left-1" id="search-label">
                    <span class="material-symbols-rounded">search</span>
                </label>
                <div>
                    <input type="search" name="studentSearch" id="student-search"
                        placeholder="Enter Name or Reg Number" class="input">
                </div>

                <button type="submit" class="btn btn-sm btn-primary">
                    Submit
                </button>
            </form>

            <form action="?" method="get" class="flex items-end flex-wrap gap-x-2" id="result-options-form">

                <div class="flex-1">
                    <label for="class">Class</label>

                    <select ng-change="setClass(event)" ng-model="class_id" name="class" id="class ignore"
                        class="w-full input">
                        <option value="" class>Select Class</option>
                        @if (auth()->user()->role == 'admin')
                            @foreach (\App\Models\Admin::academicSets() as $class)
                                <option value='{{ $class->id }}'
                                    :selected="'{{ $class->id }}' == '{{ $class_id }}'">{{ $class->name }}
                                </option>
                            @endforeach
                        @else
                            @foreach ($classes as $class)
                                <option value='{{ $class->id }}'
                                    :selected="'{{ $class->id }}' == '{{ $class_id }}'">{{ $class->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="flex-1">
                    <label for="session">Session</label>
                    <dropdown options="config.sessions"></dropdown>
                    <select class="input ignore" ng-disabled="config.sessions.length<1" ng-model="session" name="session" id="session"
                        class="w-full input">
                        <option value="" class>Select session</option>
                        <option ng-repeat="sess in config.sessions" value="{% sess.name %}">{% sess.name %}</option>
                    </select>
                </div>

                <div class="flex-1">
                    <label for="semester">Semester</label>
                    <select ng-disabled="!session" ng-model="semester" name="semester" id="semester" class="input ignore"
                        ng-change="selectSemesterAndSuggestCourses()">
                        <option value="">Select semester</option>
                        <option value="HARMATTAN">Harmattan</option>
                        <option value="RAIN">Rain</option>
                    </select>
                </div>

                <div class="">
                    <label for="course">Course</label>
                    <select ng-disabled="!semester" ng-model="course" name="course" id="course" class="input ignore">
                        <option value="">Select course</option>
                        <option value="all">All courses</option>
                        <option ng-repeat="course in courses track by course.course.id" value="{% course.course.id %}">
                            {% course.course.code %}</option>

                    </select>
                </div>

                <button ng-disabled="!course" type="submit" class="btn btn-sm btn-primary !m-0">
                    View Result
                </button>
            </form>
        </div>


        @if ($course)
            @if ($course === 'all')
                @include('pages.admin.result-management.all-results', ['class' => $class])
            @else
                @include('pages.admin.result-management.course-results')
            @endif
        @endif
        <button id="button">Export to XLSX</button>
        <button export="#resultsTable" class="btn btn-primary">Export</button>

        <script src="{{ asset('js/spreadsheet.js') }}"></script>
        

        <script>
            document.getElementById('button').addEventListener('click', function() {
                var table = document.getElementById('resultsTable');
                var ws = XLSX.utils.table_to_sheet(table);
              
                var wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');
                XLSX.writeFile(wb, 'exported_data.xlsx');
            });
        </script>

    </div>


</x-template>
