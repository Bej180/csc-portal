<x-popend name="showCourseForm">

    <form>

        <div class="nav nav-tabs nav-tabs-bottom">
            <a class="nav-link show active" id="v-tabs-basicdetails-tab" data-bs-toggle="tab" href="#v-tabs-basicdetails"
                role="tab" aria-controls="v-tabs-basicdetails" aria-selected="true">
                Basic Details</a>
            <a class="nav-link" id="v-tabs-allocation-tab" data-bs-toggle="tab" href="#v-tabs-allocation" role="tab"
                aria-controls="v-tabs-allocation" aria-selected="false">
                Allocations</a>

        </div>
        <div class="tab-content mt-4">
            <div class="tab-pane fade active show" id="v-tabs-basicdetails" role="tabpanel"
                aria-labelledby="v-tabs-basicdetails-tab">
                <fieldset class="p-4">
                    <legend class="font-semibold">Basic Details</legend>

                    <div class="flex flex-col gap-4">
                        <div class="flex-1">
                            <input class="input" ng-model="data.name" placeholder="Course Title" name="name"
                                id="name" />

                        </div>
                        <div class="flex-1">
                            <input class="input" ng-model="data.code" placeholder="Course code" name="code"
                                ng-change="suggestLevelAndSemester()" id="code" />
                        </div>


                        <div class="flex-1">
                            <select class="input" ng-model="data.prerequisite" name="prerequisite" id="prerequisite"
                                ng-disabled="!data.code">
                                <option value="">Prerequisite</option>
                            </select>
                        </div>
                        <div class=" flex-1">
                            <select class="input" name="mandatory" id="mandatory" ng-model="data.mandatory"
                                ng-disabled="!data.code">
                                <option value="">Course Option</option>
                                <option value="1">COMPULSORY</option>
                                <option value="0">ELECTIVE</option>
                            </select>
                        </div>

                        <div class="flex-1 flex">
                            <textarea placeholder="Type course outline here" ng-model="data.outline" name="outline" rows="4" class="input"
                                id="outline"></textarea>
                        </div>

                    </div>


                </fieldset>
            </div>
            <div class="tab-pane fade" id="v-tabs-allocation" role="tabpanel" aria-labelledby="v-tabs-allocation-tab">
                <fieldset class="p-4">
                    <legend class="font-semibold">Unit Allocation</legend>




                    <div class="flex flex-col gap-4">

                        <div class="flex flex-col">
                            <select class="input" ng-model="data.test" name="test" placeholder="Test Score"
                                manual="true" id="test">
                                <option value="">--Test Units--</option>
                                <option value="0">no unit</option>
                                <option value="1">1 unit</option>
                                <option value="2">2 unit</option>
                                <option value="3">3 units</option>
                                <option value="4">4 units</option>
                                <option value="5">5 units</option>
                            </select>

                        </div>
                        <div class="flex flex-col">
                            <select class="input" ng-model="data.lab" name="lab" id="lab">
                                <option value="">--Lab Units--</option>
                                <option value="0">no unit</option>
                                <option value="1">1 unit</option>
                                <option value="2">2 units</option>
                                <option value="3">3 units</option>
                                <option value="4">4 units</option>
                                <option value="5">5 units</option>
                            </select>

                        </div>
                        <div class="flex flex-col">
                            <select class="input" ng-model="data.exam" type="number" name="exam" id="exam">
                                <option value="">--Exam units--</option>
                                <option value="1">1 unit</option>
                                <option value="2">2 units</option>
                                <option value="3">3 units</option>
                                <option value="4">4 units</option>
                                <option value="5">5 units</option>
                            </select>
                        </div>

                    </div>

                </fieldset>

                <fieldset class="p-4">
                    <legend class="font-semibold">Assigned to</legend>




                    <div class="lg:flex gap-1 justify-between items-center">

                        <div class="flex-1">
                            <select class="input" ng-model="data.level" name="level" placeholder="Level"
                                id="level">
                                <option value="">--Level--</option>
                                <option value="100">100 level</option>
                                <option value="200">200 level</option>
                                <option value="300">300 level</option>
                                <option value="400">400 level</option>
                                <option value="500">500 level</option>
                            </select>

                        </div>

                        <div class="flex-1">
                            <select class="input" ng-model="data.semester" name="semester" placeholder="Semester"
                                id="semester">
                                <option value="">--Semester--</option>
                                <option value="HARMATTAN" ng-selected="data.semester === 'HARMATTAN'">
                                    Harmattan</option>
                                <option value="RAIN" ng-selected="data.semester === 'RAIN'">Rain</option>
                            </select>
                        </div>



                    </div>
                </fieldset>


                <div class="flex items-center gap-2">
                    <input type="checkbox" id="check" ng-model="check" required class="checkbox" class="peer"
                        name="check" id="check" /> <label class="peer-checked:font-bold" for="check">Have
                        you verified the above details are correct?</label>
                </div>
                <div class="flex gap-3 justify-end items-center">
                    <a href="/admin/courses" class="btn-white">
                        Cancel
                    </a>
                    <button class="btn-primary" type="button" ng-disabled="!check" ng-click="addCourse()">
                        Add Course
                    </button>

                </div>
            </div>




        </div>
    </form>
</x-popend>
