<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalesOrderItemResource\RelationManagers\SalesOrderItemsRelationManager;
use App\Filament\Resources\SalesOrderResource\Pages;
use App\Filament\Resources\SalesOrderResource\RelationManagers;
use App\Models\SalesOrder;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalesOrderResource extends Resource
{
    protected static ?string $model = SalesOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('customer_id')
                ->relationship('customer', 'customer_name')
                ->required(),

            DatePicker::make('order_date')->required(),

            TextInput::make('total_amount')
                ->label('Total Amount')
                ->disabled()
                ->dehydrated(false) // <--- supaya tidak disimpan dari form
                ->prefix('Rp '),

            Select::make('status')
                ->options([
                    'Pending' => 'Pending',
                    'Paid' => 'Paid',
                    'Cancelled' => 'Cancelled',
                ])
                ->default('Pending')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.customer_name')->label('Customer'),
                TextColumn::make('order_date')->date(),
                TextColumn::make('total_amount')
                ->money('idr')
                ->label('Total'),
                BadgeColumn::make('status'),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'Pending' => 'Pending',
                    'Paid' => 'Paid',
                    'Cancelled' => 'Cancelled',
                ]),

                Filter::make('order_date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(
                        fn(Builder $query, array $data) => $query
                            ->when($data['from'], fn($q, $date) => $q->whereDate('order_date', '>=', $date))
                            ->when($data['until'], fn($q, $date) => $q->whereDate('order_date', '<=', $date))
                    )
            ])
            ->actions([
                Action::make('markPaid')
                    ->label('Mark as Paid')
                    ->color('success')
                    ->visible(fn($record) => $record->status !== 'Paid')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['status' => 'Paid']);
                    }),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SalesOrderItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSalesOrders::route('/'),
            'create' => Pages\CreateSalesOrder::route('/create'),
            'edit' => Pages\EditSalesOrder::route('/{record}/edit'),
        ];
    }
}
