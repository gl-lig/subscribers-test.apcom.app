<?php

namespace App\Contracts;

use App\Models\Order;
use App\Models\Subscriber;
use App\Models\SubscriptionType;

interface OrderServiceInterface
{
    public function createOrder(Subscriber $subscriber, SubscriptionType $type, int $durationMonths, ?string $promoCode = null): Order;

    public function processUpgrade(Subscriber $subscriber, SubscriptionType $newType, int $durationMonths, ?string $promoCode = null): Order;

    public function calculatePrice(SubscriptionType $type, int $durationMonths, ?string $promoCode = null, ?Order $currentOrder = null): array;
}
