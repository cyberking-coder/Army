<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Exports\OrdersExport;
use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download_excel')
                ->label('Download Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->form([
                    \Filament\Forms\Components\Select::make('status')
                        ->options(['' => 'All', 'pending' => 'Pending', 'successful' => 'Successful', 'rejected' => 'Rejected']),
                ])
                ->action(fn (array $data) =>
                    Excel::download(new OrdersExport($data['status'] ?: null), 'orders-'.now()->format('Ymd-His').'.xlsx')
                ),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all'        => Tab::make('All'),
            'pending'    => Tab::make('Pending')->badge(Order::where('status', 'pending')->count())
                              ->modifyQueryUsing(fn (Builder $q) => $q->where('status', 'pending')),
            'successful' => Tab::make('Successful')
                              ->modifyQueryUsing(fn (Builder $q) => $q->where('status', 'successful')),
            'rejected'   => Tab::make('Rejected')
                              ->modifyQueryUsing(fn (Builder $q) => $q->where('status', 'rejected')),
        ];
    }
}
