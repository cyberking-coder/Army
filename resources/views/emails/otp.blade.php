@extends('emails.layout')

@section('content')
    <p>Hi {{ $recipientName }},</p>

    <p>Use the code below to verify your subscription. It expires in <strong>15 minutes</strong>.</p>

    <div class="otp">{{ $otp }}</div>

    <p>If you didn't request this, you can safely ignore this email — no changes will be made to your account.</p>

    <p style="color: #6B7280; font-size: 13px;">— {{ \App\Models\Setting::get('site_title') }}</p>
@endsection
