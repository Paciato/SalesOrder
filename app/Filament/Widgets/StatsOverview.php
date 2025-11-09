<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\SalesOrder;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Sales', 'Rp ' . number_format(SalesOrder::sum('total_amount'), 0, ',', '.')),
            Stat::make('Total Orders', SalesOrder::count()),
            Stat::make('Completed Orders', SalesOrder::where('status', 'Paid')->count()),
        ];
    }
}
