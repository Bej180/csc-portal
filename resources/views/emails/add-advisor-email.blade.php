@component('mail::message')
    # Hi *{{ $user->name }}*,

    Your account has been created on {{ config('app.name') }}!

    And you have been assigned to Class `{{ $class }}`

    Click the link below to activate your account

    @component('mail::button', ['url' => $verificationUrl])
        Activate Account
    @endcomponent

    This link will expire in {{ config('auth.verification.expire') }} minutes.

    Try to change your default password

    Sincerely,
    {{ config('app.name') }}
@endcomponent