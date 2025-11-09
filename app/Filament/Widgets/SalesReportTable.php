<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\SalesOrder;

class SalesReportTable extends BaseWidget
{
    protected static ?string $heading = 'Sales Orders Report';

    protected function getTableQuery(): Builder|Relation|null
    {
        return SalesOrder::query()->with('customer');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('customer.name')
                ->label('Customer')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('order_date')
                ->label('Order Date')
                ->date()
                ->sortable(),

            Tables\Columns\TextColumn::make('total_amount')
                ->label('Total Amount')
                ->money('idr')
                ->sortable(),

            Tables\Columns\BadgeColumn::make('status')
                ->label('Status')
                ->colors([
                    'success' => 'Completed',
                    'warning' => 'Pending',
                    'danger' => 'Cancelled',
                ]),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'Pending' => 'Pending',
                    'Completed' => 'Completed',
                    'Cancelled' => 'Cancelled',
                ]),
        ];
    }

    /** 🧱 Tambahkan ini agar widget tampil full width */
    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }
}
