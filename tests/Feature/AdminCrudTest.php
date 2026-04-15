<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Order;
use App\Models\PromoCode;
use App\Models\Subscriber;
use App\Models\SubscriptionType;
use App\Models\SubscriptionTypeTranslation;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->superAdmin()->create();
    }

    private function authSession(): array
    {
        return ['admin_2fa_verified' => true, 'admin_last_activity' => time()];
    }

    // --- Dashboard ---

    public function test_dashboard_loads(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->withSession($this->authSession())
            ->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    // --- Subscribers ---

    public function test_subscribers_index(): void
    {
        Subscriber::factory()->count(3)->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->withSession($this->authSession())
            ->get('/admin/subscribers');

        $response->assertStatus(200);
    }

    public function test_subscriber_show(): void
    {
        $subscriber = Subscriber::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->withSession($this->authSession())
            ->get('/admin/subscribers/' . $subscriber->id);

        $response->assertStatus(200);
    }

    public function test_subscriber_destroy(): void
    {
        $subscriber = Subscriber::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->withSession($this->authSession())
            ->delete('/admin/subscribers/' . $subscriber->id);

        $response->assertRedirect();
        $this->assertSoftDeleted('subscribers', ['id' => $subscriber->id]);
    }

    // --- Subscription Types ---

    public function test_subscription_types_index(): void
    {
        SubscriptionType::factory()->count(3)->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->withSession($this->authSession())
            ->get('/admin/subscription-types');

        $response->assertStatus(200);
    }

    public function test_subscription_type_create(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->withSession($this->authSession())
            ->post('/admin/subscription-types', [
                'status' => 'online',
                'parcelles_count' => 10,
                'alertes_count' => 5,
                'price_chf' => 99.00,
                'translations' => [
                    'fr' => ['name' => 'Test Plan', 'description' => 'Un plan test'],
                ],
            ]);

        $response->assertRedirect(route('admin.subscription-types.index'));
        $this->assertDatabaseHas('subscription_types', ['price_chf' => 99.00]);
        $this->assertDatabaseHas('subscription_type_translations', ['name' => 'Test Plan']);
    }

    public function test_subscription_type_update(): void
    {
        $type = SubscriptionType::factory()->create(['price_chf' => 49.00]);
        SubscriptionTypeTranslation::create([
            'subscription_type_id' => $type->id,
            'locale' => 'fr',
            'name' => 'Old Name',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->withSession($this->authSession())
            ->put('/admin/subscription-types/' . $type->id, [
                'status' => 'online',
                'parcelles_count' => 20,
                'alertes_count' => 10,
                'price_chf' => 99.00,
                'translations' => [
                    'fr' => ['name' => 'New Name'],
                ],
            ]);

        $response->assertRedirect(route('admin.subscription-types.index'));
        $this->assertDatabaseHas('subscription_types', ['id' => $type->id, 'price_chf' => 99.00]);
    }

    public function test_subscription_type_set_default(): void
    {
        $type1 = SubscriptionType::factory()->default()->create();
        $type2 = SubscriptionType::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->withSession($this->authSession())
            ->post('/admin/subscription-types/' . $type2->id . '/set-default');

        $response->assertRedirect(route('admin.subscription-types.index'));
        $this->assertDatabaseHas('subscription_types', ['id' => $type2->id, 'is_default' => true]);
        $this->assertDatabaseHas('subscription_types', ['id' => $type1->id, 'is_default' => false]);
    }

    public function test_subscription_type_destroy(): void
    {
        $type = SubscriptionType::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->withSession($this->authSession())
            ->delete('/admin/subscription-types/' . $type->id);

        $response->assertRedirect();
        $this->assertSoftDeleted('subscription_types', ['id' => $type->id]);
    }

    // --- Promo Codes ---

    public function test_promo_codes_index(): void
    {
        PromoCode::factory()->count(2)->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->withSession($this->authSession())
            ->get('/admin/promo-codes');

        $response->assertStatus(200);
    }

    public function test_promo_code_create(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->withSession($this->authSession())
            ->post('/admin/promo-codes', [
                'title' => 'Summer Sale',
                'code' => 'SUMMER25',
                'discount_pct' => 25,
                'scope' => 'all',
                'is_active' => true,
                'valid_from' => now()->toDateString(),
                'valid_until' => now()->addMonth()->toDateString(),
                'usage_limit_per_user' => 1,
            ]);

        $response->assertRedirect(route('admin.promo-codes.index'));
        $this->assertDatabaseHas('promo_codes', ['code' => 'SUMMER25']);
    }

    // --- Orders ---

    public function test_orders_index(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->withSession($this->authSession())
            ->get('/admin/orders');

        $response->assertStatus(200);
    }

    public function test_order_show(): void
    {
        $order = Order::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->withSession($this->authSession())
            ->get('/admin/orders/' . $order->id);

        $response->assertStatus(200);
    }

    // --- Payments ---

    public function test_payments_index(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->withSession($this->authSession())
            ->get('/admin/payments');

        $response->assertStatus(200);
    }

    // --- Admins ---

    public function test_admins_index(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->withSession($this->authSession())
            ->get('/admin/admins');

        $response->assertStatus(200);
    }

    public function test_admin_create(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->withSession($this->authSession())
            ->post('/admin/admins', [
                'first_name' => 'New',
                'last_name' => 'Admin',
                'email' => 'new@test.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'admin',
            ]);

        $response->assertRedirect(route('admin.admins.index'));
        $this->assertDatabaseHas('admins', ['email' => 'new@test.com']);
    }

    // --- Settings ---

    public function test_settings_index(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->withSession($this->authSession())
            ->get('/admin/settings');

        $response->assertStatus(200);
    }

    // --- Logs ---

    public function test_activity_logs(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->withSession($this->authSession())
            ->get('/admin/logs/activity');

        $response->assertStatus(200);
    }

    public function test_api_logs(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->withSession($this->authSession())
            ->get('/admin/logs/api');

        $response->assertStatus(200);
    }

    // --- Languages ---

    public function test_languages_index(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->withSession($this->authSession())
            ->get('/admin/languages');

        $response->assertStatus(200);
    }
}
