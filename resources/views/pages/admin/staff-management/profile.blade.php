<x-popend name="show_staff">
    <div ng-controller="AdvisorController">

        <div class="-mx-4 -mt-4 py-8 px-8 bg-[#f7f7fa] border-b border-zinc-300">
            <div class="col-md-12">
                <div class="mb-[20px] rounded-lg ">
                    <div class="profile-bg-img">
                        <img class="rounded-lg overflow-cover w-full" src="{{ asset('img/profile-bg.jpg') }}"
                            alt="Profile">
                    </div>

                    <div class="flex justify-start items-center">
                        <div class="shrink-0 mx-[20px] relative -top-[30px]">
                            <img class="profile-pic" src="{% show_staff.image %}" alt="Profile">
                            <div class="uploader-btn">
                                <label class="hide-uploader">
                                    <i class="feather-edit-3"></i><input type="file">
                                </label>
                            </div>
                        </div>
                        <div class="names-profiles">
                            <h4 class="text-2xl" ng-bind="show_staff.user.name"></h4>
                            <div class="header5">Staff</div>
                        </div>
                    </div>

                </div>
            </div>



            <div class="flex gap-3 mb-2 justify-evenly items-center">
                <submit ng-if="show_staff.class.length === 0" class="btn btn-primary" submit="make_advisor"
                    state="submitState.make_advisor" ng-click="makeClassAdvisor(show_staff)"
                    value="Make Class Advisor" />
                </submit>
                <div ng-if="show_staff.class.length > 0">
                    <div class="btn-group">
                        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">Class Advisor</button>
                        <div class="dropdown-menu" style="" ng-init="class_name_encode = encodeURIComponent(class.name);">
                            <span class="dropdown-header">Classes</span>
                            <a class="dropdown-item" href="/admin/classes?class={% class.name %}" ng-repeat="class in show_staff.class">{% class.name %}</a>
                            
                        </div>
                    </div>

                </div>
                <button class="btn btn-secondary">Remove Account</button>
            </div>
        </div>
        <div class="">
            <ul class="nav nav-tabs nav-tabs-bottom nav-justified">
                <li class="nav-item">
                    <a href="#pro-personal" id="pro-personal-tab" data-bs-toggle="tab" aria-expanded="true"
                        class="nav-link active">
                        Personal
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#pro-courses" id="pro-courses-tab" arial-controls="pro-courses" data-bs-toggle="tab"
                        class="nav-link">
                        Courses
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
                <div class="tab-pane fade active show" id="pro-personal" role="tabpanel"
                    aria-labelledby="pro-personal-tab">
                    <div>
                        <div class="flex gap-3 mb-3">
                            <div class="opacity-25">
                                <i class="feather-user"></i>
                            </div>
                            <div class="views-personal">
                                <div class="header4">Name</div>
                                <div class="header5" ng-bind="show_staff.user.name"></div>
                            </div>
                        </div>

                        <div class="flex gap-3 mb-3">
                            <div class="opacity-25">
                                <i class="feather-phone-call"></i>
                            </div>
                            <div class="views-personal">
                                <div class="header4">Mobile</div>
                                <div class="header5" ng-bind="show_staff.user.phone||'NA'">+21 510-237-1901</div>
                            </div>
                        </div>
                        <div class="flex gap-3 mb-3">
                            <div class="opacity-25">
                                <i class="feather-mail"></i>
                            </div>
                            <div class="views-personal">
                                <div class="header4">Email</div>
                                <div class="header5" ng-bind="show_staff.user.email"></div>
                            </div>
                        </div>
                        <div class="flex gap-3 mb-3">
                            <div class="opacity-25">
                                <i class="feather-user"></i>
                            </div>
                            <div class="views-personal">
                                <div class="header4">Gender</div>
                                <div class="header5" ng-bind="show_staff.gender"></div>
                            </div>
                        </div>
                        <div class="flex gap-3 mb-3">
                            <div class="opacity-25">
                                <i class="feather-calendar"></i>
                            </div>
                            <div class="views-personal">
                                <div class="header4">Date of Birth</div>
                                <div class="header5" ng-bind="show_staff.birthdate"></div>
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
                        <div class="flex gap-3 mb-0">
                            <div class="opacity-25">
                                <i class="feather-map-pin"></i>
                            </div>
                            <div class="views-personal">
                                <div class="header4">Address</div>
                                <div class="header5" ng-bind="show_staff.address"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pro-courses" role="tabpanel" aria-labelledby="pro-courses-tab">
                    <div>
                        <div class="flex flex-col gap-4" ng-bind="show_staff.courses">

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




                    <div class="">
                        <form>

                            <div class="popup-body flex flex-col gap-6" tabindex="-1">
                                <fieldset class="border-t border-slate-400/25">
                                    <legend class="form-title text-center mx-4 opacity-25">Basic Details</legend>
                                    <div class="md:grid md:grid-cols-1 gap-4">


                                        <div class="form-group local-forms">
                                            <label>First Name <span class="text-red-600">*</span></label>
                                            <input type="text" class="input" value="Vincent">
                                        </div>


                                        <div class="form-group local-forms">
                                            <label>Last Name <span class="text-red-600">*</span></label>
                                            <input type="text" class="input" value="Vincent">
                                        </div>



                                        <div class="form-group local-forms">
                                            <label>Middle Name</label>
                                            <input type="text" class="input" value="Vincent">
                                        </div>


                                        <div class="form-group local-forms">
                                            <label>Gender <span class="text-red-600">*</span></label>
                                            <select class="input select" tabindex="-1" aria-hidden="true">
                                                <option>Male</option>
                                                <option>Female</option>
                                            </select>
                                        </div>

                                        <div class="form-group local-forms calendar-icon">
                                            <label>Date Of Birth <span class="text-red-600">*</span></label>
                                            <input class="input datetimepicker" type="text"
                                                placeholder="29-04-2022">
                                        </div>

                                        <div class="form-group local-forms">
                                            <label>Phone Number<span class="text-red-600">*</span></label>
                                            <input type="text" class="input" value="077 3499 9959">
                                        </div>

                                        <div class="form-group local-forms">
                                            <label>Email ID <span class="text-red-600">*</span></label>
                                            <input type="email" class="input" value="vincent20@gmail.com">
                                        </div>

                                        <div class="form-group local-forms calendar-icon">
                                            <label>Joining Date <span class="text-red-600">*</span></label>
                                            <input class="input datetimepicker" type="text"
                                                placeholder="29-04-2022">
                                        </div>


                                        <div class="form-group local-forms">
                                            <label>Qualification <span class="text-red-600">*</span></label>
                                            <input class="input" type="text" value="Bachelor of Engineering">
                                        </div>
                                    </div>
                                </fieldset>







                                <fieldset class="border-t border-slate-400/25">
                                    <legend class="form-title text-center mx-4 opacity-25">Login Details</legend>
                                    <div class="md:grid grid-cols-2 gap-4">


                                        <div class="form-group local-forms">
                                            <label>Password <span class="text-red-600">*</span></label>
                                            <input type="password" class="input" value="">
                                        </div>

                                        <div class="form-group local-forms">
                                            <label>Repeat Password <span class="text-red-600">*</span></label>
                                            <input type="password" class="input" value="">
                                        </div>
                                    </div>
                                </fieldset>

                                <fieldset class="border-t border-slate-400/25">
                                    <legend class="form-title text-center mx-4 opacity-25">Address</legend>
                                    <div class="md:grid md:grid-cols-1 gap-4">

                                        <div class="form-group local-forms">
                                            <label>Address <span class="text-red-600">*</span></label>
                                            <input type="text" class="input" value="3979 Ashwood Drive">
                                        </div>

                                        <div class="form-group local-forms">
                                            <label>State <span class="text-red-600">*</span></label>
                                            <input type="text" class="input" value="Omaha">
                                        </div>

                                        <div class="form-group local-forms">
                                            <label>Country <span class="text-red-600">*</span></label>
                                            <input type="text" class="input" value="USA">
                                        </div>

                                    </div>
                                </fieldset>

                            </div>
                            <div class="popup-footer">

                                <button type="type" ng-click="stopEditing()"
                                    class="btn btn-secondary">Cancel</button>
                                <button type="submit" class="btn btn-primary">Proceed</button>
                            </div>
                        </form>
                    </div>







                </div>
            </div>

        </div>
        @include('pages.admin.staff-management.select-class-for-advisor')
    </div>
</x-popend>
