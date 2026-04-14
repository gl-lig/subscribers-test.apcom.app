<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\PromoCode;
use App\Models\Subscriber;
use App\Models\SubscriptionType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $subscriber = Subscriber::updateOrCreate(
            ['bat_id' => 'BAT-TEST-0001'],
            ['phone' => '+41792094478']
        );

        $premium = SubscriptionType::whereHas('translations', function ($q) {
            $q->where('locale', 'fr')->where('name', 'Premium');
        })->first();

        if ($premium) {
            $token = Str::uuid()->toString();
            Order::updateOrCreate(
                ['order_number' => 'CMD-000001'],
                [
                    'subscriber_id' => $subscriber->id,
                    'subscription_type_id' => $premium->id,
                    'duration_months' => 12,
                    'price_catalogue' => $premium->price_chf,
                    'discount_duration_pct' => 0,
                    'discount_promo_pct' => 0,
                    'price_paid' => $premium->price_chf,
                    'prorata_deducted' => 0,
                    'status' => 'active',
                    'concluded_at' => now(),
                    'starts_at' => now()->toDateString(),
                    'expires_at' => now()->addMonths(12)->toDateString(),
                    'payment_method' => 'visa',
                    'datatrans_transaction_id' => 'TEST-TXN-001',
                    'invoice_token' => $token,
                    'invoice_url' => config('app.url') . '/invoice/' . $token,
                    'cgv_accepted_at' => now(),
                    'cgv_version' => '1.0',
                ]
            );
        }

        PromoCode::updateOrCreate(
            ['code' => 'TEST20'],
            [
                'title' => 'Code de test 20%',
                'description' => 'Code promotionnel de test offrant 20% de réduction',
                'discount_pct' => 20.00,
                'valid_from' => now(),
                'valid_until' => null,
                'usage_limit_per_user' => 1,
                'scope' => 'all',
                'is_active' => true,
            ]
        );
    }
}
