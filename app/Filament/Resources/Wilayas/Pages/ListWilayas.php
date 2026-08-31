<?php

namespace App\Filament\Resources\Wilayas\Pages;

use App\Filament\Resources\Wilayas\WilayaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWilayas extends ListRecords
{
    protected static string $resource = WilayaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nouvelle Wilaya'),
        ];
    }
}
