<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label(__('Catégorie'))
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->label(__('Nom'))
                    ->required(),
                TextInput::make('base_price')
                    ->label(__('Prix de base'))
                    ->required()
                    ->numeric()
                    ->suffix('DA'),
                RichEditor::make('description')
                    ->label(__('Description'))
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label(__('Publié'))
                    ->default(true)
                    ->required(),
            ]);
    }
}
