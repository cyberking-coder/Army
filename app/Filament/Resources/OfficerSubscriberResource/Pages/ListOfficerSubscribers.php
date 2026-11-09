<?php

namespace App\Filament\Resources\OfficerSubscriberResource\Pages;

use App\Filament\Resources\OfficerSubscriberResource;
use App\Models\OfficerSubscriber;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListOfficerSubscribers extends ListRecords
{
    protected static string $resource = OfficerSubscriberResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }

    public function getTabs(): array
    {
        return [
            'all'          => Tab::make('All'),
            'subscribed'   => Tab::make('Subscribed')->badge(OfficerSubscriber::where('status', 'subscribed')->count())
                                ->modifyQueryUsing(fn (Builder $q) => $q->where('status', 'subscribed')),
            'unverified'   => Tab::make('Unverified')
                                ->modifyQueryUsing(fn (Builder $q) => $q->where('status', 'unverified')),
            'unsubscribed' => Tab::make('Unsubscribed')
                                ->modifyQueryUsing(fn (Builder $q) => $q->where('status', 'unsubscribed')),
        ];
    }
}
