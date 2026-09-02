<?php

namespace Tests\Feature;

use App\Enums\AppointmentStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\Role;
use App\Models\Appointment;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ModelsAndMigrationsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test User model features (UUID, FullName accessor, and Role enum).
     */
    public function test_user_model_features(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'role' => Role::Admin,
        ]);

        $this->assertTrue(Str::isUuid($user->id));
        $this->assertEquals('John Doe', $user->fullName);
        $this->assertEquals(Role::Admin, $user->role);
    }

    /**
     * Test Category and Product relationships, and Product decimal casting.
     */
    public function test_category_and_product_relationships(): void
    {
        $category = Category::factory()->create([
            'name' => 'Hair Care',
        ]);

        $product = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 19.99,
        ]);

        $this->assertTrue(Str::isUuid($category->id));
        $this->assertTrue(Str::isUuid($product->id));
        $this->assertEquals('Hair Care', $product->category->name);
        $this->assertTrue($category->products->contains($product));
        $this->assertEquals('19.99', $product->price);
    }

    /**
     * Test Service and Appointment relationships and datetime/enum casts.
     */
    public function test_service_and_appointment_relationships(): void
    {
        $user = User::factory()->create();
        $service = Service::factory()->create([
            'price' => 50.00,
        ]);

        $appointment = Appointment::factory()->create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'status' => AppointmentStatus::Confirmed,
        ]);

        $this->assertTrue(Str::isUuid($service->id));
        $this->assertTrue(Str::isUuid($appointment->id));
        $this->assertEquals(AppointmentStatus::Confirmed, $appointment->status);
        $this->assertInstanceOf(Carbon::class, $appointment->appointment_at);
        $this->assertEquals($user->id, $appointment->user->id);
        $this->assertEquals($service->id, $appointment->service->id);
    }

    /**
     * Test Order, OrderItem, and Product relationships and casts.
     */
    public function test_order_and_order_item_relationships(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'price' => 10.50,
        ]);

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => OrderStatus::Processing,
            'payment_status' => PaymentStatus::Paid,
        ]);

        $orderItem = OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 10.50,
        ]);

        $this->assertTrue(Str::isUuid($order->id));
        $this->assertTrue(Str::isUuid($orderItem->id));
        $this->assertEquals(OrderStatus::Processing, $order->status);
        $this->assertEquals(PaymentStatus::Paid, $order->payment_status);
        $this->assertEquals($user->id, $order->user->id);
        $this->assertTrue($order->orderItems->contains($orderItem));
        $this->assertEquals($product->id, $orderItem->product->id);
    }

    /**
     * Test that deleting a product referenced in an order_item throws a QueryException due to restrictOnDelete.
     */
    public function test_restrict_delete_product_on_order_item(): void
    {
        $orderItem = OrderItem::factory()->create();
        $product = $orderItem->product;

        $this->expectException(QueryException::class);
        $product->delete();
    }

    /**
     * Test that deleting a service referenced in an appointment throws a QueryException due to restrictOnDelete.
     */
    public function test_restrict_delete_service_on_appointment(): void
    {
        $appointment = Appointment::factory()->create();
        $service = $appointment->service;

        $this->expectException(QueryException::class);
        $service->delete();
    }

    /**
     * Test Product and Service image_url accessor.
     */
    public function test_product_and_service_image_url_accessor(): void
    {
        $productWithUrl = Product::factory()->create([
            'image_path' => 'https://example.com/product.jpg',
        ]);
        $this->assertEquals('https://example.com/product.jpg', $productWithUrl->image_url);

        $productWithStorage = Product::factory()->create([
            'image_path' => 'products/sample.png',
        ]);
        $this->assertStringContainsString('storage/products/sample.png', $productWithStorage->image_url);

        $productWithNull = Product::factory()->create([
            'image_path' => null,
        ]);
        $this->assertNotEmpty($productWithNull->image_url);

        $serviceWithUrl = Service::factory()->create([
            'image_path' => 'https://example.com/service.jpg',
        ]);
        $this->assertEquals('https://example.com/service.jpg', $serviceWithUrl->image_url);

        $serviceWithNull = Service::factory()->create([
            'image_path' => null,
        ]);
        $this->assertNotEmpty($serviceWithNull->image_url);
    }
}
