<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

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
