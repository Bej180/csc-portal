@component('mail::message')
    <p>Hello <b>{{ $user->name }}</b>,</p>

    <p>Your account has been created on {{ config('app.name') }}!</p>


    <p>Click the link below to activate your account</p>

    @component('mail::button', ['url' => $verificationUrl])
        Activate Account
    @endcomponent

    <p>This link will expire in {{ config('auth.verification.expire') }} minutes.</p>

    <p>Try to change your default password</b>

    <p>Sincerely,</p>
    <p>{{ config('app.name') }}</p>
@endcomponent