@extends('layouts.public')
@section('title', 'Checkout')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <h1 class="font-display font-bold text-3xl text-army-900 mb-8">Checkout</h1>

    @include('public.partials.errors')

    <form method="POST" action="{{ route('checkout.place') }}" class="grid lg:grid-cols-[1fr,360px] gap-8">
        @csrf
        <div class="bg-white border border-army-100 rounded-xl p-6 space-y-4">
            <h2 class="font-semibold text-army-900 text-lg">Shipping details</h2>
            @include('public.partials.input', ['name'=>'full_name','label'=>'Full name'])
            @include('public.partials.input', ['name'=>'mobile','label'=>'Mobile number','type'=>'tel'])
            @include('public.partials.input', ['name'=>'email','label'=>'Email','type'=>'email'])
            <div>
                <label class="block text-sm font-medium mb-1">Shipping address</label>
                <textarea name="shipping_address" rows="3" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:border-army-500 focus:ring-2 focus:ring-army-100 outline-none">{{ old('shipping_address') }}</textarea>
            </div>
            <div class="grid grid-cols-3 gap-3">
                @include('public.partials.input', ['name'=>'city','label'=>'City'])
                @include('public.partials.input', ['name'=>'state','label'=>'State'])
                @include('public.partials.input', ['name'=>'pincode','label'=>'Pincode'])
            </div>
        </div>

        <aside class="bg-army-50 border border-army-100 rounded-xl p-6 h-fit sticky top-24">
            <h2 class="font-semibold text-army-900 text-lg mb-4">Order summary</h2>
            <div class="space-y-2 text-sm mb-4">
                @foreach($items as $row)
                    <div class="flex justify-between gap-3">
                        <span class="text-gray-700 line-clamp-1">{{ $row['book']->title }} × {{ $row['quantity'] }}</span>
                        <span class="shrink-0 font-medium">₹{{ number_format($row['line_total']) }}</span>
                    </div>
                @endforeach
            </div>
            <div class="border-t border-army-200 pt-3 flex items-center justify-between">
                <span class="font-semibold text-army-900">Total</span>
                <span class="font-bold text-xl text-army-900">₹{{ number_format($subtotal) }}</span>
            </div>
            <button type="submit" class="mt-5 w-full bg-gold-500 hover:bg-gold-600 text-army-900 font-semibold py-3 rounded-lg transition">
                Pay with Razorpay
            </button>
            <p class="text-xs text-gray-500 mt-3 text-center">Secure checkout via Razorpay. UPI, cards, netbanking.</p>
        </aside>
    </form>
</div>
@endsection
