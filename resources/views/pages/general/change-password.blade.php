<x-template>
    <div class="popup">

        <form action="/updateprofile" method="POST" class="popup-wrapper">
            <h1 class="text-sm font-semibold popup-header">Change Password</h1>
            <div class="popup-body w-full flex flex-col gap-4 md:grid md:grid-cols-2 lg:grid-cols-3">
                @csrf
                <div class="w-full">
                    <label for="old-password" class="text-gray-400">
                        Old Password
                    </label>
                    <input type="password" id="old-password" name="oldPassword" class="input rounded w-full">
                    @error('oldPassword')
                        <x-alert type="error">{{ $message }}</x-alert>
                    @enderror
                </div>

                <div>
                    <label for="new-password" class="text-gray-400">
                        New Password
                    </label>
                    <input type="password" id="new-password" name="password" class="input rounded w-full">
                    @error('password')
                        <x-alert type="error">{{ $message }}</x-alert>
                    @enderror
                </div>

                <div>
                    <label for="confirm-password" class="text-gray-400">
                        Confirm Password
                    </label>
                    <input type="password" id="confirm-password" name="password_confirmation"
                        class="input rounded w-full">
                </div>
            </div>

            <div class="popup-footer">
              
              <a href="{{route('settings')}}" class="btn btn-secondary popup-dismiss  btn-sm" type="button">
                  Cancel
              </a>
                <button class="btn btn-primary btn-sm" type="submit">
                    Change Password
                </button>
            </div>
        </form>
    </div>
</x-template>
