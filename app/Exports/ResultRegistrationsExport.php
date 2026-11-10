<?php

namespace App\Exports;

use App\Models\ResultRegistration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ResultRegistrationsExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(public ?int $examId = null, public ?string $zone = null) {}

    public function collection()
    {
        $q = ResultRegistration::query()->with('exam')->latest('submitted_at');
        if ($this->examId) $q->where('exam_id', $this->examId);
        if ($this->zone) $q->where('zone', $this->zone);
        return $q->get();
    }

    public function headings(): array
    {
        return ['Name', 'Mobile', 'Email', 'Roll No', 'Exam', 'Zone', 'Submitted'];
    }

    public function map($r): array
    {
        return [$r->name, $r->mobile, $r->email, $r->roll_no, $r->exam?->name, $r->zone,
                $r->submitted_at?->format('Y-m-d H:i')];
    }
}
