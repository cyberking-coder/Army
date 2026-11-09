<?php

namespace App\Filament\Resources\SoldierSubscriberResource\Pages;

use App\Exports\SoldierSubscribersExport;
use App\Filament\Resources\SoldierSubscriberResource;
use App\Models\SoldierSubscriber;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

class ListSoldierSubscribers extends ListRecords
{
    protected static string $resource = SoldierSubscriberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download_subscribed')->label('Download subscribed')
                ->icon('heroicon-o-arrow-down-tray')->color('success')
                ->action(fn () => Excel::download(new SoldierSubscribersExport('subscribed'), 'soldier-subscribed-'.now()->format('Ymd-His').'.xlsx')),
            Actions\Action::make('download_unverified')->label('Download unverified')
                ->icon('heroicon-o-arrow-down-tray')->color('warning')
                ->action(fn () => Excel::download(new SoldierSubscribersExport('unverified'), 'soldier-unverified-'.now()->format('Ymd-His').'.xlsx')),
            Actions\Action::make('download_unsubscribed')->label('Download unsubscribed')
                ->icon('heroicon-o-arrow-down-tray')->color('danger')
                ->action(fn () => Excel::download(new SoldierSubscribersExport('unsubscribed'), 'soldier-unsubscribed-'.now()->format('Ymd-His').'.xlsx')),
            Actions\CreateAction::make(),
        ];
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
