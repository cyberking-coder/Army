@extends('layouts.public')
@section('title', 'Exams')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <h1 class="font-display font-bold text-3xl mb-8">Exams</h1>
    @if($exams->isEmpty())
        <p class="text-gray-500">No active exams.</p>
    @else
        <div class="space-y-4">
            @foreach($exams as $exam)
                <div class="bg-white border rounded-lg p-5 hover:border-brand-500 transition">
                    <div class="flex items-start justify-between gap-4 flex-wrap">
                        <div>
                            <h2 class="font-semibold text-lg">{{ $exam->name }}</h2>
                            @if($exam->exam_date)<div class="text-sm text-gray-500 mt-1">Exam date: {{ $exam->exam_date->format('d M Y') }}</div>@endif
                            @if($exam->description)<p class="text-sm text-gray-600 mt-2">{{ $exam->description }}</p>@endif
                        </div>
                        <a href="{{ route('exams.register', $exam->slug) }}"
                           class="bg-brand-700 hover:bg-brand-900 text-white text-sm font-medium px-4 py-2 rounded-lg transition shrink-0">
                            Register for results
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
