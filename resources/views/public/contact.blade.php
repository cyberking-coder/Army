@extends('layouts.public')
@section('title', 'Contact')

@section('content')
<div class="max-w-xl mx-auto px-4 py-10">
    <h1 class="font-display font-bold text-3xl mb-6">Contact Us</h1>
    <div class="bg-white border rounded-xl p-6 space-y-3 text-gray-700">
        <div><span class="font-semibold">Email:</span> {{ \App\Models\Setting::get('contact_email') }}</div>
        <div><span class="font-semibold">Phone:</span> {{ \App\Models\Setting::get('contact_phone') }}</div>
    </div>
    <p class="text-sm text-gray-500 mt-6">A contact form is planned for Phase 6 once email service is wired.</p>
</div>
@endsection
