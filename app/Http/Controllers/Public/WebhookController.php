<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\RazorpayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function razorpay(Request $request, RazorpayService $razorpay)
    {
        $payload   = $request->getContent();
        $signature = $request->header('X-Razorpay-Signature');

        if (! $signature || ! $razorpay->verifyWebhook($payload, $signature)) {
            return response()->json(['ok' => false], 400);
        }

        $event = json_decode($payload, true);
        $kind  = $event['event'] ?? null;

        if ($kind === 'payment.captured') {
            $paymentId = $event['payload']['payment']['entity']['id'] ?? null;
            $razorpayOrderId = $event['payload']['payment']['entity']['order_id'] ?? null;

            $order = Order::where('razorpay_order_id', $razorpayOrderId)->first();
            if ($order && $order->status !== 'successful') {
                $order->markSuccessful($paymentId);
            }
        } elseif ($kind === 'payment.failed') {
            $razorpayOrderId = $event['payload']['payment']['entity']['order_id'] ?? null;
            $order = Order::where('razorpay_order_id', $razorpayOrderId)->first();
            if ($order && $order->status === 'pending') {
                $order->markRejected('Payment failed via webhook');
            }
        } else {
            Log::info('Razorpay webhook event not handled', ['kind' => $kind]);
        }

        return response()->json(['ok' => true]);
    }
}
