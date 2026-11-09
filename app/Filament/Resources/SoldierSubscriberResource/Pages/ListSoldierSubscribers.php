<?php

namespace App\Filament\Resources\SoldierSubscriberResource\Pages;

use App\Filament\Resources\SoldierSubscriberResource;
use App\Models\SoldierSubscriber;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListSoldierSubscribers extends ListRecords
{
    protected static string $resource = SoldierSubscriberResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }

    public function getTabs(): array
    {
        return [
            'all'          => Tab::make('All'),
            'subscribed'   => Tab::make('Subscribed')->badge(SoldierSubscriber::where('status', 'subscribed')->count())
                                ->modifyQueryUsing(fn (Builder $q) => $q->where('status', 'subscribed')),
            'unverified'   => Tab::make('Unverified')
                                ->modifyQueryUsing(fn (Builder $q) => $q->where('status', 'unverified')),
            'unsubscribed' => Tab::make('Unsubscribed')
                                ->modifyQueryUsing(fn (Builder $q) => $q->where('status', 'unsubscribed')),
        ];
    }
}
