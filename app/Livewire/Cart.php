<?php

namespace App\Livewire;

use App\Contracts\DatatransServiceInterface;
use App\Contracts\OrderServiceInterface;
use App\Models\Order;
use App\Models\PromoCode;
use App\Models\Subscriber;
use App\Models\SubscriptionType;
use Livewire\Component;

class Cart extends Component
{
    public ?int $typeId = null;
    public int $duration = 12;
    public string $promoCode = '';
    public bool $promoValid = false;
    public string $promoError = '';
    public float $promoDiscount = 0;
    public bool $cgvAccepted = false;
    public array $prices = [];
    public ?array $currentOrderData = null;
    public bool $isUpgrade = false;
    public bool $processing = false;

    public function mount(): void
    {
        $batId = session('bat_id');
        if (! $batId) {
            $this->redirect(route('home'));
            return;
        }

        $this->typeId = session('selected_type_id');
        $this->duration = session('selected_duration', 12);

        if (! $this->typeId) {
            $this->redirect(route('home'));
            return;
        }

        $this->checkUpgrade();
        $this->recalculate();
    }

    public function changeDuration(int $months): void
    {
        $this->duration = $months;
        session()->put('selected_duration', $months);
        $this->recalculate();
    }

    public function applyPromoCode(): void
    {
        $this->promoError = '';
        $this->promoValid = false;
        $this->promoDiscount = 0;

        if (empty($this->promoCode)) {
            return;
        }

        $promo = PromoCode::where('code', $this->promoCode)->first();
        $batId = session('bat_id');

        if (! $promo || ! $promo->isValid($batId)) {
            $this->promoError = __('Code promo invalide ou expiré');
            $this->recalculate();
            return;
        }

        $this->promoValid = true;
        $this->promoDiscount = (float) $promo->discount_pct;
        $this->recalculate();
    }

    public function removePromoCode(): void
    {
        $this->promoCode = '';
        $this->promoValid = false;
        $this->promoError = '';
        $this->promoDiscount = 0;
        $this->recalculate();
    }

    public function processPayment(): void
    {
        if (! $this->cgvAccepted) {
            $this->addError('cgv', __('Veuillez accepter les conditions générales de vente'));
            return;
        }

        $this->processing = true;

        $batId = session('bat_id');
        $phone = session('bat_phone');

        $subscriber = Subscriber::firstOrCreate(
            ['bat_id' => $batId],
            ['phone' => $phone]
        );

        $type = SubscriptionType::findOrFail($this->typeId);
        $orderService = app(OrderServiceInterface::class);

        $promoCodeValue = $this->promoValid ? $this->promoCode : null;

        if ($this->isUpgrade) {
            $order = $orderService->processUpgrade($subscriber, $type, $this->duration, $promoCodeValue);
        } else {
            $order = $orderService->createOrder($subscriber, $type, $this->duration, $promoCodeValue);
        }

        // Store metadata
        $order->metadata()->create([
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'os' => '',
            'browser' => '',
            'language' => app()->getLocale(),
            'session_id' => session()->getId(),
            'server_timestamp' => now(),
        ]);

        // Initialize Datatrans payment
        $datatransService = app(DatatransServiceInterface::class);
        $result = $datatransService->initializeTransaction(
            $order,
            route('confirmation') . '?order=' . $order->invoice_token,
            route('home'),
            route('payment.failed') . '?order=' . $order->invoice_token
        );

        if ($result['success'] && isset($result['redirectUrl'])) {
            $order->update(['datatrans_transaction_id' => $result['transactionId']]);
            session()->put('pending_order_token', $order->invoice_token);
            $this->redirect($result['redirectUrl']);
        } else {
            $this->processing = false;
            session()->flash('error', __('Le paiement n\'a pas abouti'));
        }
    }

    private function checkUpgrade(): void
    {
        $batId = session('bat_id');
        $subscriber = Subscriber::where('bat_id', $batId)->first();

        if ($subscriber) {
            $currentOrder = $subscriber->activeOrder();
            if ($currentOrder && $currentOrder->subscriptionType) {
                $this->isUpgrade = true;
                $this->currentOrderData = [
                    'type_name' => $currentOrder->subscriptionType->translation(app()->getLocale())?->name ?? '',
                    'expires_at' => $currentOrder->expires_at->format('d.m.Y'),
                    'price_paid' => $currentOrder->price_paid,
                    'prorata' => $currentOrder->calculateProrata(),
                ];
            }
        }
    }

    private function recalculate(): void
    {
        $type = SubscriptionType::find($this->typeId);
        if (! $type) {
            return;
        }

        $orderService = app(OrderServiceInterface::class);
        $currentOrder = null;

        if ($this->isUpgrade) {
            $subscriber = Subscriber::where('bat_id', session('bat_id'))->first();
            $currentOrder = $subscriber?->activeOrder();
        }

        $this->prices = $orderService->calculatePrice(
            $type,
            $this->duration,
            $this->promoValid ? $this->promoCode : null,
            $currentOrder
        );
    }

    public function render()
    {
        $type = SubscriptionType::with('translations')->find($this->typeId);
        $locale = app()->getLocale();

        return view('livewire.cart', compact('type', 'locale'))
            ->layout('layouts.app');
    }
}
