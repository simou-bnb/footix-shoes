<?php

namespace App\Filament\Resources\Wilayas;

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

    protected static ?string $navigationLabel = 'Wilayas';

    protected static ?string $modelLabel = 'wilaya';

    protected static ?string $pluralModelLabel = 'wilayas';

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
        return [
            //
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWilayas::route('/'),
        ];
    }
}
