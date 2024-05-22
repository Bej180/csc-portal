<fieldset>
    <legend>School Details</legend>

    <div>Current Session <i class="fa fa-edit"></i></div>
    <div class="pill pill-primary">
       <i class="fa fa-calendar"></i> 2022-2023</div>


       <div>Current Semester <i class="fa fa-edit"></i></div>
    <div class="pill pill-primary">
       <i class="fa fa-calendar"></i> RAIN</div>
</fieldset>

<fieldset>
    <legend> <i class="fa fa-calendar"></i> Create New Session</legend>

    Name
    Start Year
    Current Year 

</fieldset>



<fieldset>
    <legend>
        Course Registration
    </legend>

    <div>Status <i class="fa fa-close"></i></div>
    <div class="pill pill-primary">Open</div>

    <div>Sessions Open:




<x-template>
    <x-wrapper active="Control Panel">
        <ul class="nav nav-tabs nav-tabs-bottom">
            <li class="nav-item"><a class="nav-link active" href="#calendar" data-bs-toggle="tab">Current Academic
                    Calendar</a></li>
            <li class="nav-item"><a class="nav-link" href="#account" data-bs-toggle="tab">Account Setting</a></li>
            <li class="nav-item"><a class="nav-link" href="#unit" data-bs-toggle="tab">Unit Allocation</a></li>
            <li class="nav-item"><a class="nav-link" href="#notification" data-bs-toggle="tab">Notifications</a></li>
            <li class="nav-item"><a class="nav-link" href="#resource" data-bs-toggle="tab">Resource Management</a></li>
        </ul>


        <div class="tab-content mt-5">
            <div class="tab-pane show active" id="calendar">

                <div class="grid place-items-center">
                    <div class="popup-wrapper">
                        <div class="popup-body grid grid-cols-2 gap-2">
                            <div class="info-item">
                                <label class="text-xs text-zinc-500">Current Semester:</label>
                                <div><input type="text" class="input" value="Harmattan"></div>
                            </div>
                            <div class="info-item">
                                <label class="text-xs text-zinc-500">Current Session:</label>
                                <div><input type="text" class="input" value="2024/2025"></div>
                            </div>
                            <div class="info-item">
                                <label class="text-xs text-zinc-500">Semester Begins:</label>
                                <div><input type="text" class="input" value="20/10/2024"></div>
                            </div>
                            <div class="info-item">
                                <label class="text-xs text-zinc-500">Semester Ends:</label>
                                <div><input type="text" class="input" value="21/5/2025"></div>
                            </div>
                            <div class="info-item">
                                <label class="text-xs text-zinc-500">Exam begins:</label>
                                <div><input type="text" class="input" value="20/10/2024"></div>
                            </div>
                            <div class="info-item">
                                <label class="text-xs text-zinc-500">Exam Ends:</label>
                                <div><input type="text" class="input" value="21/5/2025"></div>
                            </div>
                        </div>
                        <div class="pb-2 flex justify-center items-center gap-2">
                            <button class="btn btn-primary">Save Changes</button>
                            <button class="btn btn-white">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="account">

                <div class="info-item">
                    <label>2Factor Auth:</label>
                    <div>
                        <select class="input">
                            <option value="enabled">Enabled</option>
                            <option value="disabled">Disabled</option>
                        </select>
                    </div>
                </div>
                <div class="info-item">
                    <label>Password:</label>
                    <div><button>Change</button></div>
                </div>
                <div class="info-item">
                    <label>Login Attempt Limit:</label>
                    <div><input type="number" value="5" class="input"></div>
                </div>


            </div>
            <div class="tab-pane" id="unit">

                <div class="info-item">
                    <label>Max Units for Current Semester:</label>
                </div>
                <div class="info-item">
                    <label>100 Level:</label>
                    <div><input type="number" value="21" class="input"></div>
                </div>
                <div class="info-item">
                    <label>200 Level:</label>
                    <div><input type="number" value="25" class="input"></div>
                </div>
                <div class="info-item">
                    <label>300 Level:</label>
                    <div><input type="number" value="24" class="input"></div>
                </div>
                <div class="info-item">
                    <label>400 Level:</label>
                    <div><input type="number" value="15" class="input"></div>
                </div>
                <div class="info-item">
                    <label>500 Level:</label>
                    <div><input type="number" value="23" class="input"></div>
                </div>

            </div>
            <div class="tab-pane" id="notification">
              <div class="flex flex-col gap-2">

                <div class="info-item">
                    <span>Email Notifications:</span>
                    <shuffle class="link font-bold" options="['Enabled', 'Disabled']" ng-model="email_notifications">
                    </shuffle>

                </div>

                <div class="info-item">
                    <span>New Device Notifications:</span>
                    <shuffle class="link font-bold" options="['Enabled', 'Disabled']"
                        ng-model="new_device_notifications"></shuffle>
                </div>
                <div class="info-item">
                    <span>Account Creation:</span>
                    <shuffle class="link font-bold" options="['Enabled', 'Disabled']"
                        ng-model="account_creation_notification"></shuffle>

                </div>
                <div class="info-item">
                    <span>Lost Password:</span>
                    <shuffle class="link font-bold" options="['Enabled', 'Disabled']"
                        ng-model="lost_password_notification"></shuffle>

                </div>
                <div class="info-item">
                    <span class="text-xs text-zinc-500">OTP:</span>
                    <shuffle class="link font-bold" options="['Enabled', 'Disabled']" ng-model="otp_notification">
                    </shuffle>

                </div>
              </div>

            </div>
            <div class="tab-pane" id="resource">

                <div class="info-item">
                    <label class="text-xs text-zinc-500">Upload Resources:</label>
                    <div><button>Upload</button></div>
                </div>
                <div class="info-item">
                    <label class="text-xs text-zinc-500">View Resources:</label>
                    <div><button>View</button></div>
                </div>

            </div>
            <div class="tab-pane" id="basic-info"></div>
        </div>

    </x-wrapper>
</x-template>
