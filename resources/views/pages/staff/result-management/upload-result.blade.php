@php
    $year = date('Y');
@endphp
<x-template title="Advisor - Results" nav="results">

    <div class="flex justify-center gap-5 flex-col h-full" ng-controller="ResultController">

        <div class="flex-1">

            <div class="popupx grid place-items-center h-full">
                <form action="{{ route('import.results') }}" class="popup-wrapper flex flex-col" method="post"
                    enctype="multipart/form-data">
                   
                    @csrf
                    <div class="popup-header">
                        Upload OGR results
                    </div>
                    <div class="popup-body">
                        
                        <div class="md:grid grid-cols-2 gap-3 mt-5">
                            <div class="col-span-1">
                                <select class="input w-full px-3 py-2" ng-model="level" name="level">
                                    <option value="">Select Level</option>
                                    <option value="100">100 Level</option>
                                    <option value="200">200 Level</option>
                                    <option value="300">300 Level</option>
                                    <option value="400">400 Level</option>
                                    <option value="500">500 Level</option>
                                </select>
                            </div>
                            <div class="col-span-1">
                                <select class="input w-full px-3 py-2" ng-model="semester" name="semester"
                                    placeholder="Semester" ng-disabled="!level" disabled>
                                    <option value="">Select Semester</option>
                                    <option value="HARMATTAN">Harmattan</option>
                                    <option value="RAIN">Rain</option>
                                </select>
                            </div>

                            <div class="col-span-1">
                                <select class="input w-full px-3 py-2" ng-model="course" name="course" placeholder="Course"
                                    ng-disabled="!semester || !level" ng-click="loadCourses()" disabled>
                                    <option value="">Select Course</option>
                                    
                                    <option ng-repeat="course in courses track by course.id" value="{% course.id %}" ng-bind="course.code"></option>
                                    
                                </select>
                            </div>


                            <div class="col-span-1">
                                
                                <select manual="true" relative=".popup-body" class="input w-full px-3 py-2" name="session"
                                    ng-class="{'disabled':!semester || !level || !course}" placeholder="Session">
                                    @foreach (\App\Models\Course::generateSessions(date('Y')-2) as $session)
                                        <option value="{{ $session }}"
                                            ng-selected="'{{ $session }}'.indexOf({{ $year - 1 }}) == 0">
                                            {{ $session }}</option>
                                    @endforeach
                                </x-select>
                            </div>

                        </div>

                      

                       <br>
                        <x-input class="mt-2" type="file" class="input" name="result" ng-model="result" accept=".xlsx, xls" placeholder="Choose Result in Excel format"/>
                        
                    </div>

                    <div class="popup-footer">

                        <button type="submit" class="btn-primary" name="Import">Import Results</button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    <script></script>
</x-template>
