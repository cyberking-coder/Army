@extends('layouts.public')
@section('title', 'Subscribe as Soldier')

@section('content')
<div class="max-w-md mx-auto px-4 py-10">
    <h1 class="font-display font-bold text-3xl mb-2">Subscribe as Soldier</h1>
    <p class="text-gray-600 mb-6">Get OTP-verified updates on recruitment rallies, physical test dates and study material.</p>

    @include('public.partials.errors')

    <form method="POST" action="{{ route('subscribe.soldier') }}" class="space-y-4 bg-white border rounded-xl p-6">
        @csrf
        @include('public.partials.input', ['name'=>'first_name','label'=>'First name'])
        @include('public.partials.input', ['name'=>'last_name','label'=>'Last name'])
        @include('public.partials.input', ['name'=>'mobile','label'=>'Mobile number','type'=>'tel'])
        @include('public.partials.input', ['name'=>'email','label'=>'Email','type'=>'email'])
        <button class="w-full bg-brand-700 hover:bg-brand-900 text-white font-semibold px-6 py-3 rounded-lg transition">
            Send OTP
        </button>
    </form>
</div>
@endsection
