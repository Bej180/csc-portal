<div class="full">
<div class="card bg-zinc-50" ng-controller="AccountSetting" ng-init="SettingsFor()">
    <div class="mb-3 navigator-text" ng-click="Route.route('index')">
        <x-icon name="chevron_left"/>
        <span>Student Manager</span>
    </div>
    <div class="card-body">
       
        <div class="card-content">
            <section class="overflow-y-scroll lg:grid gap-3 lg:gap-6 grid-cols-7"
                style="height: calc(-12.5rem + 100dvh);">
                <div class="lg:col-span-2  overflow-y-auto lg:max-h-[calc(-12.5rem+100dvh)]">
                    <fieldset>
                        <legend>
                            Student Profile
                        </legend>
                        <div>
                            <div>
                                <div class="flex flex-col items-center justify-center">
                                    <div>
                                        <img id="profile-pic-container" src="{% edit_student.image %}"
                                            id="profile-image" alt="user"
                                            class="w-24 h-24 rounded-full object-cover"
                                            gender="{% edit_student.gender %}">
                                    </div>
                                    <h3 ng-bind="edit_student.name" class="font-bold"></h3>


                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>
                            <div class="flex items-center">
                                <x-icon name="account_circle"/>
                                <p>Account</p>
                            </div>
                        </legend>
                        <div>
                            <div>
                                <div class="grid gap-3">
                                    <div>
                                        <label class="text-sm font-semibold">Username</label>
                                        <input class="input w-full" ng-model="edit_student.username" />
                                    </div>
                                    <div>
                                        <button class="btn btn-primary btn-adaptive" type="button"
                                            aria-label="Change Username" ng-disabled="!edit_student.username"
                                            controller="updateProfile('username')">Change
                                            Username</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div
                    class="lg:col-span-5 flex flex-col gap-4 overflow-y-auto lg:max-h-[calc(-12.5rem+100dvh)]">
                    <fieldset>
                        <legend>
                            <div class="flex items-center"><span
                                    class="material-symbols-rounded">person</span>
                                <p>Basic Information</p>
                            </div>
                        </legend>
                        <div>
                            <div>
                                <div class="grid gap-3 lg:grid-cols-2 lg:grid-cols-3">
                                    <div>
                                        <label class="text-sm font-semibold">Full Name</label>
                                        <input class="input w-full" ng-model="edit_student.name" />
                                    </div>

                                    <div>
                                        <label class="text-sm font-semibold">Reg Number</label>
                                        <input class="input mk-reg_no w-full" ng-model="edit_student.reg_no" />
                                    </div>
                                    <div>
                                        <label class="text-sm font-semibold">Birth Date</label>
                                        <input type="date" class="input w-full" ng-model="edit_student.birthdate" />
                                    </div>

                                    <div>
                                        <label class="text-sm font-semibold">Email Address</label>
                                        <input class="input w-full" ng-model="edit_student.email" />
                                    </div>

                                    <div class="col-span-2">
                                        <label class="text-sm font-semibold">House
                                            Address</label>
                                        <textarea class="input w-full h-24" ng-model="edit_student.address"></textarea>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold">Level</div>
                                        <select ng-model="edit_student.level" placeholder="Select Level">
                                            <option value="100">100 LEVEL</option>
                                            <option value="200">200 LEVEL</option>
                                            <option value="300">300 LEVEL</option>
                                            <option value="400">400 LEVEL</option>
                                            <option value="500">500 LEVEL</option>
                                            <option>Graduate</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="text-sm font-semibold">Phone Number</label>
                                        <input class="mk-phone input"
                                            ng-model="edit_student.phone">
                                    </div>



                                    <div>
                                        <label class="text-sm font-semibold">Religion</label>
                                        <input class="input" ng-model="edit_student.religion">
                                    </div>

                                    <div>
                                        <label class="text-sm font-semibold">LGA</label>
                                        <input class="input" ng-model="edit_student.lga">
                                    </div>

                                    <div>
                                        <label class="text-sm font-semibold">State</label>
                                        <input class="input" ng-model="edit_student.state">
                                    </div>

                                    <div>
                                        <label class="text-sm font-semibold">Country</label>
                                        <input class="input" ng-model="edit_student.country" value="{% edit_student.country %}">
                                    </div>

                                    </div>
                                    <div class="mt-3">
                                        <button controller="updateProfile('basic')" class="btn btn-primary w-full" type="button">Update
                                            Changes</button>
                                    </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>
                            <div class="flex items-center"><span
                                    class="material-symbols-rounded">lock_reset</span>
                                <p>Reset Login Details</p>
                            </div>
                        </legend>
                        <div>
                                
                            <form class="flex flex-col gap-2"> 
                                <div class="input-group">
                                    <label class="input-group-prepend" for="reset_email_address">Email</label>
                                    <input class="input" type="text" id="reset_email_address" ng-model="show_student.email" placeholder="Enter Student's Email Address"/>
                                </div>
                                <div class="input-group">
                                    <label class="input-group-prepend" for="reset_username">Username</label>
                                    <input class="input" type="text" id="reset_username" ng-model="show_student.username" placeholder="Enter Student's Username"/>
                            
                                </div>
                                <label class="flex gap-1 items-center"><input class="switch" type="checkbox" ng-model="show_student.reset_password"/> <span class="peer-checked:font-semibold">Reset Password</span></label>
                                <div class="info !p-3 !rounded-md" ng-if="show_student.reset_password">Password will be reset to <span ng-bind="show_student.gender | his">student's</span> <b>Registration Number</b></div>
                                <button type="button" class="btn btn-primary" controller="updateUserLogins(show_student)">Save Changes</button>
                            </form>

                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>
                            <div class="flex items-center"><span
                                    class="material-symbols-rounded">person</span>
                                <p>Guardian Details</p>
                            </div>
                        </legend>
                        <div id="pv_id_178_content"                             aria-labelledby="pv_id_178_header">
                            <div>
                                <div class="grid gap-3 lg:grid-cols-2 lg:grid-cols-3">
                                    <div>
                                        <label class="text-sm font-semibold">Name</label>
                                        <input class="input w-full" ="edit_student.name" />
                                    </div>

                                    <div>
                                        <label class="text-sm font-semibold">Phone Number</label>
                                        <input class="input w-full" ng-model="edit_student.staff_id" />
                                    </div>
                                    <div>
                                        <label class="text-sm font-semibold">Email Address</label>
                                        <input class="input w-full" disabled readonly
                                            ng-model="edit_student.birthdate" />
                                    </div>
                                   




                                </div>
                                <div class="mt-4">
                                    <button class="btn btn-primary" controller="updateProfile('guardian')" type="button">Update
                                        Changes</button>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset ng-init="studentEnrollments(edit_student.reg_no)">
                        <legend>
                            Enrollments
                        </legend>
                        <div class="boxz">
                            <div class="box-wrapper w-full overflox-x-auto responsive-table min-w-full">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="text-center">Session</th>
                                            <th>Semester</th>
                                            <th class="text-center">Level</th>

                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="enrollment in student_enrollments">
                                            <td class="text-center" ng-bind="enrollment.session"></td>
                                            <td class="uppercase" ng-bind="enrollment.semester"></td>
                                            <td class="text-center" ng-bind="enrollment.level"></td>
                                            <td class="flex justify-center gap-2">


                                                <button
                                                    ng-click="showEnrollmentDetails(enrollment)"
                                                    class="btn btn-primary !rounded-full btn-icon-sm"
                                                    type="button" title="View">
                                                    <x-icon name="visibility"/>
                                                </button>

                                                <button title="Delete" ng-click="deleteEnrollment($event, $index, enrollment)"
                                                    class="btn btn-danger !rounded-full btn-icon-sm"
                                                    type="button">
                                                    <x-icon name="delete"/>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </fieldset>



                </div>
            </section>
        </div>
    </div>
</div>
</div>