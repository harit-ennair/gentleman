<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class SimplePlatformViewsTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_public_test_pages_render(): void
    {
        foreach (['/', '/login', '/register', '/services', '/products', '/categories', '/cart'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_client_test_pages_render_for_a_logged_in_user(): void
    {
        $client = User::factory()->create();

        foreach (['/dashboard', '/profile', '/appointments', '/appointments/create', '/orders'] as $path) {
            $this->actingAs($client)->get($path)->assertOk();
        }
    }

    public function test_admin_test_pages_render_for_an_admin(): void
    {
        $admin = User::factory()->create(['role' => Role::Admin]);

        foreach (['/admin/dashboard', '/admin/users', '/admin/appointments', '/admin/orders'] as $path) {
            $this->actingAs($admin)->get($path)->assertOk();
        }
    }
}
