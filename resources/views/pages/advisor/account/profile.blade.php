@php
    $user = auth()->user();
    auth()->user()->loadNames();

    $profile = $user->profile;
    
    
@endphp
<x-template title="My Profile" nav="profile">
    <div class="scroller">
        <div id="profile-container" class="flex flex-col gap-4 items-center p-4 xl:grid xl:grid-cols-4">
            <div
            class="flex flex-col items-center
            xl:col-span-1 xl:row-span-2 xl:justify-start xl:h-full">
                <form enctype="multipart/form-data" action="/updateprofile" method="POST" id="change-image-form" class="flex items-center gap-1 xl:flex-col">
                    @csrf
                    <div class="relative w-24 h-24 xl:w-36 xl:h-36">
                        <x-profile-pic id="previewImage" data-image="user" :user="$profile" alt="user_img" class="w-24 h-24 object-cover rounded-full xl:w-36 xl:h-36"/>
                        <input type="file" preview="previewImage"  name="profileImageSelect" id="select-profile-image" accept=".png, .jpg, .jpeg"
                                class="opacity-0 h-full w-full absolute z-10 top-0 left-0">
                    </div>
                    <input type="submit" value="Change Profile Image" class="btn text-white rounded bg-[var(--accent)] hover:bg-[var(--accent-600)] transition xl:text-sm" name="submit"/>
                </form>
            </div>

            <div
            class="flex flex-col gap-3 items-center w-full
            xl:col-span-3 xl:items-start">
                <h1 class="text-sm font-semibold">Basic Information</h1>
                <form action="/updateprofile" method="POST" class="w-full flex flex-col gap-4
                md:grid md:grid-cols-2
                lg:grid-cols-3">
                @csrf
                    <div class="w-full">
                        <label for="first-name" class="text-gray-400">
                            First Name
                        </label>
                        <input type="text" id="first-name" name="firstname" class="input rounded w-full" value="{{old('firstname',$user->firstname)}}">
                    </div>

                    <div>
                        <label for="middle-name" class="text-gray-400">
                            Middle Name
                        </label>
                        <input type="text" id="middle-name" name="middlename" class="input rounded w-full" value="{{old('middlename',$user->middlename)}}">
                    </div>

                    <div>
                        <label for="last-name" class="text-gray-400">
                            Last Name
                        </label>
                        <input type="text" id="last-name" name="lastname" class="input rounded w-full" value="{{old('lastname',$user->lastname)}}">
                    </div>

                    <div>
                        <label for="email" class="text-gray-400">
                            Email Address
                        </label>
                        <input type="email" id="email" name="email" class="input rounded w-full" value="{{old('email',$user->email)}}">
                    </div>

                    <div class="lg:col-span-2">
                        <label for="home-address" class="text-gray-400">
                            Home Address
                        </label>
                        <input type="text" id="home-address" name="address" class="input rounded w-full" value="{{old('address', $profile->address)}}">
                    </div>

                    <div>
                        <label for="phone" class="text-gray-400">
                            Phone Number
                        </label>
                        <input type="text" id="phone" name="phone" class="input rounded w-full" value="{{old('phone', $user->phone)}}">
                    </div>

                    <div class="lg:col-span-3">
                        <button
                        class="btn text-white rounded bg-[var(--primary)] transition hover:bg-[var(--primary-700)]"
                        type="submit">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <div
            class="flex flex-col gap-3 items-center w-full
            xl:col-span-3 xl:items-start">
                <h1 class="text-sm font-semibold">Change Password</h1>
                <form
                action="/updateprofile"
                method="POST"
                class="w-full flex flex-col gap-4
                md:grid md:grid-cols-2
                lg:grid-cols-3">
                @csrf
                    <div class="w-full">
                        <label for="old-password" class="text-gray-400">
                            Old Password
                        </label>
                        <input type="password" id="old-password" name="oldPassword" class="input rounded w-full">
                        @error('oldPassword') 
                            <x-alert type="error">{{$message}}</x-alert>
                        @enderror
                    </div>
            
                    <div>
                        <label for="new-password" class="text-gray-400">
                            New Password
                        </label>
                        <input type="password" id="new-password" name="password" class="input rounded w-full">
                        @error('password') 
                            <x-alert type="error">{{$message}}</x-alert>
                        @enderror
                    </div>
            
                    <div>
                        <label for="confirm-password" class="text-gray-400">
                            Confirm Password
                        </label>
                        <input type="password" id="confirm-password" name="password_confirmation" class="input rounded w-full">
                    </div>
            
                    <div class="md:col-span-2">
                        <button class="btn text-white rounded bg-[var(--primary)] transition hover:bg-[var(--primary-700)]"
                            type="submit">
                            Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
            
</x-template>