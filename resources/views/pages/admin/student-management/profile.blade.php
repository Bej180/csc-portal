<x-popend title="Student Profile" name="show_student" ng-controller="AccountSetting">
    <div class="col-md-12">
        <div class="mb-[20px] rounded-lg bg-[#f7f7fa] dark:bg-[#22242259]">
            <div class="profile-bg-img">
                <img class="rounded-lg overflow-cover w-full" src="{{ asset('img/profile-bg.jpg') }}" alt="Profile">
            </div>

            <div class="flex justify-start items-center">
                <div class="shrink-0 mx-[20px] relative -top-[30px]">
                    <avatar user="show_student" class="profile-pic" alt="Profile"></avatar>
                    <div class="uploader-btn">
                        <label class="hide-uploader">
                            <i class="feather-edit-3"></i><input type="file">
                        </label>
                    </div>
                </div>
                <div class="names-profiles">
                    <h4 class="text-2xl" ng-bind="show_student.name"></h4>
                    <div class="header5">Student</div>
                </div>
            </div>

        </div>
    </div>

    <div class="flexx">
        <button ng-click="editStudent(show_student)" class="btn btn-secondary w-full">Edit Account</button>
    </div>


    <div class="card-body">

        <div class="horizontal-divider"></div>
    <div class="grid grid-cols-5 mt-4">
        <p class="px-2 rounded-l-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-1">
            Reg No:</p>
        <p class="px-2 rounded-r-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-4"
            ng-bind="show_student.reg_no"></p>

        <p
            class="px-2 rounded-l-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-1 bg-green-50/35 dark:bg-[#22242259] text-[--highlight-text-color]">
            Sex:</p>
        <p class="px-2 rounded-r-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-4 bg-green-50/35 dark:bg-[#22242259] text-[--highlight-text-color] font-semibold uppercase"
            ng-bind="show_student.gender"></p>

        <p class="px-2 rounded-l-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-1">
            Phone:</p>
        <p class="px-2 rounded-r-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-4"
            ng-bind="show_student.phone"></p>

        <p
            class="px-2 rounded-l-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-1 bg-green-50/35 dark:bg-[#22242259] text-[--highlight-text-color]">
            Address:</p>
        <p class="px-2 rounded-r-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-4 bg-green-50/35 dark:bg-[#22242259] text-[--highlight-text-color] font-semibold"
            ng-bind="show_student.address"></p>

        <p class="px-2 rounded-l-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-1">
            Email:</p>
        <p class="px-2 rounded-r-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-4"
            ng-bind="show_student.email"></p>

            <p
            class="px-2 rounded-l-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-1 bg-green-50/35 dark:bg-[#22242259] text-[--highlight-text-color]">
            Date of Birth:</p>
        <p class="px-2 rounded-r-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-4 bg-green-50/35 dark:bg-[#22242259] text-[--highlight-text-color] font-semibold"
            ng-bind="show_student.birthdate"></p>

            <p class="px-2 rounded-l-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-1">
                Class:</p>
            <p class="px-2 rounded-r-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-4" ng-bind="show_student.class.name||'NA'"
                ></p>

                <p
                class="px-2 rounded-l-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-1 bg-green-50/35 dark:bg-[#22242259] text-[--highlight-text-color]">
                Level:</p>
            <p class="px-2 rounded-r-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-4 bg-green-50/35 dark:bg-[#22242259] text-[--highlight-text-color] font-semibold"
                ng-bind="show_student.birthdate"></p>
            
                <p class="px-2 rounded-l-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-1">
                    CGPA:</p>
                <p class="px-2 rounded-r-md py-1 sm:py-2 text-center sm:text-left col-span-5 sm:col-span-4"
                ng-bind="show_student.cgpa"></p>
    </div>


    <div class="horizontal-divider"></div>

    <div class="px-2">
        <button type="button" class="text-sm font-semibold flex items-center justify-between w-full"
            ng-click="show_class=!show_class">

            <span class="font-[600]">Reset Login Details</span>
            <span class="fa"
                ng-class="{'fa-chevron-up':!show_class, 'fa-chevron-down': show_class}"></span>
        </button>
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




    </div>
</x-popend>
