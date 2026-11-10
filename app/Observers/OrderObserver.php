<?php

namespace App\Observers;

use App\Mail\OrderConfirmationMail;
use App\Mail\OrderStatusMail;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class OrderObserver
{
    public function updated(Order $order): void
    {
        if (! $order->wasChanged('status')) {
            return;
        }

        $oldStatus = $order->getOriginal('status');

        // Successful (first time) -> send confirmation
        if ($order->status === 'successful' && $oldStatus === 'pending') {
            Mail::to($order->email)->queue(new OrderConfirmationMail($order));
            return;
        }

        // Any other status change -> send status update
        Mail::to($order->email)->queue(new OrderStatusMail($order, $oldStatus));
    }
}
