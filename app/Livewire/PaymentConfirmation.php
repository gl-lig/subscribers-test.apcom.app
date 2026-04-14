<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class PaymentConfirmation extends Component
{
    public ?Order $order = null;

    public function mount(): void
    {
        $token = request()->query('order', session('pending_order_token'));

        if (! $token) {
            $this->redirect(route('home'));
            return;
        }

        $this->order = Order::where('invoice_token', $token)
            ->with(['subscriber', 'subscriptionType.translations'])
            ->first();

        if (! $this->order) {
            $this->redirect(route('home'));
            return;
        }

        // One-time access: clear the session token
        session()->forget('pending_order_token');
    }

    public function render()
    {
        $locale = app()->getLocale();
        return view('livewire.payment-confirmation', compact('locale'))
            ->layout('layouts.app');
    }
}
