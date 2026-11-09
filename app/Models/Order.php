<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'full_name', 'mobile', 'email',
        'shipping_address', 'city', 'state', 'pincode',
        'subtotal', 'discount', 'final_cost', 'status',
        'payment_id', 'razorpay_order_id', 'consignment_number', 'notes',
    ];

    protected $casts = [
        'subtotal'   => 'decimal:2',
        'discount'   => 'decimal:2',
        'final_cost' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->order_number)) {
                $order->order_number = strtoupper(Str::random(8));
            }
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function markSuccessful(?string $paymentId = null): void
    {
        $this->update(['status' => 'successful', 'payment_id' => $paymentId]);
    }

    public function markRejected(?string $reason = null): void
    {
        $this->update(['status' => 'rejected', 'notes' => $reason]);
    }
}
