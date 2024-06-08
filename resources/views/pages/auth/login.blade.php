
<x-app>
<head>
    @include('layouts.head')
</head>

<body ng-controller="AuthController" ng-init="initAuth({{auth()->check()?'true':'false'}})"
    class="bg-[#f7f7fa] dark:bg-black text-[#333333] font-sans font-[16px] h-full overflow-x-hidden">


    <div class="grid place-items-center h-screen max-h-screen">
        <x-route name="index"
            class="shadow-md flex max-w-[60%] md:max-w-[85%] lg:max-w-[800px] min-h-[500px] my-[2rem] mx-auto w-full bg-white dark:bg-[#0e0d0d] rounded-md overflow-clip">
            <!--left column-->
            <div class="bg-[#1a7f64] dark:bg-[#08261e] flex-1 min-h-full hidden lg:flex flex-col justify-end items-center bg-blend-multiply relative">
                <div class="border-b-4 border-solid border-[#1a7f64] font-[600] text-[22px] primary-text">Welcome to <b>{{ config('app.name') }}</b></div>
                <img class="max-w-full h-auto dark:opacity-50" src="{{ asset('img/login.png') }}" alt="Logo">
            </div>
            <!--/left column-->


            <!--right column-->
            <div class="flex-1 min-h-full p-[38px] grid place-items-center relative border-t-8 border-[#1a7f64] lg:border-none">
                <div class="w-full relative z-10">
                    

                    <h2 class="text-2xl text-center primary-text font-bold">Log In</h2>
                    <form>
                       


                        <div class="flex flex-col gap-4 mt-3">
                            <div class="custom-input">
                                <x-icon name="account_circle" class="icon-md"/>
                                <input type="text" class="input-bottom" placeholder="Email or Phone"
                                    name="credential" ng-model="loginData.usermail" />

                            </div>

                            <div class="custom-input" ng-init="visible=false">
                                <x-icon name="lock" class="icon-md"/>
                                <input type="{% visible?'text':'password' %}" class="input-bottom appearance-none" placeholder="Password" name="password"
                                    ng-model="loginData.password" autocomplete="off"/>
                                    <i class="fa" ng-class="{'fa-eye-slash':!visible, 'fa-eye':visible}" ng-click="visible=!visible"></i>
                            </div>
                            
                            <div class="flex mb-[15px] justify-between">
                                <div class="remember-me">
                                    <label class="flex items-center gap-1">

                                        <input type="checkbox" ng-model="loginData.rememberme" name="rememberme" value="remember" class="switch">
                                        <span class="checkmark peer-checked:font-semibold">Remember me</span>
                                    </label>
                                </div>
                                <a href="#lostpassword" ng-click="popUp('resetPassword')">Forgot Password?</a>
                            </div>
                        </div>












                        <div class="flex flex-col mt-3 gap-3">
                            
                            
                            <button type="button" values="{sent:'Logged In', sending: 'Loggin In...', error: 'Failed'}" controller="login('{{request()->get('callbackUrl')}}')" class="btn btn-primary transition w-full">Log in</button>
                            
                            <button type="button" ng-click="popend('activateAccount')" class="btn-secondary btn">Activate Account</button>
                        </div>
                    </form>
                </div>
                <img src="{{ asset('svg/frame.svg') }}" alt="frame" class="absolute bottom-0 w-[350px] opacity-10 right-0">
            </div>
            <!--/right column-->
            
        </x-route>
        <x-route name="2fa">
            <form action="/otp" method="POST" class="popup-wrapper otp-form max-w-[500px] !p-[15px]">
                @csrf
                <div class="popup-body p-8  flex flex-col gap-3"  otp-inputs>
                    <div class="flex align-center gap-6 p-x-1 text-green-700">
                        <img src="{{ asset('svg/logo.svg') }}" alt="logo" width="48">
                        <div>
                            <p class="font-size-2 text-body-600 font-bold">Department of Computer Science</p>
                            <p class="font-size-1 text-body-400 font-semibold">Federal University of Technolog, Owerri</p>
                        </div>
                    </div>
                    <div class="mt-5">
    
                      
                        OTP has been sent to you. Please check your registered email address <span class="font-semibold" ng-bind="otp_user_email | maskEmail"></span> for the six-digit
                        code.
                        <div class="mt-6 opacity-65">
                            Enter the six digit code below
                        </div>
                    </div>
                    <div>
                        <div class="otp-inputs">
                            <div>
                                <input type="text" ng-disabled="false" autofocus="true" class="otp-input" placeholder="*"
                                    ng-model="otp[0]" maxLength="1" minLength="1" max="9" min="0" />
                            </div>
                            <div>
                                <input type="text" ng-model="otp[1]" maxLength="1" class="otp-input" placeholder="*"
                                    max="9" min="0" ng-disabled="!otp[0]" />
                            </div>
                            <div>
                                <input type="text" ng-model="otp[2]" maxLength="1" class="otp-input" placeholder="*"
                                    max="9" min="0" ng-disabled="!otp[1]" />
                            </div>
                            <div>
                                <input type="text" ng-model="otp[3]" maxLength="1" class="otp-input" placeholder="*"
                                    max="9" min="0" ng-disabled="!otp[2]" />
                            </div>
                            <div>
                                <input type="text" ng-model="otp[4]" maxLength="1" class="otp-input" placeholder="*"
                                    max="9" min="0" ng-disabled="!otp[3]" />
                            </div>
                            <div>
                                <input type="text" ng-model="otp[5]" maxLength="1" class="otp-input" placeholder="*"
                                    max="9" min="0" ng-disabled="!otp[4]" />
                            </div>
                        </div>
                    </div>
    
    
    
    
                    <div class="mt-5 flex flex-col">
                        <button controller="verifyOTP('{{request()->get('callbackUrl')}}')" type="submit" class="btn btn-primary btn-adaptive"
                            ng-disabled="!otp || otp.length !== 6">Verify and
                            Proceed</button>
                    </div>
    
                    <div>Did not get the code? <a href="#" class="link">Resend</a></div>
                </div>
    
            </form>
        </x-route>
    </div>
    @include('pages.auth.lost-password')
    @include('pages.auth.request_account_activation')

</body>
@include('layouts.footer', [
    'script' => asset('/js/angular/modules/auth.js')
])

</x-app>
