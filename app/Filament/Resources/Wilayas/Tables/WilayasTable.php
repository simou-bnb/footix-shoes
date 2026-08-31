<?php

namespace App\Filament\Resources\Wilayas\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Table;

class WilayasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label(__('Code'))
                    ->searchable(),
                TextColumn::make('name')
                    ->label(__('Wilaya'))
                    ->searchable(),
                TextInputColumn::make('home_delivery_price')
                    ->label(__('Livraison à domicile (DA)'))
                    ->type('number'),
                TextInputColumn::make('stopdesk_delivery_price')
                    ->label(__('Livraison stop desk (DA)'))
                    ->type('number')
                    ->placeholder(__('non disponible')),
                ToggleColumn::make('is_active')
                    ->label(__('Active')),
            ])
            ->defaultSort('code')
            ->paginated(false)
            ->filters([])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([]);
    }
}
