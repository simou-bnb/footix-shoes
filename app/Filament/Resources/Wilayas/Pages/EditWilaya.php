<?php

namespace App\Filament\Resources\Wilayas\Pages;

use App\Filament\Resources\Wilayas\WilayaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWilaya extends EditRecord
{
    protected static string $resource = WilayaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
