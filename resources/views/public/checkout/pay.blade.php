@extends('layouts.public')
@section('title', 'Complete Payment')

@section('content')
<div class="max-w-xl mx-auto px-4 py-16 text-center">
    <h1 class="font-display font-bold text-3xl text-army-900 mb-3">Complete your payment</h1>
    <p class="text-gray-600 mb-2">Order <span class="font-mono font-semibold">{{ $order->order_number }}</span></p>
    <div class="text-4xl font-bold text-army-900 my-6">₹{{ number_format($order->final_cost) }}</div>

    @if($razorpayOrder && $razorpayKey)
        {{-- Live Razorpay flow --}}
        <button id="pay-btn" class="bg-gold-500 hover:bg-gold-600 text-army-900 font-semibold px-8 py-3 rounded-lg transition">
            Pay now
        </button>

        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script>
            const options = {
                key: "{{ $razorpayKey }}",
                amount: {{ (int) round($order->final_cost * 100) }},
                currency: "INR",
                name: "{{ \App\Models\Setting::get('site_title') }}",
                description: "Order {{ $order->order_number }}",
                order_id: "{{ $razorpayOrder['id'] }}",
                prefill: {
                    name:  "{{ $order->full_name }}",
                    email: "{{ $order->email }}",
                    contact: "{{ $order->mobile }}",
                },
                theme: { color: "#4A5A30" },
                handler: function (resp) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('checkout.verify', $order) }}";
                    form.innerHTML = `
                        @csrf
                        <input name="razorpay_payment_id" value="${resp.razorpay_payment_id}">
                        <input name="razorpay_order_id"   value="${resp.razorpay_order_id}">
                        <input name="razorpay_signature"  value="${resp.razorpay_signature}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            };
            document.getElementById('pay-btn').onclick = () => (new Razorpay(options)).open();
        </script>
    @else
        {{-- Dev fallback — Razorpay keys not configured --}}
        <div class="bg-amber-50 border border-amber-200 text-amber-900 rounded-lg p-4 text-sm mb-6 text-left">
            <strong>Dev mode:</strong> Razorpay keys not set in <code>.env</code>. Click below to simulate a successful payment. Set <code>RAZORPAY_KEY</code> and <code>RAZORPAY_SECRET</code> for the real flow.
        </div>
        <form method="POST" action="{{ route('checkout.dev_paid', $order) }}">
            @csrf
            <button class="bg-gold-500 hover:bg-gold-600 text-army-900 font-semibold px-8 py-3 rounded-lg transition">
                Simulate Payment Success
            </button>
        </form>
    @endif
</div>
@endsection
