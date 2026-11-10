<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Cart;
use App\Services\RazorpayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function __construct(protected Cart $cart) {}

    public function show()
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index');
        }

        return view('public.checkout.index', [
            'items'    => $this->cart->items(),
            'subtotal' => $this->cart->subtotal(),
        ]);
    }

    public function place(Request $request, RazorpayService $razorpay)
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $data = $request->validate([
            'full_name'        => 'required|string|max:150',
            'mobile'           => 'required|string|max:20',
            'email'            => 'required|email|max:150',
            'shipping_address' => 'required|string',
            'city'             => 'required|string|max:80',
            'state'            => 'required|string|max:80',
            'pincode'          => 'required|string|max:10',
        ]);

        $items    = $this->cart->items();
        $subtotal = $this->cart->subtotal();

        $order = DB::transaction(function () use ($data, $items, $subtotal) {
            $order = Order::create([
                ...$data,
                'subtotal'   => $subtotal,
                'discount'   => 0,
                'final_cost' => $subtotal,
                'status'     => 'pending',
            ]);

            foreach ($items as $row) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'book_id'    => $row['book']->id,
                    'quantity'   => $row['quantity'],
                    'unit_price' => $row['book']->effective_price,
                    'line_total' => $row['line_total'],
                ]);
            }

            return $order;
        });

        // Create Razorpay order (skip gracefully if keys not configured in dev)
        $razorpayOrder = null;
        if (config('services.razorpay.key') && config('services.razorpay.secret')) {
            try {
                $razorpayOrder = $razorpay->createOrder($order);
            } catch (\Throwable $e) {
                Log::error('Razorpay order create failed', ['error' => $e->getMessage()]);
            }
        }

        return view('public.checkout.pay', [
            'order'         => $order,
            'razorpayOrder' => $razorpayOrder,
            'razorpayKey'   => config('services.razorpay.key'),
        ]);
    }

    public function verify(Request $request, Order $order, RazorpayService $razorpay)
    {
        $data = $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id'   => 'required|string',
            'razorpay_signature'  => 'required|string',
        ]);

        $valid = $razorpay->verifyPayment(
            $data['razorpay_order_id'],
            $data['razorpay_payment_id'],
            $data['razorpay_signature'],
        );

        if (! $valid) {
            $order->markRejected('Signature verification failed');
            return redirect()->route('order.show', $order)->withErrors(['payment' => 'Payment verification failed.']);
        }

        $order->markSuccessful($data['razorpay_payment_id']);
        $this->cart->clear();

        return redirect()->route('order.show', $order)->with('success', 'Payment successful!');
    }

    public function show(Order $order)
    {
        return view('public.order.show', compact('order'));
    }

    /**
     * Dev-only: simulate a successful payment without hitting Razorpay.
     * Enabled when RAZORPAY_KEY is empty (local development).
     */
    public function devMarkPaid(Order $order)
    {
        abort_if(config('services.razorpay.key'), 404);

        $order->markSuccessful('DEV-'.time());
        $this->cart->clear();

        return redirect()->route('order.show', $order)
            ->with('success', 'Dev mode: order marked paid without Razorpay.');
    }
}
