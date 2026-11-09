<?php

namespace App\Filament\Resources\ResultRegistrationResource\Pages;

use App\Filament\Resources\ResultRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResultRegistrations extends ListRecords
{
    protected static string $resource = ResultRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()->label('Add registration data')];
    }
}
