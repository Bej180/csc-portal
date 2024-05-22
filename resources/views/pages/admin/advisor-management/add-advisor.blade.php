<x-popend name="addClassAdvisor" title="Add Class Advisor">


    <form>

        <ul class="nav nav-tabs nav-tabs-bottom">
            <li class="nav-item"><a class="nav-link active" href="#basic-info" data-bs-toggle="tab">Basic Info</a></li>
            <li class="nav-item"><a class="nav-link" href="#assign-class" data-bs-toggle="tab">Assign Class</a></li>
        </ul>


        <div class="tab-content mt-5">
            <div class="tab-pane show active" id="basic-info">


                <div>
                    <div class="grid grid-cols-1 gap-4">
                        <input type="text" class="input" ng-model="data.firstname" placeholder="First Name" />

                        <input type="text" class="input" ng-model="data.lastname" placeholder="Last Name" />

                        <input type="text" class="input" ng-model="data.middlename" placeholder="Middle Name" />

                        <input type="text" class="input" ng-model="data.phone" placeholder="Phone Number" />
                        <input type="text" class="input" ng-model="data.email" placeholder="Email Address" />

                        <select ng-model="data.gender" class="input">
                            <option value="">Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>

                    </div>


                    <div class="flex gap-4 mt-4 ">
                        <div class="flex-1">
                            <input type="text" class="input w-full grid-rows-2" ng-model="data.address"
                                placeholder="Contact Address" />
                        </div>
                        <div class="flex gap-3 relative">



                            <x-tooltip label="Date of Birth" direction='top'>
                                <input type="date" class="input grid-span-1" ng-model="data.birthdate" />
                            </x-tooltip>

                            <select ng-if="data.set_id !== 'custom'" ng-model="data.set_id"
                                class="input data-load-classes" ng-model="set_id" id="set_id"
                                ng-change="addCustomClass()">

                                <option value="">--Select Class--</option>
                                <option id="addClass" value="custom">Create Class</option>

                            </select>
                            <div ng-if="data.set_id=='custom'" class="w-32">
                                <x-input ng-blur="insertClass()" type="text" placeholder="Class" ng-ref="admission"
                                    id="admission" ng-model="data.admission" class="session-input" data-session='5' />
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="assign-class">
                
                @include('pages.admin.class-management.create-class')


                <div class="flex mt-5 justify-end gap-3">

                    <button type="button" class="btn btn-secondary" ng-click="closeAdder()">Cancel</button>
                    <submit class="btn btn-primary" ng-click="AddAdvisor()"
                        value="{%data.set_id==='custom'?'Add Advisor & Class':'Add Advisor'%}">Add Advisor</submit>
                </div>
            </div>
        </div>




    </form>

</x-popend>
