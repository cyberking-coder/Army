<?php

namespace App\Filament\Resources\NewslineResource\Pages;

use App\Filament\Resources\NewslineResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageNewslines extends ManageRecords
{
    protected static string $resource = NewslineResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
