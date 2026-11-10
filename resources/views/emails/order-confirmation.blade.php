@extends('emails.layout')

@section('content')
    <p>Hi {{ $order->full_name }},</p>

    <p>Thank you for your order. We've received your payment and will dispatch your package shortly.</p>

    <div class="box">
        <div><strong>Order number:</strong> {{ $order->order_number }}</div>
        <div><strong>Date:</strong> {{ $order->created_at->format('d M Y, H:i') }}</div>
        <div><strong>Status:</strong> {{ ucfirst($order->status) }}</div>
    </div>

    <table>
        <thead>
            <tr><th>Item</th><th style="text-align:right">Qty</th><th style="text-align:right">Amount</th></tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->book->title ?? 'Book' }}</td>
                    <td style="text-align:right">{{ $item->quantity }}</td>
                    <td style="text-align:right">₹{{ number_format($item->line_total) }}</td>
                </tr>
            @endforeach
            <tr class="total">
                <td colspan="2" style="text-align:right">Total</td>
                <td style="text-align:right">₹{{ number_format($order->final_cost) }}</td>
            </tr>
        </tbody>
    </table>

    <p><strong>Shipping to:</strong><br>
        {{ $order->full_name }}<br>
        {{ $order->shipping_address }}<br>
        {{ $order->city }}, {{ $order->state }} {{ $order->pincode }}<br>
        {{ $order->mobile }}
    </p>

    <a href="{{ route('order.show', $order) }}" class="btn">View order online</a>

    <p>You'll get another email with the tracking number once your package is dispatched.</p>

    <p style="color: #6B7280; font-size: 13px;">— {{ \App\Models\Setting::get('site_title') }}</p>
@endsection
