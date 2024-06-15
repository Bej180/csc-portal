<!doctype html>
<html lang="en" ng-cloak ng-app="cscPortal" ng-controller="AuthController">

<head>
    @include('layouts.head', compact('title'))
</head>

<body ng-init="resetPassword=false;" class="bg-[#f7f7fa] text-[#333333] font-sans font-[16px] h-full overflow-x-hidden">


    <div class="grid place-items-center h-screen max-h-screen">
        <div
            class="shadow-md flex max-w-[85%] lg:max-w-[800px] min-h-[500px] max-h-[500px] overflow-hidden my-[2rem] mx-auto w-full bg-white dark:bg-black print:!bg-white rounded-md">
            <!--left column-->
            <div
                class="bg-orange-500 flex-1 min-h-full hidden lg:flex flex-col justify-end items-center bg-blend-multiply relative">
                <b class="text-white">{{ config('app.name') }}</b>
                <img class="max-w-full h-auto" src="{{ asset('img/login.png') }}" alt="Logo">
            </div>
            <!--/left column-->


            <!--right column-->
            <div class="flex-1 min-h-full p-[38px] grid place-items-center relative">
                <div class="w-full relative z-10 overflow-auto h-full">


                    <h2 class="text-2xl font-bold  text-center primary-text">Register</h2>
                    <form action="index.html">
                        

                        @if (isset($invitation))
                            <div class="panel p-4">

                                Welcome, with this link you can join <a class="font-bold link">CSC
                                    {{ $invitation->name }}</a> admission session
                                <input type="hidden" ng-init="registerData.set_id='{{ $invitation->id }}'"
                                    ng-model="registerData.set_id" />
                            </div>
                        @endif




                        <div class="flex flex-col gap-4 mt-3">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-1 custom-input">
                                    <input type="text" class="input-bottom" placeholder="Surname"
                                        ng-model="registerData.surname" />

                                </div>

                                <div class="col-span-2 custom-input">
                                    <input type="text" class="input-bottom" placeholder="Other Names"
                                        ng-model="registerData.othernames" ng-disabled="!registerData.surname"/>
                                </div>
                            </div>



                            <div class="custom-input">
                                <input type="number" class="input-bottom" placeholder="Reg Number"
                                    ng-model="registerData.reg_no" mask="999999999" ng-disabled="!registerData.othernames"/>
                            </div>


                            <div>
                                <div class="custom-input">
                                    <input type="text" class="input-bottom" placeholder="Contact Address"
                                        ng-model="registerData.address" />

                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div class="col-span-1">
                                    <div class="custom-input">
                                        <input type="text" class="input-bottom" placeholder="LGA"
                                            ng-model="registerData.lga" />

                                    </div>
                                </div>

                                <div class="col-span-1">
                                    <div class="custom-input">
                                        <input type="text" class="input-bottom" placeholder="State"
                                            ng-model="registerData.state" />
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <div class="custom-input">
                                        <input type="text" class="input-bottom" placeholder="Country"
                                            ng-model="registerData.country" />
                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class="flex flex-col gap-4 mt-3">



                            <div class="flex gap-3">
                                <div class="flex-1">
                                    <div class="custom-input">
                                        <input type="email" class="input-bottom" placeholder="Email Address"
                                            ng-model="registerData.email" ng-disabled="!registerData.reg_no" autocomplete="off"/>

                                    </div>
                                </div>

                                <div class="flex-1">
                                    <div class="custom-input">
                                        <input type="phone" class="input-bottom mk-phone" ng-disabled="!registerData.email" placeholder="Phone Number"
                                            ng-model="registerData.phone" />
                                    </div>
                                </div>
                            </div>




                            <div class="flex gap-3">
                                <div class="flex-1">
                                    <div class="custom-input">
                                        
                                        <input autocomplete="home" ng-disabled="!registerData.phone" type="password" class="input-bottom" placeholder="Password"
                                             ng-model="registerData.password"/>
                                    </div>
                                </div>

                                <div class="flex-1">
                                    <div class="custom-input">
                                        <input type="password" ng-disabled="!registerData.password" class="input-bottom appearance-none" placeholder="Confirm Password"
                                            ng-model="registerData.password_confirmation" autocomplete="new-password" />

                                    </div>
                                </div>
                            </div>

                            <label class="flex items-center gap-1">
                                <input type='checkbox' ng-disabled="!registerData.surname||!registerData.othernames||!registerData.reg_no||!registerData.email||!registerData.phone||!registerData.password" required ng-model="registerData.checkpolicy" class="peer"/>
                                <span class="peer-checked:font-semibold text-xs">You accept that the data you provided are valid. Some may not be changed later</span>
                                
                            </label>






                            <div class="flex flex-col mt-3">
                                <button ng-disabled="!registerData.surname||!registerData.othernames||!registerData.reg_no||!registerData.email||!registerData.phone||!registerData.password|| !registerData.checkpolicy" controller="register()"
                                    class="btn btn-primary transition w-full">Register</button>
                            </div>





                        </div>











                        <a class="block p-1 mt-4 text-right" href="/login">

                            <span class="text-black">Already have an account?</span> Login
                        </a>





                    </form>
                </div>
                <img src="{{ asset('svg/frame.svg') }}" alt="frame"
                    class="absolute bottom-0 w-[350px] opacity-10 right-0">
            </div>
            <!--/right column-->




        </div>
    </div>
    @include('pages.auth.lost-password')

</body>
@include('layouts.footer')
<script type="module" src="{{ asset('js/angular/modules/auth.js')}}"></script>

</html>
