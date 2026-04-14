<?php

namespace App\Services;

use App\Contracts\OrderServiceInterface;
use App\Models\Order;
use App\Models\PromoCode;
use App\Models\Subscriber;
use App\Models\SubscriptionType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService implements OrderServiceInterface
{
    public function createOrder(Subscriber $subscriber, SubscriptionType $type, int $durationMonths, ?string $promoCode = null): Order
    {
        return DB::transaction(function () use ($subscriber, $type, $durationMonths, $promoCode) {
            $prices = $this->calculatePrice($type, $durationMonths, $promoCode);
            $token = Str::uuid()->toString();

            return Order::create([
                'order_number' => Order::generateOrderNumber(),
                'subscriber_id' => $subscriber->id,
                'subscription_type_id' => $type->id,
                'duration_months' => $durationMonths,
                'price_catalogue' => $prices['price_catalogue'],
                'discount_duration_pct' => $prices['discount_duration_pct'],
                'promo_code' => $promoCode,
                'discount_promo_pct' => $prices['discount_promo_pct'],
                'price_paid' => $prices['total'],
                'prorata_deducted' => 0,
                'status' => 'active',
                'concluded_at' => now(),
                'starts_at' => now()->toDateString(),
                'expires_at' => now()->addMonths($durationMonths)->toDateString(),
                'invoice_token' => $token,
                'invoice_url' => config('app.url') . '/invoice/' . $token,
                'cgv_accepted_at' => now(),
                'cgv_version' => '1.0',
            ]);
        });
    }

    public function processUpgrade(Subscriber $subscriber, SubscriptionType $newType, int $durationMonths, ?string $promoCode = null): Order
    {
        return DB::transaction(function () use ($subscriber, $newType, $durationMonths, $promoCode) {
            $currentOrder = $subscriber->activeOrder();
            $prices = $this->calculatePrice($newType, $durationMonths, $promoCode, $currentOrder);
            $token = Str::uuid()->toString();

            $newOrder = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'subscriber_id' => $subscriber->id,
                'subscription_type_id' => $newType->id,
                'duration_months' => $durationMonths,
                'price_catalogue' => $prices['price_catalogue'],
                'discount_duration_pct' => $prices['discount_duration_pct'],
                'promo_code' => $promoCode,
                'discount_promo_pct' => $prices['discount_promo_pct'],
                'price_paid' => $prices['total'],
                'prorata_deducted' => $prices['prorata'],
                'status' => 'active',
                'concluded_at' => now(),
                'starts_at' => now()->toDateString(),
                'expires_at' => now()->addMonths($durationMonths)->toDateString(),
                'invoice_token' => $token,
                'invoice_url' => config('app.url') . '/invoice/' . $token,
                'cgv_accepted_at' => now(),
                'cgv_version' => '1.0',
            ]);

            if ($currentOrder) {
                $currentOrder->update([
                    'status' => 'replaced',
                    'expires_at' => now()->toDateString(),
                    'replaced_by_order_id' => $newOrder->id,
                    'replacement_note' => 'Remboursement pour nouvel engagement de type supérieur',
                ]);
            }

            return $newOrder;
        });
    }

    public function calculatePrice(SubscriptionType $type, int $durationMonths, ?string $promoCode = null, ?Order $currentOrder = null): array
    {
        $priceCatalogue = (float) $type->price_chf * ($durationMonths / 12);
        $discountDurationPct = 0;
        $discountPromoPct = 0;

        // Rabais 36 mois
        if ($durationMonths === 36 && $type->discount_36_months > 0) {
            $discountDurationPct = (float) $type->discount_36_months;
        }

        $afterDurationDiscount = $priceCatalogue - ($priceCatalogue * $discountDurationPct / 100);

        // Code promo
        $afterPromoDiscount = $afterDurationDiscount;
        if ($promoCode) {
            $promo = PromoCode::where('code', $promoCode)->first();
            if ($promo && $promo->isValid()) {
                $discountPromoPct = (float) $promo->discount_pct;
                $afterPromoDiscount = $afterDurationDiscount - ($afterDurationDiscount * $discountPromoPct / 100);
            }
        }

        // Prorata
        $prorata = 0;
        if ($currentOrder && $currentOrder->isActive()) {
            $prorata = $currentOrder->calculateProrata();
        }

        $total = max(0, round($afterPromoDiscount - $prorata, 2));

        return [
            'price_catalogue' => round($priceCatalogue, 2),
            'discount_duration_pct' => $discountDurationPct,
            'discount_duration_amount' => round($priceCatalogue * $discountDurationPct / 100, 2),
            'subtotal_after_duration' => round($afterDurationDiscount, 2),
            'discount_promo_pct' => $discountPromoPct,
            'discount_promo_amount' => round($afterDurationDiscount * $discountPromoPct / 100, 2),
            'subtotal_after_promo' => round($afterPromoDiscount, 2),
            'prorata' => round($prorata, 2),
            'total' => $total,
        ];
    }
}
