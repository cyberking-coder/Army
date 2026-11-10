@extends('emails.layout')

@section('content')
    <p>Hi {{ $order->full_name }},</p>

    <p>Your order status has changed from <strong>{{ ucfirst($oldStatus) }}</strong> to <strong>{{ ucfirst($order->status) }}</strong>.</p>

    <div class="box">
        <div><strong>Order number:</strong> {{ $order->order_number }}</div>
        <div><strong>Status:</strong> {{ ucfirst($order->status) }}</div>
        @if($order->consignment_number)
            <div><strong>Tracking number:</strong> {{ $order->consignment_number }}</div>
        @endif
        @if($order->notes)
            <div><strong>Note:</strong> {{ $order->notes }}</div>
        @endif
    </div>

    <a href="{{ route('order.show', $order) }}" class="btn">View order online</a>

    <p style="color: #6B7280; font-size: 13px;">— {{ \App\Models\Setting::get('site_title') }}</p>
@endsection
