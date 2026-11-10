@extends('layouts.public')
@section('title', $book->title)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <a href="{{ route('books.index') }}" class="text-sm text-brand-700 hover:underline">← Back to Books</a>

    <div class="grid md:grid-cols-2 gap-10 mt-6">
        <div class="aspect-[3/4] bg-gray-100 rounded-xl overflow-hidden">
            @if($book->cover_image_path)
                <img src="{{ \Storage::url($book->cover_image_path) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center text-7xl text-gray-300">📘</div>
            @endif
        </div>
        <div>
            <h1 class="font-display font-bold text-3xl text-brand-900 mb-2">{{ $book->title }}</h1>
            @if($book->author)<div class="text-gray-500 mb-4">by {{ $book->author }}</div>@endif

            <div class="flex items-baseline gap-3 mb-6">
                <span class="text-3xl font-bold text-brand-700">₹{{ number_format($book->effective_price) }}</span>
                @if($book->discount_price)
                    <span class="text-gray-400 line-through">₹{{ number_format($book->price) }}</span>
                    <span class="text-green-600 text-sm font-semibold">{{ round((1-$book->discount_price/$book->price)*100) }}% off</span>
                @endif
            </div>

            @if($book->description)
                <p class="text-gray-700 mb-6 whitespace-pre-line">{{ $book->description }}</p>
            @endif

            @if($book->stock > 0)
                <button class="bg-brand-700 hover:bg-brand-900 text-white font-semibold px-6 py-3 rounded-lg transition w-full md:w-auto">
                    Buy Now →
                </button>
                <p class="text-xs text-gray-400 mt-2">Checkout &amp; Razorpay flow comes online in Phase 5.</p>
            @else
                <div class="bg-gray-100 text-gray-500 px-6 py-3 rounded-lg inline-block">Out of stock</div>
            @endif
        </div>
    </div>
</div>
@endsection
