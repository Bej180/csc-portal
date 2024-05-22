<!doctype html>
<html lang="en" ng-cloak ng-app="cscPortal" ng-controller="AuthController">

<head>
    @include('layouts.head', compact('title'))
</head>

<body ng-init="resetPassword=false;" class="bg-[#f7f7fa] text-[#333333] font-sans font-[16px] h-full overflow-x-hidden">


    <div class="grid place-items-center h-screen max-h-screen">
        <div
            class="shadow-md flex max-w-[85%] lg:max-w-[800px] min-h-[500px] my-[2rem] mx-auto w-full bg-white rounded-md overflow-clip">
            <!--left column-->
            <div
                class="bg-orange-500 flex-1 min-h-full hidden lg:flex flex-col justify-end items-center bg-blend-multiply relative">
                <b class="text-white">{{ config('app.name') }}</b>
                <img class="max-w-full h-auto" src="{{ asset('img/login.png') }}" alt="Logo">
            </div>
            <!--/left column-->


            <!--right column-->
            <div class="flex-1 min-h-full p-[38px] grid place-items-center relative">
                <fieldset class="w-full relative z-10">
                    

                    <h2 class="text-2xl font-bold  text-center primary-text">Register</h2>
                    <form action="index.html">

                        @if(isset($invitation))
                            <div class="input mt-4">

                                Welcome, with this link you can join  <a class="font-bold link">CSC {{$invitation->name}}</a> admission session
                                <input type="hidden" ng-init="registerData.set_id='{{$invitation->id}}'" ng-model="registerData.set_id"/>
                            </div>
                        @endif

                        <ul class="nav nav-tabs nav-tabs-bottom nav-justified mt-4">
                            <li class="nav-item">
                                <a href="#basic-info" data-bs-toggle="tab" aria-expanded="false"
                                    class="nav-link active">
                                    Basic Info
                                </a>
                            </li>
                            <li class="nav-item" ng-class="{disabled:!surname || !othernames || !registerData.reg_no}">
                                <a href="#contact-info" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                    Contact Info
                                </a>
                            </li>
                            <li class="nav-item" ng-class="{disabled:!surname || !othernames || !registerData.reg_no}">
                                <a href="#personal-info" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                    Personal Info
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content pt-2">
                            <div class="tab-pane active show" id="basic-info">

                                <div class="flex flex-col gap-4 mt-3">
                                    <div class="grid grid-cols-1 gap-3">
                                       
                                            <div class="custom-input">
                                                <input type="text" class="input-bottom" placeholder="Surname"
                                                    ng-model="surname" />

                                            </div>
                                        
                                            <div class="custom-input">
                                                <input type="text" class="input-bottom" placeholder="Other Names"
                                                    ng-model="othernames" />
                                            </div>

                                            
                                        
                                            <div class="custom-input">
                                                <input type="number" class="input-bottom" placeholder="Reg Number"
                                                    ng-model="registerData.reg_no" />
                                            </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="tab-pane" id="contact-info">
                                <div class="flex flex-col gap-4 mt-3">

                                    <div>
                                        <div class="custom-input">
                                            <input type="text" class="input-bottom" placeholder="Contact Address"
                                                ng-model="registerData.address" />

                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <div class="custom-input">
                                                <input type="text" class="input-bottom" placeholder="LGA"
                                                    ng-model="registerData.lga" />

                                            </div>
                                        </div>

                                        <div>
                                            <div class="custom-input">
                                                <input type="text" class="input-bottom" placeholder="State"
                                                    ng-model="registerData.state" />
                                            </div>
                                        </div>
                                        <div>
                                            <div class="custom-input">
                                                <input type="text" class="input-bottom" placeholder="Country"
                                                    ng-model="registerData.country" />
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane" id="personal-info">

                                <div class="flex flex-col gap-4 mt-3">



                                    <div class="flex gap-3">
                                        <div class="flex-1">
                                            <div class="custom-input">
                                                <input type="email" class="input-bottom" placeholder="Email Address"
                                                    ng-model="registerData.email" />

                                            </div>
                                        </div>

                                        <div class="flex-1">
                                            <div class="custom-input">
                                                <input type="phone" class="input-bottom" placeholder="Phone Number"
                                                    ng-model="registerData.phone" />
                                            </div>
                                        </div>
                                    </div>




                                    <div class="flex gap-3">
                                        <div class="flex-1">
                                            <div class="custom-input">
                                                <input type="password" class="input-bottom"
                                                    placeholder="Password" name="password" ng-model="registerData.password" />
                                            </div>
                                        </div>

                                        <div class="flex-1">
                                            <div class="custom-input">
                                                <input type="password" class="input-bottom"
                                                    placeholder="Confirm Password" ng-model="registerData.password_confirmation" />
                                                
                                            </div>
                                        </div>
                                    </div>



                                    



                                    <div class="flex flex-col mt-3">
                                        <submit ng-click="register($event)" state="{%status%}"
                                            class="btn btn-secondary transition w-full" value="Register"/>
                                    </div>

                                    
            


                                </div>
                            </div>
                        </div>


                     







                        <a class="block p-1 mt-4 text-right" href="/login">

                            <span class="text-black">Already have an account?</span> Login
                        </a>




                       
                    </form>
                </fieldset>
                <img src="{{ asset('svg/frame.svg') }}" alt="frame"
                    class="absolute bottom-0 w-[350px] opacity-10 right-0">
            </div>
            <!--/right column-->




        </div>
    </div>
    @include('pages.auth.lost-password')

</body>
@include('layouts.footer')

</html>
