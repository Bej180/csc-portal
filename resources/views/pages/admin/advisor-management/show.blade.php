<x-template>

    <x-wrapper active="Advisors Details" :navs="['/admin/advisors' => 'Advisors']">
        <div class="card">
            <div class="card-body">
                <div class="col-md-12">
                    <div class="about-info">
                        <h4>Profile <span><a href="javascript:;"><i class="feather-more-vertical"></i></a></span></h4>
                    </div>
                    <div class="mb-[40px] rounded-lg bg-[#f7f7fa]">
                        <div class="profile-bg-img">
                            <img class="rounded-lg overflow-cover w-full" src="{{ asset('img/profile-bg.jpg') }}"
                                alt="Profile">
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="col-lg-4 col-md-4">
                                <div class="flex justify-start items-center">
                                    <div class="mx-[20px] relative -top-[30px]">
                                        <img class="profile-pic" src="{{ asset('img/profiles/avatar-18.jpg') }}"
                                            alt="Profile">
                                        <div class="uploader-btn">
                                            <label class="hide-uploader">
                                                <i class="feather-edit-3"></i><input type="file">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="names-profiles">
                                        <h4 class="text-2xl">Joe Kelley</h4>
                                        <div class="header5">Electronics</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 d-flex align-items-center">
                                <div class="flex justify-between">
                                    <div class="students-follows">
                                        <div class="header5">Followers</div>
                                        <div class="header4">2850</div>
                                    </div>
                                    <div class="students-follows">
                                        <div class="header5">Followers</div>
                                        <div class="header4">2850</div>
                                    </div>
                                    <div class="students-follows">
                                        <div class="header5">Followers</div>
                                        <div class="header4">2850</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 d-flex align-items-center">
                                <div class="follow-btn-group">
                                    <button type="submit" class="btn btn-info follow-btns">Follow</button>
                                    <button type="submit" class="btn btn-info message-btns">Message</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:grid lg:grid-cols-3 gap-8">
                    <div class="col-span-1">
                        <div class="student-personals-grp">
                            <div class="card">
                                <div class="card-body">
                                    <div class="heading-detail">
                                        <div class="header4">Personal Details :</div>
                                    </div>
                                    <div class="flex gap-3 mb-3">
                                        <div class="opacity-25">
                                            <i class="feather-user"></i>
                                        </div>
                                        <div class="views-personal">
                                            <div class="header4">Name</div>
                                            <div class="header5">Joe Kelley</div>
                                        </div>
                                    </div>
                                    <div class="flex gap-3 mb-3">
                                        <div class="opacity-25">
                                            <img src="{{ asset('img/icons/buliding-icon.svg') }}" alt="">
                                        </div>
                                        <div class="views-personal">
                                            <div class="header4">Department </div>
                                            <div class="header5">Electronics</div>
                                        </div>
                                    </div>
                                    <div class="flex gap-3 mb-3">
                                        <div class="opacity-25">
                                            <i class="feather-phone-call"></i>
                                        </div>
                                        <div class="views-personal">
                                            <div class="header4">Mobile</div>
                                            <div class="header5">+21 510-237-1901</div>
                                        </div>
                                    </div>
                                    <div class="flex gap-3 mb-3">
                                        <div class="opacity-25">
                                            <i class="feather-mail"></i>
                                        </div>
                                        <div class="views-personal">
                                            <div class="header4">Email</div>
                                            <h5><a href="/cdn-cgi/l/email-protection" class="__cf_email__"
                                                    data-cfemail="d4bebbb194b3b9b5bdb8fab7bbb9">[email&nbsp;protected]</a>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="flex gap-3 mb-3">
                                        <div class="opacity-25">
                                            <i class="feather-user"></i>
                                        </div>
                                        <div class="views-personal">
                                            <div class="header4">Gender</div>
                                            <div class="header5">Female</div>
                                        </div>
                                    </div>
                                    <div class="flex gap-3 mb-3">
                                        <div class="opacity-25">
                                            <i class="feather-calendar"></i>
                                        </div>
                                        <div class="views-personal">
                                            <div class="header4">Date of Birth</div>
                                            <div class="header5">12 Jun 1995</div>
                                        </div>
                                    </div>
                                    <div class="flex gap-3 mb-3">
                                        <div class="opacity-25">
                                            <i class="feather-italic"></i>
                                        </div>
                                        <div class="views-personal">
                                            <div class="header4">Language</div>
                                            <div class="header5">English, French, Bangla</div>
                                        </div>
                                    </div>
                                    <div class="flex gap-3 mb-3 mb-0">
                                        <div class="opacity-25">
                                            <i class="feather-map-pin"></i>
                                        </div>
                                        <div class="views-personal">
                                            <div class="header4">Address</div>
                                            <div class="header5">180, Estern Avenue, United States</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="student-personals-grp">
                            <div class="card mb-0">
                                <div class="card-body">
                                    <div class="heading-detail">
                                        <div class="header4">Skills:</div>
                                    </div>
                                    <div class="skill-blk">
                                        <div class="skill-statistics">
                                            <div class="skills-head">
                                                <div class="header5">Photoshop</div>
                                                <p>90%</p>
                                            </div>
                                            <div class="progress mb-0">
                                                <div class="progress-bar bg-photoshop" role="progressbar"
                                                    style="width: 90%" aria-valuenow="90" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <div class="skill-statistics">
                                            <div class="skills-head">
                                                <div class="header5">Code editor</div>
                                                <p>75%</p>
                                            </div>
                                            <div class="progress mb-0">
                                                <div class="progress-bar bg-editor" role="progressbar"
                                                    style="width: 75%" aria-valuenow="75" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <div class="skill-statistics mb-0">
                                            <div class="skills-head">
                                                <div class="header5">Illustrator</div>
                                                <p>95%</p>
                                            </div>
                                            <div class="progress mb-0">
                                                <div class="progress-bar bg-illustrator" role="progressbar"
                                                    style="width: 95%" aria-valuenow="95" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <div class="student-personals-grp">
                            <div class="card mb-0">
                                <div class="card-body">
                                    <div class="heading-detail">
                                        <div class="header4">About Me</div>
                                    </div>
                                    <div class="flex flex-col gap-4">
                                        <div class="header5">Hello I am Joe Parks</div>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                            tempor
                                            incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                                            nostrud
                                            exercitation ullamco laboris nisi ut aliquip ex commodo consequat. Duis aute
                                            irure
                                            dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
                                            pariatur. Excepteur officia deserunt mollit anim id est laborum.</p>
                                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium
                                            doloremque
                                            laudantium, totam inventore veritatis et quasi architecto beatae vitae dicta
                                            sunt
                                            explicabo. </p>
                                    </div>
                                    <div class="flex flex-col gap-3">
                                        <div class="header5">Education</div>
                                        <div class="educate-year">
                                            <div class="header6">2008 - 2009</div>
                                            <p>Secondary Schooling at xyz school of secondary education, Mumbai.</p>
                                        </div>
                                        <div class="educate-year">
                                            <div class="header6">2011 - 2012</div>
                                            <p>Higher Secondary Schooling at xyz school of higher secondary education,
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-wrapper>
</x-template>
