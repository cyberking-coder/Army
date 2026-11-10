<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;

class RazorpayService
{
    protected Api $api;

    public function __construct()
    {
        $this->api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret'),
        );
    }

    /**
     * Create a Razorpay order and attach its id to our local Order.
     * Returns the Razorpay order array.
     */
    public function createOrder(Order $order): array
    {
        $razorpayOrder = $this->api->order->create([
            'receipt'  => $order->order_number,
            'amount'   => (int) round($order->final_cost * 100), // paise
            'currency' => 'INR',
            'notes'    => [
                'order_id'  => (string) $order->id,
                'customer'  => $order->full_name,
            ],
        ]);

        $order->update(['razorpay_order_id' => $razorpayOrder->id]);

        return $razorpayOrder->toArray();
    }

    /**
     * Verify the signature Razorpay sends back after checkout.
     */
    public function verifyPayment(string $razorpayOrderId, string $razorpayPaymentId, string $razorpaySignature): bool
    {
        try {
            $this->api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $razorpayOrderId,
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature'  => $razorpaySignature,
            ]);
            return true;
        } catch (\Throwable $e) {
            Log::warning('Razorpay signature verification failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Verify a webhook signature.
     */
    public function verifyWebhook(string $payload, string $signature): bool
    {
        try {
            $this->api->utility->verifyWebhookSignature(
                $payload,
                $signature,
                config('services.razorpay.webhook_secret'),
            );
            return true;
        } catch (\Throwable $e) {
            Log::warning('Razorpay webhook verification failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
