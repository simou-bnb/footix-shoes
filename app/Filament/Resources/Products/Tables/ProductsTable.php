<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images.path')
                    ->label('')
                    ->limit(1),
                TextColumn::make('name')
                    ->label(__('Nom'))
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label(__('Catégorie'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('base_price')
                    ->label(__('Prix'))
                    ->money('DZD')
                    ->sortable(),
                TextColumn::make('variants_count')
                    ->counts('variants')
                    ->label(__('Variantes')),
                TextColumn::make('variants_sum_stock')
                    ->sum('variants', 'stock')
                    ->label(__('Stock total')),
                IconColumn::make('is_active')
                    ->label(__('Publié'))
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label(__('Catégorie'))
                    ->relationship('category', 'name'),
                TernaryFilter::make('is_active')
                    ->label(__('Publié')),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading(__('Aucun produit pour le moment'));
    }
}
