@php 

$classes = \App\Models\Admin::academicSets();

@endphp
<div ng-cloak ng-show="edit" class="popup popup-inverse">
  
  
  <form action="{{ route('update.staff') }}" class="popup-wrapper" method="POST" enctype="multipart/form-data">
    @csrf
    <h1 class="popup-header font-bold text-center">Edit Student</h1>
    <div class="popup-body">
        <x-drag-and-drop/>
        <div class="grid grid-cols-3 gap-4">
          <input type="text" class="input" name="firstname" ng-model="firstname" placeholder="First Name"/>
          <input type="text" class="input" name="lastname" ng-model="lastname" placeholder="Last Name"/>
          <input type="text" class="input" name="middlename" ng-model="middlename" placeholder="Middle Name"/>
          <input type="hidden" name="staff_id" value="{% staff_id %}"/>

          
          <input type="text" class="input" name="email" ng-model="staff.user.email" placeholder="Email Address"/>
          <input type="text" class="input" name="phone" ng-model="staff.user.phone" placeholder="Phone Number"/>
          <select name="gender" class="input" ng-model="staff.gender">
            <option value="">Gender</option>
            <option value="female">Female</option>
            <option value="male">Male</option>
          </select>
        </div>
        <div class="flex gap-4 mt-4 ">
          <div class="flex-1">
            <input type="text" class="input w-full grid-rows-2" name="address" placeholder="Contact Address" ng-model="staff.address"/>
          </div>
          <div class="flex gap-3">
            <x-tooltip label="Date of Birth">
              <input type="date" class="input grid-span-1" ng-modelx="staff.birthdate" name="birthdate"/> 
            </x-tooltip>

            
          </div>
        </div>

    </div>

    <div class="flex gap-2 mt-5 justify-end popup-footer">
      
      <button type="reset" class="btn-white">Reset</button>
      <button type="button" class="btn-white popup-dismiss" ng-click="closeEditor()">Cancel</button>
      <button type="submit" class="btn btn-primary">Save Changes</button>
    </div>


  </form>

</div>