@extends('emails.new-base')
@section('message')
    <p>Hi {{ $user->name }},</p>

    <p>This email is to notify you that your account was recently logged in from the following IP address:</p>

    <p><strong>IP Address:</strong> {{ $ipAddress }}</p>
    
    <p><strong>User Agent:</strong> {{ $userAgent }}</p>

    <p>If you recognize this login, you can safely ignore this email.</p>

    <p>If you did not log in to your account from this IP address, we recommend that you:</p>

    <ol>
        <li>Review your recent login activity.</li>
        <li>Consider enabling two-factor authentication for your account.</li>
        <li>Change your account password if you suspect unauthorized access.</li>
    </ol>

    <p>If you have any concerns about this email or suspect your account may have been compromised, please contact us immediately.</p>

    <p>Sincerely,</p>
    <p>{{ config('app.name') }}</p>
@endsection