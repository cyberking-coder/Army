<?php

namespace App\Filament\Resources\OfficerSubscriberResource\Pages;

use App\Filament\Resources\OfficerSubscriberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOfficerSubscriber extends EditRecord
{
    protected static string $resource = OfficerSubscriberResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
