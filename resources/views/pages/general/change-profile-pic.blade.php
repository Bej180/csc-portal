@php
    $authUser = auth()->user();
    $profile = $authUser->profile;
@endphp
<x-template>
    <div class="popup">
        <form enctype="multipart/form-data" action="/updateprofile" method="POST" id="change-image-form"
            class="popup-wrapper">
            @csrf
            <div class="popup-header">Change Profile Image</div>
            <div class="popup-body flex flex-col items-center" ng-init="image='/profilepic/{{$authUser->id}}'">
             
                  <x-drag-and-drop alt="user_img" class="w-24 h-24 object-cover rounded-full xl:w-36 xl:h-36"/>
                   
                <div class="flex gap-3"><a href="{{route('settings')}}" class="btn btn-secondary popup-dismiss  btn-sm" type="button">
                  Cancel
              </a><input type="submit" value="Change Profile Image" class="btn btn-primary btn-sm" name="submit" /></div>
                   
            </div>

        </form>
    </div>
</x-template>
