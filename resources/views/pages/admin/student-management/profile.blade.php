<x-popend title="Student Profile" name="show_student">
    <div class="col-md-12">
        <div class="mb-[20px] rounded-lg bg-[#f7f7fa]">
            <div class="profile-bg-img">
                <img class="rounded-lg overflow-cover w-full" src="{{ asset('img/profile-bg.jpg') }}" alt="Profile">
            </div>

            <div class="flex justify-start items-center">
                <div class="shrink-0 mx-[20px] relative -top-[30px]">
                    <img class="profile-pic" src="/profilepic/{% show_student.id %}" alt="Profile" />
                    <div class="uploader-btn">
                        <label class="hide-uploader">
                            <i class="feather-edit-3"></i><input type="file">
                        </label>
                    </div>
                </div>
                <div class="names-profiles">
                    <h4 class="text-2xl" ng-bind="show_student.user.name"></h4>
                    <div class="header5">Student</div>
                </div>
            </div>

        </div>
    </div>



    <div class="card-body">


        <div class="p-4 flex flex-col gap-3">
            <div class="flex gap-3">
                <div class="opacity-25">
                    <i class="feather-user"></i>
                </div>
                <div class="views-personal">
                    <div class="header4">Name</div>
                    <div class="header5" ng-bind="show_student.user.name"></div>
                </div>
            </div>

            <div class="flex gap-3">
                <div class="opacity-25">
                    <i class="feather-phone-call"></i>
                </div>
                <div class="views-personal">
                    <div class="header4">Mobile</div>
                    <div class="header5" ng-bind="show_student.user.phone||'NA'"></div>
                </div>
            </div>

            <div class="flex gap-3">
                <div class="opacity-25">
                    <i class="feather-mail"></i>
                </div>
                <div class="views-personal">
                    <div class="header4">Email</div>
                    <div class="header5" ng-bind="show_student.user.email"></div>
                </div>
            </div>

            <div class="flex gap-3">
                <div class="opacity-25">
                    <i class="feather-user"></i>
                </div>
                <div class="views-personal">
                    <div class="header4">Gender</div>
                    <div class="header5" ng-bind="show_student.gender"></div>
                </div>
            </div>

            <div class="flex gap-3">
                <div class="opacity-25">
                    <i class="feather-calendar"></i>
                </div>
                <div class="views-personal">
                    <div class="header4">Date of Birth</div>
                    <div class="header5" ng-bind="show_student.birthdate"></div>
                </div>
            </div>

            <div class="flex gap-3">
                <div class="opacity-25">
                    <i class="feather-italic"></i>
                </div>
                <div class="views-personal">
                    <div class="header4">Class</div>
                    <div class="header5">CSC 23</div>
                </div>
            </div>

            <div class="flex gap-3">
                <div class="opacity-25">
                    <i class="feather-map-pin"></i>
                </div>
                <div class="views-personal">
                    <div class="header4">Address</div>
                    <div class="header5" ng-bind="show_student.address"></div>
                </div>
            </div>

            <div class="flex">
                <button type="button" ng-click="ResetPassword(show_student,show_student.reg_no)" class="btn btn-primary flex-2">Reset Password</button>
            </div>
        </div>


    </div>
</x-popend>
