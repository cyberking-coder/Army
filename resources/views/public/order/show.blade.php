@extends('layouts.public')
@section('title', 'Order '.$order->order_number)

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12">
    @if($order->status === 'successful')
        <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center mb-6">
            <div class="text-5xl mb-3">✅</div>
            <h1 class="font-display font-bold text-2xl text-green-900 mb-1">Thank you for your order!</h1>
            <p class="text-green-800 text-sm">We've emailed you a confirmation. Your consignment number will follow once dispatched.</p>
        </div>
    @elseif($order->status === 'pending')
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 text-center mb-6">
            <div class="text-5xl mb-3">⏳</div>
            <h1 class="font-display font-bold text-2xl text-amber-900 mb-1">Payment pending</h1>
            <p class="text-amber-800 text-sm">We're waiting for payment confirmation. Refresh this page in a minute.</p>
        </div>
    @else
        <div class="bg-red-50 border border-red-200 rounded-xl p-6 text-center mb-6">
            <div class="text-5xl mb-3">❌</div>
            <h1 class="font-display font-bold text-2xl text-red-900 mb-1">Order {{ $order->status }}</h1>
            @if($order->notes)<p class="text-red-800 text-sm">{{ $order->notes }}</p>@endif
        </div>
    @endif

    <div class="bg-white border border-army-100 rounded-xl p-6 space-y-3 text-sm">
        <div class="flex justify-between"><span class="text-gray-500">Order number</span><span class="font-mono font-semibold">{{ $order->order_number }}</span></div>
        <div class="flex justify-between"><span class="text-gray-500">Date</span><span>{{ $order->created_at->format('d M Y, H:i') }}</span></div>
        <div class="flex justify-between"><span class="text-gray-500">Name</span><span>{{ $order->full_name }}</span></div>
        <div class="flex justify-between"><span class="text-gray-500">Email</span><span>{{ $order->email }}</span></div>
        <div class="flex justify-between"><span class="text-gray-500">Mobile</span><span>{{ $order->mobile }}</span></div>
        <div class="flex justify-between"><span class="text-gray-500">Status</span>
            <span class="font-semibold
                @if($order->status==='successful') text-green-700
                @elseif($order->status==='pending') text-amber-700
                @else text-red-700 @endif">
                {{ ucfirst($order->status) }}
            </span>
        </div>
        @if($order->consignment_number)
            <div class="flex justify-between"><span class="text-gray-500">Consignment #</span><span class="font-mono">{{ $order->consignment_number }}</span></div>
        @endif

        <hr>

        @foreach($order->items as $item)
            <div class="flex justify-between gap-3">
                <span>{{ $item->book->title ?? 'Book' }} × {{ $item->quantity }}</span>
                <span>₹{{ number_format($item->line_total) }}</span>
            </div>
        @endforeach

        <hr>

        <div class="flex justify-between font-bold text-army-900">
            <span>Total paid</span>
            <span>₹{{ number_format($order->final_cost) }}</span>
        </div>
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('books.index') }}" class="text-army-700 hover:underline text-sm">← Continue browsing</a>
    </div>
</div>
@endsection
