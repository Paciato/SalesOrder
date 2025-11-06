<?php

namespace App\Filament\Resources\SalesOrderResource\Pages;

use App\Filament\Resources\SalesOrderResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditSalesOrder extends EditRecord
{
    protected static string $resource = SalesOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('mark_as_paid')
                ->label('Mark as Paid')
                ->visible(fn($record) => $record->status !== 'Paid')
                ->color('success')
                ->action(function ($record) {
                    $record->update(['status' => 'Paid']);
                    $this->notify('success', 'Order marked as paid!');
                }),
        ];
    }
}
