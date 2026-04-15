<?php

namespace App\Livewire;

use App\Models\SubscriptionType;
use Livewire\Component;

class PricingPage extends Component
{
    public int $selectedDuration = 24;
    public ?int $selectedTypeId = null;
    public bool $showPhoneModal = false;

    public function selectPlan(int $typeId, int $duration): void
    {
        $this->selectedTypeId = $typeId;
        session()->put('selected_type_id', $typeId);
        session()->put('selected_duration', $duration);
        $this->showPhoneModal = true;
    }

    public function closeModal(): void
    {
        $this->showPhoneModal = false;
    }

    public function render()
    {
        $types = SubscriptionType::online()->where('is_free', false)->ordered()->with('translations')->get();
        $locale = app()->getLocale();

        // Pre-calculate all prices for all durations (Alpine.js switches instantly)
        $maxDiscounts = [];
        $priceData = [];

        foreach ([12, 24, 36] as $d) {
            $max = $types->max(fn ($t) => $t->discountForDuration($d));
            $maxDiscounts[$d] = $max > 0 ? intval($max) : 0;
        }

        foreach ($types as $type) {
            $baseMonthly = (float) $type->price_chf / 12;
            $typeData = [
                'id' => $type->id,
                'is_free' => $type->is_free,
                'base_monthly' => number_format($baseMonthly, 2),
            ];
            foreach ([12, 24, 36] as $d) {
                $total = $type->priceForDuration($d);
                $monthly = $total / $d;
                $discount = $type->discountForDuration($d);
                $typeData['durations'][$d] = [
                    'monthly' => number_format($monthly, 2),
                    'total' => number_format($total, 2),
                    'discount' => $discount > 0 ? intval($discount) : 0,
                ];
            }
            $priceData[$type->id] = $typeData;
        }

        return view('livewire.pricing-page', compact('types', 'locale', 'maxDiscounts', 'priceData'))
            ->layout('layouts.app');
    }
}
