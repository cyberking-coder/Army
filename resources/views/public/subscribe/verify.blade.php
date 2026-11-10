@extends('layouts.public')
@section('title', 'Verify OTP')

@section('content')
<div class="max-w-md mx-auto px-4 py-10">
    <h1 class="font-display font-bold text-3xl mb-2">Verify your email</h1>
    <p class="text-gray-600 mb-6">We sent a 6-digit code. Enter it below to confirm your subscription.</p>

    @include('public.partials.errors')

    <form method="POST" action="{{ route('subscribe.verify', ['type'=>$type,'id'=>$id]) }}" class="space-y-4 bg-white border rounded-xl p-6">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">6-digit OTP</label>
            <input type="text" name="otp" required maxlength="6" pattern="[0-9]{6}" autofocus
                   class="w-full border border-gray-200 rounded-lg px-4 py-3 text-center text-2xl tracking-widest font-mono focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </div>
        <button class="w-full bg-brand-700 hover:bg-brand-900 text-white font-semibold px-6 py-3 rounded-lg transition">
            Verify &amp; Subscribe
        </button>
    </form>
</div>
@endsection
