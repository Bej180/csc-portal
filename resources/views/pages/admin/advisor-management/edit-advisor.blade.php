@php 
  $name = $advisor->user->name;
  //const nameParts = response.user.name.split(" ");
  $fullname = $advisor->user->fullname;
  $firstname = old('firstname', $fullname->firstname);
  $lastname = old('lastname', $fullname->lastname);
  $middlename = old('middlename', $fullname->middlename);
  $birthdate = old('birthdate', $advisor->birthdate);
  $set_id = old('set_id', $advisor->set_id);
  $phone = old('phone', $advisor->phone);
  $email = old('email', $advisor->email);
  $gender = old('gender', $advisor->gender);

  
@endphp
<x-template title="Update Advisor Account" nav="advisors">
    <div class="popup">


        <form action="/admin/advisor/update" class="popup-wrapper" method="POST" enctype="multipart/form-data">
            @csrf
            <h1 class="popup-header text-center">Edit Advisor</h1>
            <div class="popup-body">
                <x-drag-and-drop />
                <div class="grid grid-cols-3 gap-4">

                    <input type="text" class="input" value="{{ $firstname }}"  name="firstname"
                        placeholder="First Name" />
                    <input type="text" class="input" value="{{ $lastname }}" name="lastname" placeholder="Last Name" />
                    <input type="text" class="input" value="{{ $middlename }}" name="middlename"
                        placeholder="Middle Name" />
                    <input type="hidden" name="advisor_id" value="{% editAdvisor.id %}" />

                    <input type="text" class="input" value="{{$advisor->phone}}" name="phone"
                        placeholder="Phone Number" />
                    <input type="text" class="input" value="{{$advisor->user->email}}" name="email"
                        placeholder="Email Address" />
                    <select name="gender" class="input">
                        <option value="">Gender</option>
                        <option value="male" selected="{{$advisor->gender === 'male' && 'selected'}}">Male</option>
                        <option value="female"  selected="{{$advisor->gender === 'female' && 'selected'}}">Female</option>
                    </select>
                </div>
                <div class="flex gap-4 mt-4 ">
                    <div class="flex-1">
                        <input type="text" class="input w-full grid-rows-2" name="address"
                            placeholder="Contact Address" />
                    </div>
                    <div class="flex gap-3">
                        <x-tooltip label="Date of Birth">
                            <input type="date" class="input grid-span-1" name="birthdate" value="{{$birthdate}}"/>
                        </x-tooltip>

                        <select name="set_id" class="input" ng-click="loadClasses()">
                            <option value="">Reassign Class</option>
                            <option ng-repeat="set in classes track by set.id" value="{% set.id %}">{% set.name %}
                            </option>
                        </select>
                    </div>
                </div>


            </div>

            <div class="flex gap-2 mt-5 justify-end popup-footer">
                <a href="/admin/advisors?advisor_id={{$advisor->id}}" class="btn-white">Cancel</a>
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>


        </form>

    </div>
</x-template>
