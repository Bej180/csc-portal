@extends('emails.base-email')

@section('subject')
    Unusual Activity Detected on Your Account - @yield('app_name')
@endsection

@section('content')
    <p>Hi {{ $user->name }},</p>

    <p>We recently detected unusual activity on your account. This email is to notify you and help you keep your account secure.</p>

    <p>Details of the unusual activity:</p>

    <ul>
        <li><strong>IP Address:</strong> {{ $ip_address }}</li>
        <li><strong>User Agent:</strong> {{ $user_agent }}</li>
    </ul>

    <p>If you recognize this activity, you can safely ignore this email.</p>

    <p>If you don't recognize this activity, we recommend that you:</p>

    <ul>
        <li>Change your account password immediately.</li>
        <li>Review your recent login activity.</li>
        <li>Consider enabling two-factor authentication for your account.</li>
    </ul>

    <p>If you have any concerns about this email or suspect your account may have been compromised, please contact us immediately.</p>

    <p>Sincerely,</p>
    <p>{{ config('app.name') }}</p>
@endsection
