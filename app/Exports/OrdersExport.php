<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(public ?string $status = null) {}

    public function collection()
    {
        $q = Order::query()->latest();
        if ($this->status) $q->where('status', $this->status);
        return $q->get();
    }

    public function headings(): array
    {
        return ['Order #', 'Name', 'Mobile', 'Email', 'Address', 'City', 'State', 'Pincode',
                'Subtotal', 'Discount', 'Final cost', 'Status', 'Consignment #', 'Payment ID', 'Date'];
    }

    public function map($o): array
    {
        return [$o->order_number, $o->full_name, $o->mobile, $o->email, $o->shipping_address,
                $o->city, $o->state, $o->pincode, $o->subtotal, $o->discount, $o->final_cost,
                $o->status, $o->consignment_number, $o->payment_id, $o->created_at?->format('Y-m-d H:i')];
    }
}
