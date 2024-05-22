@component('mail:message')
    <p>Hi {{ $user->name }},</p>

    <p>Your account has been created on {{ config('app.name') }}!</p>

    <p>To verify your email address and activate your account, please click the link below:</p>

    @component('mail:button', ['url' => $verificationUrl])
        Verify Your Email Address
    @endcomponent

    <p>This link will expire in {{ config('auth.verification.expire') }} minutes.</p>

    <p>Sincerely,</p>
    <p>{{ config('app.name') }}</p>
@endcomponent