<x-template>
    <main class="w-full h-full overflow-scroll">
        <section class="p-5 w-dvw md:w-full">
            <div class="card bg-zinc-50" ng-controller="AccountSetting" ng-init="SettingsFor()">
                <div class="card-body">
                    <div class="card-caption">
                        <div class="card-title">Profile</div>
                    </div>
                    <div class="card-content">
                        <section class="overflow-y-scroll md:grid gap-3 lg:gap-6 grid-cols-7"
                            style="height: calc(-12.5rem + 100dvh);">
                            <div class="md:col-span-2  overflow-y-auto md:max-h-[calc(-12.5rem+100dvh)]">
                                <fieldset class="p-fieldset">
                                    <legend class="p-fieldset-legend">
                                        <div class="flex items-center"><span
                                                class="material-symbols-rounded">add_a_photo</span>
                                            <p>Profile Image</p>
                                        </div>
                                    </legend>
                                    <div id="pv_id_175_content" class="p-toggleable-content" role="region"
                                        aria-labelledby="pv_id_175_header">
                                        <div class="p-fieldset-content">
                                            <div class="flex flex-col items-center justify-center">
                                                <div>
                                                    <img id="profile-pic-container" src="{% profile.image %}"
                                                        id="profile-image" alt="user"
                                                        class="w-24 h-24 rounded-full object-cover"
                                                        gender="{{ auth()->user()->gender }}">
                                                </div>
                                                <div class="info">
                                                    Upload only .jpg, .jpeg, and .png files not more than 500KB.
                                                </div>
                                                <form method="POST" action="/user/change_profile_pic"
                                                    enctype="multipart/form-data" class="mt-2">
                                                    @csrf
                                                    <label for="profile-picture"
                                                        class="overflow-hidden bg-primary p-[0.6rem] gap-x-2 flex flex-wrap items-center justify-center text-white cursor-pointer hover:bg-[--primary-700] transition-colors rounded-md">
                                                        <span class="fa fa-file-import"></span>
                                                        <p>Choose</p>
                                                        <input id="profile-picture" class="opacity-0 w-full h-0"
                                                            type="file" name="image" ng-model="image"
                                                            accept=".jpg, .png, .jpeg" name="file"
                                                            preview-at="#profile-pic-container">
                                                    </label>
                                                    <div class="flex my-3 gap-3">
                                                        <button type="submit"
                                                            class="btn btn-primary w-full text-sm lg:text-base">
                                                            <span class="btn-icon btnIcon fa fa-upload"></span></button>
                                                        <button class="btn btn-secondary h-9 w-full" type="button"
                                                            aria-label="Cancel">Cancel</button>
                                                    </div>
                                                </form>


                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="p-fieldset">
                                    <legend class="p-fieldset-legend">
                                        <div class="flex items-center"><span
                                                class="material-symbols-rounded">account_circle</span>
                                            <p>Account</p>
                                        </div>
                                    </legend>
                                    <div id="pv_id_176_content" class="p-toggleable-content" role="region"
                                        aria-labelledby="pv_id_176_header">
                                        <div class="p-fieldset-content">
                                            <div class="grid gap-3">
                                                <div>
                                                    <label class="text-sm font-semibold">Username</label>
                                                    <input class="input  p-filled w-full" ng-model="profile.username" />
                                                </div>
                                                <div>
                                                    <button class="btn btn-primary btn-adaptive" type="button"
                                                        aria-label="Change Username" ng-disabled="!profile.username"
                                                        controller="updateProfile('username')">Change
                                                        Username</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div
                                class="md:col-span-5 flex flex-col gap-4 overflow-y-auto md:max-h-[calc(-12.5rem+100dvh)]">
                                <fieldset class="p-fieldset !bg-white ">
                                    <legend class="p-fieldset-legend">
                                        <div class="flex items-center"><span
                                                class="material-symbols-rounded">person</span>
                                            <p>Basic Information</p>
                                        </div>
                                    </legend>
                                    <div id="pv_id_178_content" class="p-toggleable-content" role="region"
                                        aria-labelledby="pv_id_178_header">
                                        <div class="p-fieldset-content">
                                            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                                                <div>
                                                    <label class="text-sm font-semibold">Full Name</label>
                                                    <input class="input  p-filled w-full input-disabled" disabled
                                                        readonly ng-model="profile.name" />
                                                </div>

                                                <div>
                                                    <label class="text-sm font-semibold">Reg Number</label>
                                                    <input class="input  p-filled w-full input-disabled" disabled
                                                        readonly ng-model="profile.reg_no" />
                                                </div>
                                                <div>
                                                    <label class="text-sm font-semibold">Birth Date</label>
                                                    <input class="input input-disabled w-full" disabled readonly
                                                        ng-model="profile.birthdate" />
                                                </div>

                                                <div class="lg:col-span-2">
                                                    <label class="text-sm font-semibold">House
                                                        Address</label>
                                                    <input class="input w-full" ng-model="profile.address">
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold">Level</div>
                                                    <select ng-model="profile.level" placeholder="Select Level">
                                                        <option value="100">100 LEVEL</option>
                                                        <option value="200">200 LEVEL</option>
                                                        <option value="300">500 LEVEL</option>
                                                        <option value="400">500 LEVEL</option>
                                                        <option value="500">500 LEVEL</option>
                                                        <option value="0">Graduate</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="text-sm font-semibold">Email Address</label>
                                                    <input class="input  p-filled w-full" ng-model="profile.email" />
                                                </div>
                                                <div>
                                                    <label class="text-sm font-semibold">Phone Number</label>
                                                    <input class="p-inputmask input p-filled w-full"
                                                        ng-model="profile.phone">
                                                </div>



                                                <div>
                                                    <label class="text-sm font-semibold">Religion</label>
                                                    <input class="input p-filled w-full" ng-model="profile.religion">
                                                </div>

                                                <div>
                                                    <label class="text-sm font-semibold">LGA</label>
                                                    <input class="input p-filled w-full" ng-model="profile.lga">
                                                </div>

                                                <div>
                                                    <label class="text-sm font-semibold">State</label>
                                                    <input class="input p-filled w-full" ng-model="profile.state">
                                                </div>

                                                <div>
                                                    <label class="text-sm font-semibold">Country</label>
                                                    <input class="input p-filled w-full" ng-model="profile.country" value="{% profile.country %}">
                                                </div>

                                                <div>
                                                    <button controller="updateProfile('basic')" class="btn btn-primary" type="button">Update
                                                        Changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="p-fieldset !bg-white">
                                    <legend class="p-fieldset-legend">
                                        <div class="flex items-center"><span
                                                class="material-symbols-rounded">lock_reset</span>
                                            <p>Change Password</p>
                                        </div>
                                    </legend>
                                    <div id="pv_id_179_content" class="p-toggleable-content" role="region"
                                        aria-labelledby="pv_id_179_header">
                                        <div class="p-fieldset-content">

                                            <div class="flex flex-col md:grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                                                <div class="col-span-1 relative w-full" ng-init="visible=false">
                                                    <div class="input-wrapper">
                                                        <input placeholder="Old Password"
                                                            class="!border-none v-password w-full"
                                                            type="{% visible?'text':'password' %}"
                                                            ng-model="profile.old_password" />
                                                        <i class="fa"
                                                            ng-class="{'fa-eye-slash':!visible, 'fa-eye':visible}"
                                                            ng-click="visible=!visible"></i>
                                                    </div>
                                                </div>

                                                <div class="col-span-1 relative w-full" ng-init="visible=false">
                                                    <div class="input-wrapper">
                                                        <input placeholder="New Password"
                                                            class="!border-none v-password w-full"
                                                            type="{% visible?'text':'password' %}"
                                                            ng-model="profile.password" />
                                                        <i class="fa"
                                                            ng-class="{'fa-eye-slash':!visible, 'fa-eye':visible}"
                                                            ng-click="visible=!visible"></i>
                                                    </div>
                                                </div>

                                                <div class="col-span-1 relative w-full" ng-init="visible=false">
                                                    <div class="input-wrapper">
                                                        <input placeholder="Confirm Password"
                                                            class="!border-none v-password w-full"
                                                            type="{% visible?'text':'password' %}"
                                                            ng-model="profile.password_confirmation" />
                                                        <i class="fa"
                                                            ng-class="{'fa-eye-slash':!visible, 'fa-eye':visible}"
                                                            ng-click="visible=!visible"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <button class="btn btn-primary btn-adaptive" type="button"
                                                    aria-label="Change Password" ng-click="updateProfile('password')">Change
                                                    Password</button>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="p-fieldset !bg-white ">
                                    <legend class="p-fieldset-legend">
                                        <div class="flex items-center"><span
                                                class="material-symbols-rounded">person</span>
                                            <p>Guardian Details</p>
                                        </div>
                                    </legend>
                                    <div id="pv_id_178_content" class="p-toggleable-content" role="region"
                                        aria-labelledby="pv_id_178_header">
                                        <div class="p-fieldset-content">
                                            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                                                <div>
                                                    <label class="text-sm font-semibold">Name</label>
                                                    <input class="input  p-filled w-full input-disabled" disabled
                                                        readonly ="profile.name" />
                                                </div>

                                                <div>
                                                    <label class="text-sm font-semibold">Phone Number</label>
                                                    <input class="input  p-filled w-full input-disabled" disabled
                                                        readonly ng-model="profile.staff_id" />
                                                </div>
                                                <div>
                                                    <label class="text-sm font-semibold">Email Address</label>
                                                    <input class="input input-disabled w-full" disabled readonly
                                                        ng-model="profile.birthdate" />
                                                </div>
                                               




                                            </div>
                                            <div class="mt-4">
                                                <button class="btn btn-primary" controller="updateProfile('guardian')" type="button">Update
                                                    Changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <fieldset class="p-fieldset">
                                    <legend class="p-fieldset-legend">
                                        <div class="flex items-center"><span
                                                class="material-symbols-rounded">lock_reset</span>
                                            <p>Other Settings</p>
                                        </div>
                                    </legend>
                                    
                                    <div id="pv_id_179_content" class="p-toggleable-content" role="region"
                                        aria-labelledby="pv_id_179_header">
                                        <div class="flex flex-col gap-3">
                                            <div ng-init="initTwoFactor(profile)">
                                                <a href="#" ng-click="collapse2FA=!collapse2FA">Two Factor
                                                    Authentication Configuration</a> <i class="fa" ng-class="{'fa-check': profile.two_factor_status=='enabled'}"></i> <span class="uppercase">({% profile.two_factor_status %})</span>
                                                <div ng-if="!collapse2FA"
                                                    class="flex flex-col gap-2 border-l-4 p-4 bg-primary/5 border-primary border-solid">
                                                    <div class="form-group">
                                                        <label class="font-semibold">2FA Validation</label>
                                                        <div>
                                                            <label>
                                                                <input type="radio" ng-model="profile.two_factor_status"
                                                                    name="validationEnabled" value="enabled"
                                                                    class="peer"> <span
                                                                    class="peer-disabled:opacity-50">Enable</span></label>
                                                            <label><input type="radio" class="peer"
                                                                    name="validationEnabled"
                                                                    ng-model="profile.two_factor_status" value="disabled">
                                                                <span class="peer-disabled:opacity-50">
                                                                    Disable</span></label>
                                                        </div>
                                                    </div>
                                                    <div class="info">
                                                        <strong>Note:</strong> Enabling two-factor authentication adds
                                                        an
                                                        extra layer of security to your account. You will need to enter
                                                        a code sent to your chosen method (email or SMS) to complete the
                                                        login process.
                                                    </div>
                                                    <div
                                                        ng-class="{'opacity-45 pointer-event-none':profile.two_factor_status=='disabled'}">
                                                        <div class="form-group">
                                                            <label class="font-semibold">Method of Validation</label>
                                                            
                                                            <label><input type="radio" name="validationMethod"
                                                                    value="email" ng-model="profile.two_factor_method"
                                                                    ng-disabled="profile.two_factor_status=='disabled'"
                                                                    checked> Email</label>
                                                            <label class="text-zinc-300"><input
                                                                    ng-disabled="profile.two_factor_status=='disabled'"
                                                                    ng-model="profile.two_factor_method" type="radio"
                                                                    name="validationMethod" value="sms" disabled>
                                                                SMS
                                                                <i class="opacity-40">(unsupported)</i></label>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="font-semibold">Validation Frequency</label>
                                                            <label><input type="radio"
                                                                    ng-model="profile.two_factor_frequency"
                                                                    name="validationFrequency" value="always"
                                                                    ng-disabled="profile.two_factor_status=='disabled'"
                                                                    checked> Every time</label>
                                                            <label><input type="radio"
                                                                    ng-model="profile.two_factor_frequency"
                                                                    name="validationFrequency" value="new_device"
                                                                    ng-disabled="profile.two_factor_status=='disabled'"> When
                                                                New device visits</label>
                                                        </div>
                                                    </div>


                                                    <div class="form-group">
                                                        <button verify-password class="btn btn-primary"
                                                            controller="saveTwoFactorSettings(profile)">Save
                                                            Settings</button>
                                                    </div>
                                                </div>

                                            </div>


                                        </div>




                                        <p><a href="/activities">Review Your Activities</a></p>
                                        <p><a href="#">Add Recovery Emails</a></p>

                                    </div>
                                </fieldset>



                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-template>
