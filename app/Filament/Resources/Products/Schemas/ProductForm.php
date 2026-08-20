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
                    ->label('Catégorie')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->label('Nom')
                    ->required(),
                TextInput::make('base_price')
                    ->label('Prix de base')
                    ->required()
                    ->numeric()
                    ->suffix('DA'),
                RichEditor::make('description')
                    ->label('Description')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label('Publié')
                    ->default(true)
                    ->required(),
            ]);
    }
}
