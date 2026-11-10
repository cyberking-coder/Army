<?php

namespace App\Filament\Resources\OfficerSubscriberResource\Pages;

use App\Exports\OfficerSubscribersExport;
use App\Filament\Resources\OfficerSubscriberResource;
use App\Models\OfficerSubscriber;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

class ListOfficerSubscribers extends ListRecords
{
    protected static string $resource = OfficerSubscriberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download_subscribed')->label('Download subscribed')
                ->icon('heroicon-o-arrow-down-tray')->color('success')
                ->action(fn () => Excel::download(new OfficerSubscribersExport('subscribed'), 'officer-subscribed-'.now()->format('Ymd-His').'.xlsx')),
            Actions\Action::make('download_unverified')->label('Download unverified')
                ->icon('heroicon-o-arrow-down-tray')->color('warning')
                ->action(fn () => Excel::download(new OfficerSubscribersExport('unverified'), 'officer-unverified-'.now()->format('Ymd-His').'.xlsx')),
            Actions\Action::make('download_unsubscribed')->label('Download unsubscribed')
                ->icon('heroicon-o-arrow-down-tray')->color('danger')
                ->action(fn () => Excel::download(new OfficerSubscribersExport('unsubscribed'), 'officer-unsubscribed-'.now()->format('Ymd-His').'.xlsx')),
            Actions\CreateAction::make(),
        ];
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
