@extends('layouts.public')
@section('title', 'Register for '.$exam->name)

@section('content')
<div class="max-w-xl mx-auto px-4 py-10">
    <a href="{{ route('exams.index') }}" class="text-sm text-brand-700 hover:underline">← All exams</a>
    <h1 class="font-display font-bold text-3xl mt-3 mb-2">Register for Results</h1>
    <h2 class="text-brand-700 mb-6">{{ $exam->name }}</h2>

    @include('public.partials.errors')

    <form method="POST" action="{{ url('/exams/'.$exam->slug.'/register') }}" class="space-y-4 bg-white border rounded-xl p-6">
        @csrf
        @include('public.partials.input', ['name'=>'name','label'=>'Full name'])
        @include('public.partials.input', ['name'=>'mobile','label'=>'Mobile number','type'=>'tel'])
        @include('public.partials.input', ['name'=>'email','label'=>'Email','type'=>'email'])
        @include('public.partials.input', ['name'=>'roll_no','label'=>'Roll number'])
        @include('public.partials.input', ['name'=>'zone','label'=>'Zone'])

        <button class="w-full bg-brand-700 hover:bg-brand-900 text-white font-semibold px-6 py-3 rounded-lg transition">
            Submit Registration
        </button>
    </form>
</div>
@endsection
