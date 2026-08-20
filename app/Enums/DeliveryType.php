<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DeliveryType: string implements HasLabel
{
    case Home = 'home';
    case StopDesk = 'stopdesk';

    public function getLabel(): string
    {
        return match ($this) {
            self::Home => 'À domicile',
            self::StopDesk => 'Stop Desk',
        };
    }
}
