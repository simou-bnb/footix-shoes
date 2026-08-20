<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Produits commandés';

    protected static ?string $modelLabel = 'produit commandé';

    protected static ?string $pluralModelLabel = 'produits commandés';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_name')
            ->columns([
                TextColumn::make('product_name')
                    ->label('Produit'),
                TextColumn::make('variant_label')
                    ->label('Variante')
                    ->placeholder('—'),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->placeholder('—'),
                TextColumn::make('unit_price')
                    ->label('Prix unitaire')
                    ->money('DZD'),
                TextColumn::make('quantity')
                    ->label('Qté'),
                TextColumn::make('line_total')
                    ->label('Total')
                    ->money('DZD'),
            ])
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
