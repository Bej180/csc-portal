<div ng-cloak ng-show="editAdvisor" class="popup">


    <form action="/admin/advisor/update" class="popup-wrapper" method="POST" enctype="multipart/form-data">
        @csrf
        <h1 class="popup-header text-center">Edit Advisor</h1>
        <div class="popup-body">
            <x-drag-and-drop />
            <div class="grid grid-cols-3 gap-4">

                <input type="text" class="input" ng-model="firstname" name="firstname" placeholder="First Name" />
                <input type="text" class="input" ng-model="lastname" name="lastname" placeholder="Last Name" />
                <input type="text" class="input" ng-model="middlename" name="middlename"
                    placeholder="Middle Name" />
                <input type="hidden" name="advisor_id" value="{% editAdvisor.id %}" />

                <input type="text" class="input" ng-model="editAdvisor.phone" name="phone"
                    placeholder="Phone Number" />
                <input type="text" class="input" ng-model="editAdvisor.user.email" name="email"
                    placeholder="Email Address" />
                <select name="gender" class="input">
                    <option value="">Gender</option>
                    <option value="male" ng-selected="editAdvisor.gender == 'male'">Male</option>
                    <option value="female" ng-selected="editAdvisor.gender == 'female'">Female</option>
                </select>
            </div>
            <div class="flex gap-4 mt-4 ">
                <div class="flex-1">
                    <input type="text" class="input w-full grid-rows-2" name="address"
                        placeholder="Contact Address" />
                </div>
                <div class="flex gap-3">
                    <x-tooltip label="Date of Birth">
                        <input type="date" class="input grid-span-1" name="birthdate" />
                    </x-tooltip>

                    <select name="set_id" class="input" ng-click="loadClasses()">
                        <option value="">Reassign Class</option>
                        <option ng-repeat="set in classes track by set.id" value="{% set.id %}">{% set.name %}</option>
                    </select>
                </div>
            </div>


        </div>

        <div class="flex gap-2 mt-5 justify-end popup-footer">
            <button type="button" class="btn-white" ng-click="closeEditor()">Cancel</button>
            <button type="submit" class="btn-primary">Save Changes</button>
        </div>


    </form>

</div>
