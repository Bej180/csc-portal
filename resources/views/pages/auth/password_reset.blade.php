<!doctype html>
<html lang="en" ng-cloak ng-app="cscPortal" ng-controller="RootController">

<head>
    @include('layouts.head')
</head>

<body ng-controller="AuthController" ng-init="password=null;password_confirmation=null;token='{{ $token }}';"
    class="bg-[#f7f7fa] text-[#333333] font-sans font-[16px] h-full overflow-x-hidden">


    <div class="grid place-items-center h-screen max-h-screen">
        <div
            class="shadow-md flex max-w-[60%] lg:max-w-[800px] min-h-[500px] my-[2rem] mx-auto w-full bg-white rounded-md overflow-clip">
            <!--left column-->
            <div
                class="bg-[#1a7f64] flex-1 min-h-full hidden lg:flex flex-col justify-end items-center bg-blend-multiply relative">
                <div class="border-b-4 border-solid border-[#1a7f64] font-[600] text-[22px] primary-text">Welcome to
                    <b>{{ config('app.name') }}</b>
                </div>
                <img class="max-w-full h-auto" src="{{ asset('img/login.png') }}" alt="Logo">
            </div>
            <!--/left column-->


            <!--right column-->



            @if (!$account)
                <div class="flex items-center flex-col text-center">
                    <i class="fa fa-exclamation-triangle fa-5x text-red-600"></i>
                    <div class="text-2xl mt-4 text-red-800 font-semibold">
                        Invalid Token
                    </div>
                    <div class="text-slate-500 mt-2">
                        <p>
                            Your token is invalid. Please try again by requesting for a new token
                        </p>
                    </div>

                    <p class="flex gap-3 mt-5 justify-center">
                        <a href="{{ route('home') }}" class="btn btn-primary "><i class="fas fa-home"></i> Goto
                            Home</a>

                        <a href="#" ng-click="popend('resetPassword')" class="btn btn-secondary"><i
                                class="fas fa-undo"></i>
                            Regenerate Token</a>
                    </p>

                </div>
            @elseif (!$account->tokenIsActive())
                <div class="grid place-items-center w-full">
                    <div class="flex items-center flex-col text-center">
                        <i class="fa fa-exclamation-triangle fa-5x text-red-600"></i>
                        <div class="text-2xl mt-4 text-red-800 font-semibold">
                            Expired Token
                        </div>
                        <div class="text-slate-500 mt-2">
                            <p class="paragraph">
                                Your password request token has expired. Please try to regenerate the token
                            </p>

                            <p class="flex gap-3 justify-center">
                                <a href="{{ route('home') }}" class="btn btn-primary "><i class="fas fa-home"></i>
                                    Goto
                                    Home</a>

                                <a href="#" ng-click="popend('resetPassword')" class="btn btn-secondary"><i
                                        class="fas fa-undo"></i>
                                    Regenerate Token</a>
                            </p>
                        </div>

                    </div>
                </div>
            @else
                <div class="flex-1 min-h-full relative border-t-8 border-[#1a7f64] lg:border-none">
                    <div>
                        <h2 class="p-[38px] text-2xl  primary-text font-bold mb-2">Reset Password</h2>
                        <div class="px-[38px] pb-[38px] grid place-items-center w-full">
                            <fieldset class="w-full relative z-10 grid grid-cols-1 place-content-center">
                                <form values="{sent:'Password Reset', sending: 'Resetting Password..', error: 'Failed'}"
                                    ng-action="changePassword()">


                                    <div class="flex flex-col justify-end paragraph"
                                        ng-init="getResetTimeDifference('{{ $token }}')">

                                        <div class="text-sm">
                                            Remaining time:
                                            <span ng-bind="timeText" class="font-mono"></span>
                                        </div>
                                    </div>




                                    <div class="flex flex-col gap-8 my-8">
                                        <div class="custom-input">

                                            <i class="fa fa-lock"></i>
                                            <input type="password" class="input-bottom" placeholder="New Password"
                                                autocomplete="off" ng-model="password" />

                                        </div>

                                        <div class="custom-input">
                                            <i class="fa fa-lock"></i>
                                            <input type="password" class="input-bottom"
                                                placeholder="Verify New Password" autocomplete="off"
                                                ng-model="password_confirmation" />

                                        </div>



                                    </div>




                                    <div class="flex flex-col mt-8 gap-3">

                                        <button type="button" ng-submit class="btn btn-primary transition w-full">Reset
                                            Password</buton>

                                    </div>
                                </form>
                            </fieldset>
                        </div>
                    </div>
            @endif

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
