<x-template>

    <div class="popup" ng-controller="AuthController" ng-init="otp=[]">

        <form method="POST" class="popup-wrapper otp-form max-w-[500px] !p-[15px]">
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

                  
                    OTP has been sent to you. Please check your registered phone number +23490384**5 for the six-digit
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
                    <button controller="verifyOTP()" type="submit" class="btn btn-primary btn-adaptive"
                        ng-disabled="!otp || otp.length !== 6">Verify and
                        Proceed</button>
                </div>

                <div>Did not get the code? <a href="#" class="link">Resend</a></div>
            </div>

        </form>

    </div>

</x-template>
