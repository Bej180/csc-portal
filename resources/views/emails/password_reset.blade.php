    
@component('mail::message')
# Reset Your Password

Hi {{ $user->name }},
    
You are receiving this email because we received a password reset request for your account.

@component('mail::button', ['url' => $resetLink])
Reset Password
@endcomponent

If you did not request a password reset, no further action is required.

<p>This link will expire in {{ config('auth.passwords.users.expire') }} minutes.</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent