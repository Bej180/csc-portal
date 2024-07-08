
<x-popend name="resetPassword">

    <form class="lg:p-[30px] relative z-10">
        <div class="center-page">
            <div>
                <div class="header1">Reset Password</div>
                <div class="paragraph"><span class="link">Sorry</span>, Let Us Help You Retrieve your account</div>


                <div class="flex flex-col gap-3">
                    <div class="text-zinc-500">
                        Type your registered <b class="link">Email Address</b> in the field below. A link for password
                        reset
                        will be sent to it.
                    </div>


                    <div class="custom-input">

                        <input type="text" ng-model="loginData.usermail" placeholder="Enter Email Address" />
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="flex flex-col gap-3 mt-5">
                        <button class="btn btn-primary" controller="ResetPassword(loginData.usermail)">Reset Password</button>
                    </div>
                </div>
            </div>

        </div>



    </form>
</x-popend>
