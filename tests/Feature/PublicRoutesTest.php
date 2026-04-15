<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicRoutesTest extends TestCase
{
    public function test_home_page(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_health_check(): void
    {
        $response = $this->getJson('/health');

        $response->assertStatus(200)
            ->assertJson(['status' => 'ok']);
    }

    public function test_locale_switch(): void
    {
        $response = $this->get('/locale/de');

        $response->assertRedirect();
        $this->assertEquals('de', session('locale'));
    }

    public function test_locale_switch_invalid_keeps_default(): void
    {
        $response = $this->get('/locale/xx');

        $response->assertRedirect();
        // Invalid locale is ignored, session locale not set to 'xx'
        $this->assertNotEquals('xx', session('locale'));
    }

    public function test_cart_page_redirects_without_session(): void
    {
        $response = $this->get('/cart');
        // Cart redirects when no subscription type is selected in session
        $response->assertRedirect();
    }
}
