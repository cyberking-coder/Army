<?php

namespace App\Filament\Resources\PdfResource\Pages;

use App\Filament\Resources\PdfResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePdf extends CreateRecord
{
    protected static string $resource = PdfResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!empty($data['file_path']) && \Storage::disk('public')->exists($data['file_path'])) {
            $data['file_size_kb'] = (int) (\Storage::disk('public')->size($data['file_path']) / 1024);
        }
        return $data;
    }
}
