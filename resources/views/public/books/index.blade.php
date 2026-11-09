@extends('layouts.public')
@section('title', 'Books')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="font-display font-bold text-3xl mb-8">Books</h1>

    @if($books->isEmpty())
        <p class="text-gray-500">No books yet.</p>
    @else
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($books as $book)
                <a href="{{ route('books.show', $book->slug) }}" class="group">
                    <div class="aspect-[3/4] bg-gray-100 rounded-lg overflow-hidden mb-2">
                        @if($book->cover_image_path)
                            <img src="{{ \Storage::url($book->cover_image_path) }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-4xl text-gray-300">📘</div>
                        @endif
                    </div>
                    <div class="font-medium line-clamp-2 group-hover:text-brand-700">{{ $book->title }}</div>
                    @if($book->author)<div class="text-xs text-gray-500">by {{ $book->author }}</div>@endif
                    <div class="text-brand-700 font-bold mt-1">
                        ₹{{ number_format($book->effective_price) }}
                        @if($book->discount_price)
                            <span class="text-gray-400 text-sm line-through ml-1">₹{{ number_format($book->price) }}</span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">{{ $books->links() }}</div>
    @endif
</div>
@endsection
