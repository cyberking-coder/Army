@extends('layouts.public')
@section('title', 'Your Cart')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <h1 class="font-display font-bold text-3xl text-army-900 mb-8">Your Cart</h1>

    @if($items->isEmpty())
        <div class="bg-army-50 rounded-xl p-10 text-center">
            <p class="text-army-700 mb-4">Your cart is empty.</p>
            <a href="{{ route('books.index') }}" class="inline-block bg-army-700 hover:bg-army-900 text-white font-semibold px-6 py-3 rounded-lg transition">Browse books</a>
        </div>
    @else
        <div class="bg-white border border-army-100 rounded-xl overflow-hidden">
            @foreach($items as $row)
                <div class="flex items-center gap-4 p-4 border-b border-army-100 last:border-b-0">
                    <div class="w-16 h-20 bg-army-50 rounded overflow-hidden shrink-0">
                        @if($row['book']->cover_image_path)
                            <img src="{{ \Storage::url($row['book']->cover_image_path) }}" class="w-full h-full object-cover" alt="">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-xl text-army-200">📘</div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('books.show', $row['book']->slug) }}" class="font-semibold hover:text-army-700">{{ $row['book']->title }}</a>
                        <div class="text-sm text-gray-500">₹{{ number_format($row['book']->effective_price) }}</div>
                    </div>
                    <form method="POST" action="{{ route('cart.update', $row['book']) }}" class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <input type="number" name="quantity" value="{{ $row['quantity'] }}" min="0" class="w-16 border border-army-200 rounded px-2 py-1 text-center">
                        <button class="text-xs text-army-700 hover:underline">update</button>
                    </form>
                    <div class="font-semibold text-army-900 w-24 text-right">₹{{ number_format($row['line_total']) }}</div>
                    <form method="POST" action="{{ route('cart.remove', $row['book']) }}">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 hover:text-red-800 text-sm">×</button>
                    </form>
                </div>
            @endforeach

            <div class="bg-army-50 p-4 flex items-center justify-between">
                <span class="font-semibold text-army-900">Subtotal</span>
                <span class="font-bold text-xl text-army-900">₹{{ number_format($subtotal) }}</span>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('checkout.show') }}" class="bg-gold-500 hover:bg-gold-600 text-army-900 font-semibold px-8 py-3 rounded-lg transition">
                Proceed to Checkout →
            </a>
        </div>
    @endif
</div>
@endsection
