<?php

namespace Tests\Feature;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingValidationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Service $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->service = Service::factory()->create([
            'is_active' => true,
            'duration' => 60,
        ]);
    }

    public function test_available_slots_returns_json_with_slot_data(): void
    {
        $date = now()->addDay()->format('Y-m-d');

        $response = $this->actingAs($this->user)
            ->getJson(route('appointments.available-slots', [
                'date' => $date,
                'service_id' => $this->service->id,
            ]));

        $response->assertOk()
            ->assertJsonStructure([
                'date',
                'service',
                'duration',
                'slots' => [['time', 'available']],
            ]);

        // All slots should be available (no bookings yet)
        $availableSlots = collect($response->json('slots'))->where('available', true);
        $this->assertGreaterThan(0, $availableSlots->count());
    }

    public function test_sunday_returns_available_slots(): void
    {
        // Find the next Sunday
        $sunday = now()->next('Sunday')->format('Y-m-d');

        $response = $this->actingAs($this->user)
            ->getJson(route('appointments.available-slots', [
                'date' => $sunday,
                'service_id' => $this->service->id,
            ]));

        $response->assertOk();
        $availableSlots = collect($response->json('slots'))->where('available', true);
        $this->assertGreaterThan(0, $availableSlots->count());
    }

    public function test_booked_slot_shows_as_unavailable(): void
    {
        $dateStr = now()->addDay()->format('Y-m-d');

        // Book at 10:00 with a 60-min service
        Appointment::factory()->create([
            'user_id' => $this->user->id,
            'service_id' => $this->service->id,
            'appointment_at' => "{$dateStr} 10:00:00",
            'status' => AppointmentStatus::Pending,
        ]);

        $response = $this->actingAs($this->user)
            ->getJson(route('appointments.available-slots', [
                'date' => $dateStr,
                'service_id' => $this->service->id,
            ]));

        $slots = collect($response->json('slots'));

        // 10:00 should be unavailable (exact overlap)
        $slot1000 = $slots->firstWhere('time', '10:00');
        $this->assertNotNull($slot1000);
        $this->assertFalse($slot1000['available']);

        // 10:30 should also be unavailable (overlaps with 10:00-11:00 booking)
        $slot1030 = $slots->firstWhere('time', '10:30');
        $this->assertNotNull($slot1030);
        $this->assertFalse($slot1030['available']);

        // 09:00 with a 60-min service would end at 10:00
        $slot0900 = $slots->firstWhere('time', '09:00');
        $this->assertNotNull($slot0900);
        $this->assertTrue($slot0900['available']);

        // 11:00 should be available (starts when 10:00 booking ends)
        $slot1100 = $slots->firstWhere('time', '11:00');
        $this->assertNotNull($slot1100);
        $this->assertTrue($slot1100['available']);
    }

    public function test_store_allows_booking_on_sunday(): void
    {
        $sunday = now()->next('Sunday')->setTime(10, 0)->toDateTimeString();

        $response = $this->actingAs($this->user)->post(route('appointments.store'), [
            'service_id' => $this->service->id,
            'appointment_at' => $sunday,
            'notes' => null,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_store_rejects_booking_outside_business_hours(): void
    {
        $date = now()->addDay();

        // Too early (07:00)
        $response = $this->actingAs($this->user)->post(route('appointments.store'), [
            'service_id' => $this->service->id,
            'appointment_at' => $date->copy()->setTime(7, 0)->toDateTimeString(),
        ]);
        $response->assertSessionHasErrors('appointment_at');

        // Too late (service 60 min starting at 20:30 would end at 21:30, after 21:00)
        $response = $this->actingAs($this->user)->post(route('appointments.store'), [
            'service_id' => $this->service->id,
            'appointment_at' => $date->copy()->setTime(20, 30)->toDateTimeString(),
        ]);
        $response->assertSessionHasErrors('appointment_at');
    }

    public function test_store_rejects_overlapping_booking(): void
    {
        $dateStr = now()->addDay()->format('Y-m-d');

        // Existing booking at 10:00 (60 min → ends at 11:00)
        Appointment::factory()->create([
            'user_id' => $this->user->id,
            'service_id' => $this->service->id,
            'appointment_at' => "{$dateStr} 10:00:00",
            'status' => AppointmentStatus::Confirmed,
        ]);

        // Try to book at 10:30 (would overlap with 10:00-11:00)
        $response = $this->actingAs($this->user)->post(route('appointments.store'), [
            'service_id' => $this->service->id,
            'appointment_at' => "{$dateStr} 10:30:00",
        ]);

        $response->assertSessionHasErrors('appointment_at');
    }

    public function test_store_allows_valid_booking(): void
    {
        $dateStr = now()->addDay()->format('Y-m-d');

        $response = $this->actingAs($this->user)->post(route('appointments.store'), [
            'service_id' => $this->service->id,
            'appointment_at' => "{$dateStr} 10:00:00",
            'notes' => 'Test booking',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('appointments', [
            'user_id' => $this->user->id,
            'service_id' => $this->service->id,
            'status' => AppointmentStatus::Pending->value,
        ]);
    }

    public function test_create_page_loads_with_services(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('appointments.create'));

        $response->assertOk()
            ->assertSee($this->service->name);
    }
}
