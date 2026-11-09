@extends('layouts.public')
@section('title', 'Study Material')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <h1 class="font-display font-bold text-3xl mb-8">Study Material</h1>
    @if($items->isEmpty())
        <p class="text-gray-500">No study material yet.</p>
    @else
        <div class="grid md:grid-cols-2 gap-4">
            @foreach($items as $item)
                <div class="bg-white border rounded-lg p-4 hover:border-brand-500 transition">
                    <div class="font-semibold">{{ $item->title }}</div>
                    @if($item->subject)<div class="text-xs bg-brand-50 text-brand-700 inline-block px-2 py-0.5 rounded mt-1">{{ $item->subject }}</div>@endif
                    @if($item->description)<p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ $item->description }}</p>@endif
                    <a href="{{ \Storage::url($item->file_path) }}" target="_blank"
                       class="inline-block mt-3 text-sm text-brand-700 font-medium hover:underline">
                        Download →
                    </a>
                </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $items->links() }}</div>
    @endif
</div>
@endsection
