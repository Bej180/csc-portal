@extends('emails.base-email')

@section('subject')
    Your One-Time Password (OTP)
@endsection

@section('content')
    <p>Hi {{ $user->name }},</p>

    <p>Here's your one-time password (OTP) to log in to your account:</p>

    <p><strong>OTP: {{ $otp }}</strong></p>

    <p>This OTP is valid for {{ config('auth.passwords.otp.expire') }} minutes.</p>

    <p>Please do not share this OTP with anyone.</p>

    <p>Sincerely,</p>
    <p>{{ config('app.name') }}</p>
@endsection
