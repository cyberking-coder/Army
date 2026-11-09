@extends('layouts.public')
@section('title', 'Newsline')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <h1 class="font-display font-bold text-3xl mb-8">Newsline</h1>
    @if($newslines->isEmpty())
        <p class="text-gray-500">No announcements.</p>
    @else
        <div class="space-y-3">
            @foreach($newslines as $n)
                <div class="bg-white border rounded-lg p-4">
                    <div class="font-medium">{{ $n->headline }}</div>
                    @if($n->starts_at)<div class="text-xs text-gray-400 mt-1">{{ $n->starts_at->format('d M Y') }}</div>@endif
                    @if($n->link_url)<a href="{{ $n->link_url }}" class="text-sm text-brand-700 hover:underline">Read more →</a>@endif
                </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $newslines->links() }}</div>
    @endif
</div>
@endsection
