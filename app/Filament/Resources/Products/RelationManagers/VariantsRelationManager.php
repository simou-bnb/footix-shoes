<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    protected static ?string $title = 'Variantes';

    protected static ?string $modelLabel = 'variante';

    protected static ?string $pluralModelLabel = 'variantes';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('size')
                    ->label('Taille')
                    ->maxLength(255),
                TextInput::make('color')
                    ->label('Couleur')
                    ->maxLength(255),
                TextInput::make('sku')
                    ->label('Référence (SKU)')
                    ->maxLength(255),
                TextInput::make('stock')
                    ->label('Stock')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('price_override')
                    ->label('Prix spécifique')
                    ->helperText('Laisser vide pour utiliser le prix de base du produit')
                    ->numeric()
                    ->suffix('DA'),
                Toggle::make('is_active')
                    ->label('Actif')
                    ->default(true)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('size')
            ->columns([
                TextColumn::make('size')
                    ->label('Taille')
                    ->placeholder('—'),
                TextColumn::make('color')
                    ->label('Couleur')
                    ->placeholder('—'),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->placeholder('—'),
                TextInputColumn::make('stock')
                    ->label('Stock')
                    ->type('number'),
                TextColumn::make('price_override')
                    ->label('Prix spécifique')
                    ->money('DZD')
                    ->placeholder('prix de base'),
                IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Aucune variante')
            ->emptyStateDescription('Ajoute une taille/couleur pour pouvoir vendre ce produit.');
    }
}
