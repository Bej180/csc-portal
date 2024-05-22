@component('mail::message')
# New Activation Link for Your Account


Dear {{ $student->name }},

We noticed that you haven't activated your {{ $user->role }} account on the {{ config('app.name') }} portal yet.

@if($user->role === 'student')
  To access the portal and its features, including online resources, class materials, and communication tools, please activate your account now by clicking the following link:
@else
  If you're still interested in joining our community, you can activate your account now by clicking the following link:
@endif

{{ $activationLink }}

**Important:** This link will expire in 60 minutes.

If you encounter any difficulties activating your account, please don't hesitate to contact us at {{ config('mail.from.address') }}.

We look forward to welcoming you to {{ config('app.name') }}!

Sincerely,

The {{ config('app.name') }} Administration
@endcomponent