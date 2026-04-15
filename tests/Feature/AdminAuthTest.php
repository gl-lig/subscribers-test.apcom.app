<?php

namespace Tests\Feature;

use App\Models\Admin;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    public function test_login_page_accessible(): void
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
    }

    public function test_login_with_valid_credentials(): void
    {
        $admin = Admin::factory()->create(['password' => 'secret123']);

        $response = $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'secret123',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_login_with_invalid_credentials(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'wrong',
        ]);

        $this->assertGuest('admin');
    }

    public function test_login_with_inactive_admin_blocked_by_middleware(): void
    {
        $admin = Admin::factory()->inactive()->create(['password' => 'secret123']);

        $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'secret123',
        ]);

        // Login succeeds but middleware blocks access to protected routes
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/admin/login');
    }

    public function test_logout(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->post('/admin/logout')
            ->assertRedirect();

        $this->assertGuest('admin');
    }

    public function test_dashboard_requires_auth(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/admin/login');
    }

    public function test_api_user_redirected_from_dashboard(): void
    {
        $admin = Admin::factory()->apiUser()->create();

        $response = $this->actingAs($admin, 'admin')
            ->withSession(['admin_2fa_verified' => true, 'admin_last_activity' => time()])
            ->get('/admin/dashboard');

        $response->assertRedirect(route('admin.logs.api'));
    }

    public function test_api_user_can_access_api_logs(): void
    {
        $admin = Admin::factory()->apiUser()->create();

        $response = $this->actingAs($admin, 'admin')
            ->withSession(['admin_2fa_verified' => true, 'admin_last_activity' => time()])
            ->get('/admin/logs/api');

        $response->assertStatus(200);
    }

    public function test_api_user_can_access_documentation(): void
    {
        $admin = Admin::factory()->apiUser()->create();

        $response = $this->actingAs($admin, 'admin')
            ->withSession(['admin_2fa_verified' => true, 'admin_last_activity' => time()])
            ->get('/admin/api/documentation');

        $response->assertStatus(200);
    }
}
