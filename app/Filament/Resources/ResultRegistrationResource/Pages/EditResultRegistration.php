<?php

namespace App\Filament\Resources\ResultRegistrationResource\Pages;

use App\Filament\Resources\ResultRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResultRegistration extends EditRecord
{
    protected static string $resource = ResultRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
