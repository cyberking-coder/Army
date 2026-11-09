@extends('layouts.public')
@section('title', 'Reviews')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <h1 class="font-display font-bold text-3xl mb-8">Reviews</h1>
    @if($reviews->isEmpty())
        <p class="text-gray-500">No reviews yet.</p>
    @else
        <div class="grid md:grid-cols-2 gap-6">
            @foreach($reviews as $review)
                <div class="bg-white border rounded-xl p-6">
                    <div class="text-amber-500 mb-2">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5-$review->rating) }}</div>
                    <p class="text-gray-700 mb-3">"{{ $review->comment }}"</p>
                    <div class="font-semibold text-sm">— {{ $review->name }}</div>
                </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $reviews->links() }}</div>
    @endif
</div>
@endsection
