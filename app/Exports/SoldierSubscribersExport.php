<?php

namespace App\Exports;

use App\Models\SoldierSubscriber;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SoldierSubscribersExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(public ?string $status = null) {}

    public function collection()
    {
        $q = SoldierSubscriber::query()->latest();
        if ($this->status) $q->where('status', $this->status);
        return $q->get();
    }

    public function headings(): array
    {
        return ['Status', 'First Name', 'Last Name', 'Mobile', 'Email', 'Date Entered'];
    }

    public function map($r): array
    {
        return [$r->status, $r->first_name, $r->last_name, $r->mobile, $r->email, $r->created_at?->format('Y-m-d H:i')];
    }
}
