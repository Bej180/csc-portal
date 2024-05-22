@component('mail::subject', ['title' => '2-FA Verification Status Update'])
    2-FA Verification Status Update
@endcomponent

@component('mail::message')
    Dear User,

    We would like to inform you about the recent update regarding your account's 2-FA (Two-Factor Authentication) verification status.

    @if($twoFactorEnabled)
        Your account's 2-FA Verification is currently enabled.
    @else
        Your account's 2-FA Verification has been turned off.
    @endif

    @if($twoFactorEnabled)
        Please keep your second factor secure and do not share it with anyone.
    @else
        You don't need your second factor to sign in. However, please ensure your account's security by enabling 2-FA again if needed.
    @endif

    Thank you for using our services.

    Regards,
    Your Team
@endcomponent
