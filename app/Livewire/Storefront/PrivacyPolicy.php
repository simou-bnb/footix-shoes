<?php

namespace App\Livewire\Storefront;

use Livewire\Component;

class PrivacyPolicy extends Component
{
    public function render()
    {
        return view('livewire.storefront.privacy-policy')
            ->layout('layouts.storefront', [
                'title' => 'Politique de confidentialité — Footix Shoes',
                'description' => 'Politique de confidentialité de Footix Shoes. Découvrez comment nous collectons et utilisons vos données personnelles.',
            ]);
    }
}
