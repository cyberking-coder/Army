@extends('layouts.public')
@section('title', 'Unsubscribed')

@section('content')
<div class="max-w-xl mx-auto px-4 py-16 text-center">
    <div class="text-5xl mb-4">👋</div>
    <h1 class="font-display font-bold text-2xl text-army-900 mb-2">You've been unsubscribed</h1>
    <p class="text-gray-600 mb-6">We won't send any more emails to <strong>{{ $email }}</strong>.</p>
    <a href="{{ route('home') }}" class="inline-block bg-army-700 hover:bg-army-900 text-white font-semibold px-6 py-3 rounded-lg transition">
        Back to home
    </a>
</div>
@endsection
