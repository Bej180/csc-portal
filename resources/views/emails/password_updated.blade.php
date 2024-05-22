@component('mail::message')
# Your Password May Have Been Changed

Dear {{ $user->name }},

We noticed a recent password change for your account on {{ config('app.name') }} on {{date('Y-m-d H:i:s')}}.

**If you initiated this password change, you can disregard this message.**

However, if you did not update your password yourself, your account may be at risk. We strongly recommend taking immediate action to secure your account:

1. **Change your password immediately:** Click on the following link to reset your password: {{ url('/auth/resetpassword') }}
2. **Enable two-factor authentication (2FA):** This adds an extra layer of security by requiring a second verification code when logging in. You can enable 2FA by following these instructions: {{ url('/auth/2FA_setup') }}
3. **Review your recent activity:** Check your account activity for any suspicious login attempts. If you notice any unauthorized access, please contact us immediately at {{ config('mail.from.address') }}.

**Additional Security Tips:**

- Use strong passwords with a combination of uppercase and lowercase letters, numbers, and symbols.
- Avoid using the same password for multiple accounts.
- Consider changing your password regularly.

Sincerely,

The {{ config('app.name') }} Team
@endcomponent
