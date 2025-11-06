<?php

namespace App\Filament\Resources\SalesOrderItemResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalesOrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'product_name')
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $product = \App\Models\Product::find($state);
                        $set('price', $product?->price);
                    })
                    ->required(),
                Forms\Components\TextInput::make('qty')
                    ->numeric()
                    ->required()
                    ->default(1),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_id')
            ->columns([
                TextColumn::make('product.product_name')
                    ->label('Product'),
                TextColumn::make('qty')
                    ->label('Quantity'),
                TextColumn::make('price')->money('idr'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
