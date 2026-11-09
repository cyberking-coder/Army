<?php

namespace App\Filament\Widgets;

use App\Models\OfficerSubscriber;
use App\Models\Order;
use App\Models\SoldierSubscriber;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Orders today', Order::whereDate('created_at', today())->count())
                ->description(Order::count().' total')
                ->color('primary'),
            Stat::make('Pending orders', Order::where('status', 'pending')->count())
                ->color('warning'),
            Stat::make('Officer subscribers', OfficerSubscriber::where('status', 'subscribed')->count())
                ->color('success'),
            Stat::make('Soldier subscribers', SoldierSubscriber::where('status', 'subscribed')->count())
                ->color('success'),
        ];
    }
}
