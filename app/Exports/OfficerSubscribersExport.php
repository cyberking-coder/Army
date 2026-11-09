<?php

namespace App\Exports;

use App\Models\OfficerSubscriber;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OfficerSubscribersExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(public ?string $status = null) {}

    public function collection()
    {
        $q = OfficerSubscriber::query()->latest();
        if ($this->status) $q->where('status', $this->status);
        return $q->get();
    }

    public function headings(): array
    {
        return ['Status', 'Full Name', 'Mobile', 'Email', 'Date Entered'];
    }

    public function map($r): array
    {
        return [$r->status, $r->full_name, $r->mobile, $r->email, $r->created_at?->format('Y-m-d H:i')];
    }
}
