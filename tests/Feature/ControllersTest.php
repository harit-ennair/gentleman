<?php

namespace Tests\Feature;

use App\Enums\AppointmentStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\Role;
use App\Models\Appointment;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ControllersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest registration and login.
     */
    public function test_auth_registration_and_login(): void
    {
        // Registration
        $registerData = [
            'first_name' => 'Alice',
            'last_name' => 'Smith',
            'email' => 'alice@example.com',
            'phone' => '1234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post(route('register'), $registerData);

        $response->assertStatus(302)
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'alice@example.com',
            'role' => Role::Customer->value,
        ]);

        // Logout
        $this->post(route('logout'))
            ->assertStatus(302)
            ->assertRedirect('/');

        $this->assertGuest();

        // Login
        $loginData = [
            'email' => 'alice@example.com',
            'password' => 'password123',
        ];

        $response = $this->post(route('login'), $loginData);

        $response->assertStatus(302)
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
    }

    /**
     * Test client profile display, update, and password change.
     */
    public function test_client_profile_management(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
        ]);

        // View Profile Page
        $response = $this->actingAs($user)
            ->get(route('profile.edit'));

        $response->assertStatus(200);

        // Update Profile
        $updateData = [
            'first_name' => 'UpdatedFirst',
            'last_name' => 'UpdatedLast',
            'email' => 'updated@example.com',
            'phone' => '0987654321',
        ];

        $response = $this->actingAs($user)
            ->patch(route('profile.update'), $updateData);

        $response->assertStatus(302)
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'updated@example.com',
        ]);

        // Change Password
        $passwordData = [
            'current_password' => 'oldpassword123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ];

        $response = $this->actingAs($user)
            ->from(route('profile.edit'))
            ->put(route('password.update'), $passwordData);

        $response->assertStatus(302)
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHasNoErrors();

        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    /**
     * Test client appointments: store, index, cancel, available slots.
     */
    public function test_client_appointment_management(): void
    {
        $user = User::factory()->create();
        $service = Service::factory()->create([
            'is_active' => true,
        ]);

        // Available slots on 2026-08-05
        $response = $this->actingAs($user)
            ->getJson(route('appointments.available-slots', ['date' => '2026-08-05']));

        $response->assertStatus(200)
            ->assertJsonStructure(['date', 'available_slots']);

        // Book Appointment
        $appointmentData = [
            'service_id' => $service->id,
            'appointment_at' => '2026-08-05 10:00:00',
            'notes' => 'Some notes for appointment.',
        ];

        $response = $this->actingAs($user)
            ->postJson(route('appointments.store'), $appointmentData);

        $response->assertStatus(201)
            ->assertJsonPath('appointment.status', AppointmentStatus::Pending->value);

        $appointmentId = $response->json('appointment.id');

        // Check slots are updated (10:00 should not be available)
        $response = $this->actingAs($user)
            ->getJson(route('appointments.available-slots', ['date' => '2026-08-05']));
        $this->assertNotContains('10:00', $response->json('available_slots'));

        // Client cancel appointment
        $appointment = Appointment::find($appointmentId);
        $response = $this->actingAs($user)
            ->postJson(route('appointments.cancel', $appointment));

        $response->assertStatus(200)
            ->assertJsonPath('appointment.status', AppointmentStatus::Cancelled->value);
    }

    /**
     * Test product listing and search/filtering.
     */
    public function test_product_listing_and_filtering(): void
    {
        $category = Category::factory()->create();

        Product::factory()->create([
            'name' => 'Hair Gel Professional',
            'price' => 15.00,
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        Product::factory()->create([
            'name' => 'Shampoo Silk',
            'price' => 25.00,
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        Product::factory()->create([
            'name' => 'Inactive Spray',
            'price' => 10.00,
            'category_id' => $category->id,
            'is_active' => false,
        ]);

        // Default list (shows active only)
        $response = $this->getJson(route('products.index'));
        $response->assertStatus(200);
        $this->assertCount(2, $response->json('products'));

        // Filter by Search
        $response = $this->getJson(route('products.index', ['search' => 'Gel']));
        $this->assertCount(1, $response->json('products'));
        $this->assertEquals('Hair Gel Professional', $response->json('products.0.name'));

        // Filter by Price range
        $response = $this->getJson(route('products.index', ['min_price' => 20, 'max_price' => 30]));
        $this->assertCount(1, $response->json('products'));
        $this->assertEquals('Shampoo Silk', $response->json('products.0.name'));
    }

    /**
     * Test shopping cart session actions.
     */
    public function test_shopping_cart_actions(): void
    {
        $product = Product::factory()->create([
            'stock_quantity' => 10,
            'is_active' => true,
        ]);

        // Add to cart
        $response = $this->actingAs(User::factory()->create())
            ->postJson(route('cart.add'), [
                'product_id' => $product->id,
                'quantity' => 2,
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('cart.' . $product->id, 2);

        // View Cart
        $response = $this->getJson(route('cart.index'));
        $response->assertStatus(200)
            ->assertJsonPath('total', (float) ($product->price * 2));
        $this->assertCount(1, $response->json('items'));

        // Update Cart
        $response = $this->putJson(route('cart.update'), [
            'product_id' => $product->id,
            'quantity' => 5,
        ]);
        $response->assertStatus(200);

        // Remove from cart
        $response = $this->postJson(route('cart.remove'), [
            'product_id' => $product->id,
        ]);
        $response->assertStatus(200);
        $this->assertEmpty($response->json('cart'));
    }

    /**
     * Test client order store, cancellation, and invoice.
     */
    public function test_client_order_creation_and_cancellation(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'stock_quantity' => 5,
            'price' => 10.00,
            'is_active' => true,
        ]);

        // Store Order
        $orderData = [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
            'notes' => 'Deliver in afternoon.',
        ];

        $response = $this->actingAs($user)
            ->postJson(route('orders.store'), $orderData);

        $response->assertStatus(201)
            ->assertJsonPath('order.total', '20.00')
            ->assertJsonPath('order.status', OrderStatus::Pending->value);

        $orderId = $response->json('order.id');
        $this->assertEquals(3, $product->fresh()->stock_quantity);

        // View invoice HTML page
        $response = $this->actingAs($user)
            ->get(route('orders.invoice', $orderId));
        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'text/html; charset=UTF-8');

        // Cancel Order
        $order = Order::find($orderId);
        $response = $this->actingAs($user)
            ->postJson(route('orders.cancel', $order));

        $response->assertStatus(200)
            ->assertJsonPath('order.status', OrderStatus::Cancelled->value);

        // Stock should be restored
        $this->assertEquals(5, $product->fresh()->stock_quantity);
    }

    /**
     * Test admin dashboard statistics.
     */
    public function test_admin_dashboard_metrics(): void
    {
        $admin = User::factory()->create(['role' => Role::Admin]);
        $customer = User::factory()->create(['role' => Role::Customer]);

        Appointment::factory()->create(['user_id' => $customer->id, 'status' => AppointmentStatus::Pending]);
        Order::factory()->create(['user_id' => $customer->id, 'total' => 100.00, 'payment_status' => PaymentStatus::Paid]);

        $response = $this->actingAs($admin)
            ->getJson(route('admin.dashboard'));

        $response->assertStatus(200)
            ->assertJsonPath('clients_count', 1)
            ->assertJsonPath('appointments_count', 1)
            ->assertJsonPath('orders_count', 1)
            ->assertJsonPath('revenues', 100);
    }

    /**
     * Test admin user management and toggle status (protecting self-deactivation).
     */
    public function test_admin_user_management(): void
    {
        $admin = User::factory()->create(['role' => Role::Admin]);
        $customer = User::factory()->create(['role' => Role::Customer, 'is_active' => true]);

        // Toggle customer status
        $response = $this->actingAs($admin)
            ->postJson(route('admin.users.toggle-status', $customer));

        $response->assertStatus(200)
            ->assertJsonPath('user.is_active', false);

        $this->assertFalse($customer->fresh()->is_active);

        // Try toggling own status (should be rejected)
        $response = $this->actingAs($admin)
            ->postJson(route('admin.users.toggle-status', $admin));

        $response->assertStatus(422)
            ->assertJsonPath('message', 'You cannot deactivate your own account.');

        $this->assertTrue($admin->fresh()->is_active);
    }

    /**
     * Test admin appointment updates (confirm/complete).
     */
    public function test_admin_appointment_actions(): void
    {
        $admin = User::factory()->create(['role' => Role::Admin]);
        $appointment = Appointment::factory()->create(['status' => AppointmentStatus::Pending]);

        // Confirm
        $response = $this->actingAs($admin)
            ->postJson(route('admin.appointments.confirm', $appointment));
        $response->assertStatus(200)
            ->assertJsonPath('appointment.status', AppointmentStatus::Confirmed->value);

        // Complete
        $response = $this->actingAs($admin)
            ->postJson(route('admin.appointments.complete', $appointment));
        $response->assertStatus(200)
            ->assertJsonPath('appointment.status', AppointmentStatus::Completed->value);
    }

    /**
     * Test admin order status update.
     */
    public function test_admin_order_actions(): void
    {
        $admin = User::factory()->create(['role' => Role::Admin]);
        $order = Order::factory()->create(['status' => OrderStatus::Pending, 'payment_status' => PaymentStatus::Pending]);

        // Update Status
        $response = $this->actingAs($admin)
            ->putJson(route('admin.orders.status', $order), [
                'status' => OrderStatus::Processing->value,
                'payment_status' => PaymentStatus::Paid->value,
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('order.status', OrderStatus::Processing->value)
            ->assertJsonPath('order.payment_status', PaymentStatus::Paid->value);
    }
}
