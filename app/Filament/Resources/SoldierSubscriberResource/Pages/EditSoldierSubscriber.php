<?php

namespace App\Filament\Resources\SoldierSubscriberResource\Pages;

use App\Filament\Resources\SoldierSubscriberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSoldierSubscriber extends EditRecord
{
    protected static string $resource = SoldierSubscriberResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
