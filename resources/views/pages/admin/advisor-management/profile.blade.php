<x-popend title="Profile" name="show_advisor">
    <div class="col-md-12">
        <div class="mb-[20px] rounded-lg bg-[#f7f7fa]">
            <div class="profile-bg-img">
                <img class="rounded-lg overflow-cover w-full" src="{{ asset('img/profile-bg.jpg') }}" alt="Profile">
            </div>

            <div class="flex justify-start items-center">
                <div class="shrink-0 mx-[20px] relative -top-[30px]">
                    <img class="profile-pic" src="{% show_advisor.image %}" alt="Profile">
                    <div class="uploader-btn">
                        <label class="hide-uploader">
                            <i class="feather-edit-3"></i><input type="file">
                        </label>
                    </div>
                </div>
                <div class="names-profiles">
                    <h4 class="text-2xl" ng-bind="show_advisor.user.name"></h4>
                    <div class="header5">Electronics</div>
                </div>
            </div>

        </div>
    </div>



    <div class="card-body">
        <ul class="nav nav-tabs nav-bordered nav-justified">
            <li class="nav-item">
                <a href="#pro-personal" id="pro-personal-tab" data-bs-toggle="tab" aria-expanded="true"
                    class="nav-link active">
                    Personal
                </a>
            </li>
            <li class="nav-item">
                <a href="#pro-aboutme" id="pro-aboutme-tab" arial-controls="pro-aboutme" data-bs-toggle="tab"
                    class="nav-link">
                    About Me
                </a>
            </li>

            <li class="nav-item">
              <a href="#pro-settings" id="pro-settings-tab" arial-controls="pro-settings" data-bs-toggle="tab"
                  class="nav-link">
                  Settings
              </a>
          </li>
        </ul>

        <div class="tab-content mt-4">
            <div class="tab-pane fade active show" id="pro-personal" role="tabpanel" aria-labelledby="pro-personal-tab">
                <div>
                    <div class="flex gap-3 mb-3">
                        <div class="opacity-25">
                            <i class="feather-user"></i>
                        </div>
                        <div class="views-personal">
                            <div class="header4">Name</div>
                            <div class="header5" ng-bind="show_advisor.user.name"></div>
                        </div>
                    </div>

                    <div class="flex gap-3 mb-3">
                        <div class="opacity-25">
                            <i class="feather-phone-call"></i>
                        </div>
                        <div class="views-personal">
                            <div class="header4">Mobile</div>
                            <div class="header5" ng-bind="show_advisor.user.phone||'NA'">+21 510-237-1901</div>
                        </div>
                    </div>
                    <div class="flex gap-3 mb-3">
                        <div class="opacity-25">
                            <i class="feather-mail"></i>
                        </div>
                        <div class="views-personal">
                            <div class="header4">Email</div>
                            <div class="header5" ng-bind="show_advisor.user.email"></div>
                        </div>
                    </div>
                    <div class="flex gap-3 mb-3">
                        <div class="opacity-25">
                            <i class="feather-user"></i>
                        </div>
                        <div class="views-personal">
                            <div class="header4">Gender</div>
                            <div class="header5" ng-bind="show_advisor.gender"></div>
                        </div>
                    </div>
                    <div class="flex gap-3 mb-3">
                        <div class="opacity-25">
                            <i class="feather-calendar"></i>
                        </div>
                        <div class="views-personal">
                            <div class="header4">Date of Birth</div>
                            <div class="header5" ng-bind="show_advisor.birthdate"></div>
                        </div>
                    </div>
                    <div class="flex gap-3 mb-3">
                        <div class="opacity-25">
                            <i class="feather-italic"></i>
                        </div>
                        <div class="views-personal">
                            <div class="header4">Class</div>
                            <div class="header5">CSC 23</div>
                        </div>
                    </div>
                    <div class="flex gap-3 mb-3 mb-0">
                        <div class="opacity-25">
                            <i class="feather-map-pin"></i>
                        </div>
                        <div class="views-personal">
                            <div class="header4">Address</div>
                            <div class="header5" ng-bind="show_advisor.address"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pro-aboutme" role="tabpanel" aria-labelledby="pro-aboutme-tab">
                <div>
                    <div class="flex flex-col gap-4" ng-bind="show_advisor.aboutme">

                    </div>
                    <div class="flex flex-col gap-3">
                        <div class="header5">Education</div>
                        <div class="educate-year">
                            <div class="header6">2008 - 2009</div>
                            <p>Secondary Schooling at xyz school of secondary education, Mumbai.</p>
                        </div>
                        <div class="educate-year">
                            <div class="header6">2011 - 2012</div>
                            <p>Higher Secondary Schooling at xyz school of higher secondary
                                education,
                                Mumbai.
                            </p>
                        </div>
                        <div class="educate-year">
                            <div class="header6">2012 - 2015</div>
                            <p>Bachelor of Science at Abc College of Art and Science, Chennai.</p>
                        </div>
                        <div class="educate-year">
                            <div class="header6">2015 - 2017</div>
                            <p class="mb-0">Master of Science at Cdm College of Engineering and
                                Technology,
                                Pune.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="tab-pane fade" id="pro-settings" role="tabpanel" aria-labelledby="pro-settings-tab">
                @include('pages.admin.advisor-management.edit')
            </div>
        </div>

    </div>
</x-popend>