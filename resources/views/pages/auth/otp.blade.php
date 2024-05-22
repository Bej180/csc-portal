<?php 
$nav = $module = 'otp';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  @include('layouts.head')
</head>
<body ng-app="cscPortal">
  
  @include('partials.popup-alert')

  <div class="popup popup-inverse">
    <form action="/otp" method="POST" class="popup-wrapper otp-form">
      @csrf
      <div class="popup-body p-8 lg:p-11 flex flex-col gap-3">
        <div class="flex align-center gap-6 p-x-1 text-green-700">
            <img src="{{asset('svg/logo.svg')}}" alt="logo" width="48">
            <div>
                <p class="font-size-2 text-body-600 font-bold">Department of Computer Science</p>
                <p class="font-size-1 text-body-400 font-semibold">Federal University of Technolog, Owerri</p>
            </div>
        </div>
        <div class="mt-5">
          OTP has been sent to you. Please check your registered phone number +23490384**5 for the six-digit code.
          <div class="mt-6 opacity-65">
            Enter the six digit code below
          </div>
        </div>
        <div class="grid grid-flow-col gap-2 otp-inputs">
          <input type="number" ng-disabled="false" autofocus="true" name="otp1" class="otp-input" ng-model="otp1" maxLength="1" max="9" min="0" disabled/>
          <input type="number" name="otp2" ng-model="otp2" maxLength="1" class="otp-input" max="9" min="0" ng-disabled="!otp1" disabled/>
          <input type="number" name="otp3" ng-model="otp3" maxLength="1" class="otp-input" max="9" min="0" ng-disabled="!otp2" disabled/>
          <input type="number" name="otp4" ng-model="otp4" maxLength="1" class="otp-input" max="9" min="0" ng-disabled="!otp3" disabled/>
          <input type="number" name="otp5" ng-model="otp5" maxLength="1" class="otp-input" max="9" min="0" ng-disabled="!otp4" disabled/>
          <input type="number" name="otp6" ng-model="otp6" maxLength="1" class="otp-input" max="9" min="0" ng-disabled="!otp5" disabled/>
        </div>

        

        <div class="mt-5 flex flex-col">
          <button type="submit" class="btn btn-primary !px-8 !py-4" ng-disabled="!otp1 || !otp2 || !otp3 || !otp3 || !otp4 || !otp5 || !otp6" disabled controller="verifyOTP()">Verify and Proceed</button>
        </div>

        <div>Did not get the code? <a href="#" class="link">Resend</a></div>
      </div>
      
    </form>

  </div>

</body>
@include('layouts.footer');
</html>