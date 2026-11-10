<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Cart;
use App\Services\CCAvenueService;
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

    public function place(Request $request, CCAvenueService $ccavenue)
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

        // If CCAvenue isn't configured, render a dev-only "Simulate Payment" page.
        if (! $ccavenue->isConfigured()) {
            return view('public.checkout.pay', [
                'order' => $order,
                'encRequest' => null,
                'accessCode' => null,
                'actionUrl'  => null,
            ]);
        }

        $fields = [
            'merchant_id'      => $ccavenue->merchantId(),
            'order_id'         => $order->order_number,
            'currency'         => 'INR',
            'amount'           => number_format($order->final_cost, 2, '.', ''),
            'redirect_url'     => route('checkout.ccavenue.return'),
            'cancel_url'       => route('checkout.ccavenue.return'),
            'language'         => 'EN',
            'billing_name'     => $order->full_name,
            'billing_address'  => $order->shipping_address,
            'billing_city'     => $order->city,
            'billing_state'    => $order->state,
            'billing_zip'      => $order->pincode,
            'billing_country'  => 'India',
            'billing_tel'      => $order->mobile,
            'billing_email'    => $order->email,
            'delivery_name'    => $order->full_name,
            'delivery_address' => $order->shipping_address,
            'delivery_city'    => $order->city,
            'delivery_state'   => $order->state,
            'delivery_zip'     => $order->pincode,
            'delivery_country' => 'India',
            'delivery_tel'     => $order->mobile,
            'merchant_param1'  => (string) $order->id,
        ];

        return view('public.checkout.pay', [
            'order'      => $order,
            'encRequest' => $ccavenue->buildEncryptedRequest($fields),
            'accessCode' => $ccavenue->accessCode(),
            'actionUrl'  => $ccavenue->transactionUrl(),
        ]);
    }

    /**
     * CCAvenue POSTs here after payment. The field `encResp` holds the
     * encrypted response; decrypt and read `order_status`.
     */
    public function ccavenueReturn(Request $request, CCAvenueService $ccavenue)
    {
        $encResp = $request->input('encResp');
        if (! $encResp) {
            return redirect()->route('home')->withErrors(['payment' => 'Missing payment response.']);
        }

        $parsed = $ccavenue->decodeResponse($encResp);
        Log::info('CCAvenue response', $parsed);

        $order = Order::where('order_number', $parsed['order_id'] ?? null)->first();
        if (! $order) {
            return redirect()->route('home')->withErrors(['payment' => 'Order not found.']);
        }

        $status     = $parsed['order_status'] ?? 'Failure';
        $trackingId = $parsed['tracking_id'] ?? null;

        if ($status === 'Success') {
            $order->markSuccessful($trackingId);
            $this->cart->clear();
            return redirect()->route('order.show', $order)->with('success', 'Payment successful!');
        }

        $reason = $parsed['failure_message'] ?? $parsed['status_message'] ?? $status;
        $order->markRejected('CCAvenue: '.$reason);

        return redirect()->route('order.show', $order)->withErrors(['payment' => "Payment {$status}."]);
    }

    public function show_(Order $order) { return $this->orderShow($order); }

    public function orderShow(Order $order)
    {
        return view('public.order.show', compact('order'));
    }

    /**
     * Dev-only: simulate a successful payment without hitting CCAvenue.
     * Only reachable when CCAvenue env vars are not set.
     */
    public function devMarkPaid(Order $order, CCAvenueService $ccavenue)
    {
        abort_if($ccavenue->isConfigured(), 404);

        $order->markSuccessful('DEV-'.time());
        $this->cart->clear();

        return redirect()->route('order.show', $order)
            ->with('success', 'Dev mode: order marked paid without CCAvenue.');
    }
}
