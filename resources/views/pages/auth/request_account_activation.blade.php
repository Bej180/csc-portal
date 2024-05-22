<x-popend name='activateAccount'>
    <form method="POST" action="{{ route('pages.auth.lost-password') }}"
        class="lg:p-[30px] relative z-10">
        <div class="center-page">
            <div>
                <div class="header1 link">Activate Account</div>
                <div class="paragraph">Let Us Help You Activate Your Account</div>


                <div class="flex flex-col gap-2">
                    <div class="text-zinc-500 mb-4">
                        Type your registered <i class="font-semibold">Email Address</i> 
                    </div>


                    <div class="custom-input">

                        <input type="text" ng-model="email" placeholder="Enter Email Address" />
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="flex flex-col gap-3 mt-5">
                        <submit ng-click="requestActivationLink(email)" id="login" class="btn btn-secondary" value="{%sent?'Resend':'Send'%} Activation Link" handler="btnController"></submit>
                    </div>
                </div>
            </div>

        </div>



    </form>
</x-popend>
