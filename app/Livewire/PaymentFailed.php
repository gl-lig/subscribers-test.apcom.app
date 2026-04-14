<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class PaymentFailed extends Component
{
    public ?string $orderNumber = null;

    public function mount(): void
    {
        $token = request()->query('order');
        if ($token) {
            $order = Order::where('invoice_token', $token)->first();
            $this->orderNumber = $order?->order_number;
        }
    }

    public function render()
    {
        return view('livewire.payment-failed')
            ->layout('layouts.app');
    }
}
