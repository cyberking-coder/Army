@extends('layouts.public')
@section('title', 'Complete Payment')

@section('content')
<div class="max-w-xl mx-auto px-4 py-16 text-center">
    <h1 class="font-display font-bold text-3xl text-army-900 mb-3">Complete your payment</h1>
    <p class="text-gray-600 mb-2">Order <span class="font-mono font-semibold">{{ $order->order_number }}</span></p>
    <div class="text-4xl font-bold text-army-900 my-6">₹{{ number_format($order->final_cost) }}</div>

    @if($encRequest && $accessCode && $actionUrl)
        {{-- Live CCAvenue flow: auto-submits a form that redirects to CCAvenue's hosted page --}}
        <p class="text-gray-600 mb-6">Redirecting you to CCAvenue's secure payment page...</p>

        <form id="ccavenue-form" method="POST" action="{{ $actionUrl }}">
            <input type="hidden" name="encRequest"  value="{{ $encRequest }}">
            <input type="hidden" name="access_code" value="{{ $accessCode }}">
            <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-army-900 font-semibold px-8 py-3 rounded-lg transition">
                Continue to CCAvenue →
            </button>
        </form>

        <p class="text-xs text-gray-500 mt-4">If not redirected automatically, click the button above.</p>

        <script>
            // Auto-submit after a short delay so the user sees the amount.
            setTimeout(() => document.getElementById('ccavenue-form').submit(), 800);
        </script>
    @else
        {{-- Dev fallback — CCAvenue keys not set in .env --}}
        <div class="bg-amber-50 border border-amber-200 text-amber-900 rounded-lg p-4 text-sm mb-6 text-left">
            <strong>Dev mode:</strong> CCAvenue keys not set in <code>.env</code>. Click below to simulate a successful payment.
            Set <code>CCAVENUE_MERCHANT_ID</code>, <code>CCAVENUE_ACCESS_CODE</code>, and <code>CCAVENUE_WORKING_KEY</code> for the real flow.
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
