<?php

namespace App\Livewire;

use App\Models\SubscriptionType;
use Livewire\Component;

class PricingPage extends Component
{
    public int $selectedDuration = 24;
    public ?int $selectedTypeId = null;
    public bool $showPhoneModal = false;

    public function selectDuration(int $months): void
    {
        $this->selectedDuration = $months;
    }

    public function selectPlan(int $typeId): void
    {
        $this->selectedTypeId = $typeId;
        session()->put('selected_type_id', $typeId);
        session()->put('selected_duration', $this->selectedDuration);
        $this->showPhoneModal = true;
    }

    public function closeModal(): void
    {
        $this->showPhoneModal = false;
    }

    public function calculatePrice(SubscriptionType $type): string
    {
        return number_format($type->priceForDuration($this->selectedDuration), 2, '.', "'");
    }

    public function render()
    {
        $types = SubscriptionType::online()->where('is_free', false)->ordered()->with('translations')->get();
        $locale = app()->getLocale();

        // Max discount per duration for badge on selector
        $maxDiscounts = [];
        foreach ([12, 24, 36] as $d) {
            $max = $types->max(fn ($t) => $t->discountForDuration($d));
            $maxDiscounts[$d] = $max > 0 ? intval($max) : 0;
        }

        return view('livewire.pricing-page', compact('types', 'locale', 'maxDiscounts'))
            ->layout('layouts.app');
    }
}
