<?php

namespace App\Filament\Resources\Wilayas;

use App\Filament\Resources\Wilayas\Pages\CreateWilaya;
use App\Filament\Resources\Wilayas\Pages\EditWilaya;
use App\Filament\Resources\Wilayas\Pages\ListWilayas;
use App\Filament\Resources\Wilayas\Schemas\WilayaForm;
use App\Filament\Resources\Wilayas\Tables\WilayasTable;
use App\Models\Wilaya;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WilayaResource extends Resource
{
    protected static ?string $model = Wilaya::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    public static function getNavigationLabel(): string
    {
        return __('Wilayas & Livraison');
    }

    public static function getModelLabel(): string
    {
        return __('Wilaya');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Wilayas');
    }

    public static function form(Schema $schema): Schema
    {
        return WilayaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WilayasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListWilayas::route('/'),
            'create' => CreateWilaya::route('/create'),
            'edit'   => EditWilaya::route('/{record}/edit'),
        ];
    }
}
