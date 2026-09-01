<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Appointment;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestAccessRestrictionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_appointment_create_page(): void
    {
        $response = $this->get(route('appointments.create'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_view_appointments(): void
    {
        $response = $this->get(route('appointments.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_book_an_appointment(): void
    {
        $service = Service::factory()->create(['is_active' => true]);

        $response = $this->post(route('appointments.store'), [
            'service_id' => $service->id,
            'appointment_at' => now()->addDay()->setTime(10, 0)->toDateTimeString(),
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('appointments', 0);
    }

    public function test_guest_cannot_view_orders(): void
    {
        $response = $this->get(route('orders.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_place_an_order(): void
    {
        $product = Product::factory()->create([
            'stock_quantity' => 10,
            'price' => 50.00,
            'is_active' => true,
        ]);

        $response = $this->post(route('orders.store'), [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 1],
            ],
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_guest_cannot_access_profile(): void
    {
        $response = $this->get(route('profile.edit'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_book_appointment_and_place_order(): void
    {
        $user = User::factory()->create();
        $service = Service::factory()->create(['is_active' => true]);
        $product = Product::factory()->create(['stock_quantity' => 10, 'price' => 30.00, 'is_active' => true]);

        // Authenticated user books appointment
        $apptResponse = $this->actingAs($user)->post(route('appointments.store'), [
            'service_id' => $service->id,
            'appointment_at' => now()->addDay()->setTime(11, 0)->toDateTimeString(),
        ]);
        $apptResponse->assertRedirect();
        $this->assertDatabaseHas('appointments', ['user_id' => $user->id]);

        // Authenticated user places order
        $orderResponse = $this->actingAs($user)->post(route('orders.store'), [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
        ]);
        $orderResponse->assertRedirect();
        $this->assertDatabaseHas('orders', ['user_id' => $user->id]);
    }

    public function test_client_and_guest_do_not_see_administration_link_in_navbar(): void
    {
        $client = User::factory()->create(['role' => Role::Customer]);

        // Guest check
        $this->get(route('services.index'))
            ->assertOk()
            ->assertDontSee('Administration');

        // Customer check
        $this->actingAs($client)
            ->get(route('services.index'))
            ->assertOk()
            ->assertDontSee('Administration');
    }

    public function test_admin_sees_administration_link_in_navbar(): void
    {
        $admin = User::factory()->create(['role' => Role::Admin]);

        $this->actingAs($admin)
            ->get(route('services.index'))
            ->assertOk()
            ->assertSee('Administration');
    }
}
