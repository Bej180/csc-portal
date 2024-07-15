<x-popend name="edit_course">
    <div class="flex flex-col gap-5" ng-if="edit_course">
      
        <form class="flex-1">
          
            <input type="hidden" ng-model="edit_course.id" />

            <div class="font-semibold text-center popup-header">Edit Course</div>
            <div class="popup-body lg:flex flex-col gap-10 overflox-y-auto">
                <div class="flex-1">
                    <fieldset>
                        <legend class="font-semibold">Basic Details</legend>

                        <div class="grid grid-cols-3 gap-4">
                            <div class="col-span-2">
                                <input type="text" class="input" ng-model="edit_course.name" />
                                <info type="error" message="edit_course_error.name"/>
                                

                            </div>
                            <div class="grid-span-1">
                                
                                <input type="text" class="input" ng-model="edit_course.code" placeholder="Course Code"/>
                                
                                <info type="error" message="edit_course_error.code"/>
                               
                            </div>
                        </div>

                        <div class="lg:flex gap-4 mt-4 items-center justify-between">

                            <div class=" flex-1">
                                <label>Prerequisite</label>
                                <select ng-model="edit_course.prerequisite" class="input w-full" name="prerequisite" id="prerequisite">
                                </select>
                                <info type="error" message="edit_course_error.prerequisite"/>
                            </div>
                            <div class=" flex-1">
                                <label>Course Option</label>
                                <select class="input w-full" ng-model="edit_course.option">
                                    <option>COMPULSORY</option>
                                    <option>ELECTIVE</option>
                                </select>
                                <info type="error" message="edit_course_error.option"/>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="mt-4">
                        <legend class="font-semibold">Unit Allocation</legend>




                        <div class="lg:flex gap-1 justify-between times-center">

                            <div class="flex-1">
                                <label>Test Score</label>
                                <select type="number" class="input" manual="true" ng-model="edit_course.test">
                                    <option value="0">No Test</option>
                                    <option value="1">1 UNIT</option>
                                    <option value="2">2 UNITS</option>
                                    <option value="3">3 UNITS</option>
                                    <option value="4">4 UNITS</option>
                                    <option value="5">5 UNITS</option>
                                </select>
                                <info type="error" message="edit_course_error.test"/>

                            </div>
                            <div class="flex-1">
                                <label>Lab Units</label>
                                <select type="number" class="input" manual="true" ng-model="edit_course.lab">
                                    <option value="0">NO LAB</option>
                                    <option value="1">1 UNIT</option>
                                    <option value="2">2 UNITS</option>
                                    <option value="3">3 UNITS</option>
                                    <option value="4">4 UNITS</option>
                                    <option value="5">5 UNITS</option>
                                </select>
                                <info type="error" message="edit_course_error.lab"/>
                            </div>
                            <div class="flex-1">
                                <label>Exam Units</label>
                                <select type="number" class="input"
                                    manual="true" ng-model="edit_course.exam">
                                    <option value="0">NO EXAM</option>
                                    <option value="1">1 UNIT</option>
                                    <option value="2">2 UNITS</option>
                                    <option value="3">3 UNITS</option>
                                    <option value="4">4 UNITS</option>
                                    <option value="5">5 UNITS</option>
                                </select>
                                <info type="error" message="edit_course_error.exam"/>
                            </div>

                        </div>
                    </fieldset>

                </div>

                <div class="flex-1">
                    <fieldset class="mt-4">
                        <legend>Course Outline</legend>
                        <textarea placeholder="Type course outline here" name="outline" rows="10"
                            class="border-none w-full focus:outline-none input" id="outline" mg-model="edit_course.outline"></textarea>
                            <info type="error" message="edit_course_error.outline"/>
                    </fieldset>


                    <fieldset>
                        <legend class="font-semibold">Assigned to</legend>




                        <div class="lg:flex gap-1 justify-between times-center">

                            <div class="flex-1">
                                <x-tooltip label="Level">
                                    <select class="input" name="level" ng-model="edit_course.level">
                                        <option value="">Level</option>
                                        <option value="100">100 level</option>
                                        <option value="200">200 level</option>
                                        <option value="300">300 level</option>
                                        <option value="400">400 level</option>
                                        <option value="500">500 level</option>
                                    </select>
                                </x-tooltip>
                                <info type="error" message="edit_course_error.level"/>

                            </div>

                            <div class="flex-1">
                                <select ng-model="edit_course.semester" class="input" placeholder="Semester">
                                    <option>HARMATTAN</option>
                                    <option>RAIN</option>
                                </select>
                                <info type="error" message="edit_course_error.semester"/>
                            </div>



                        </div>
                    </fieldset>
                </div>

            </div>

            <div class="flex gap-3 justify-end items-center popup-footer">
                
                <button controller="updateCourse(edit_course)" type="button" class="btn btn-primary" value="Update">
                    Save Changes
                </button>

            </div>
        </form>
    </div>
</x-template>
