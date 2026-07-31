<?php

namespace Tests\Feature;

use App\Enums\AppointmentStatus;
use App\Enums\Role;
use App\Models\Appointment;
use App\Models\Service;
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

    public function test_client_calendar_shows_only_the_selected_month_and_owned_appointments(): void
    {
        $client = User::factory()->create();
        $otherClient = User::factory()->create();
        $service = Service::factory()->create(['name' => 'Signature Haircut']);

        Appointment::factory()->create([
            'user_id' => $client->id,
            'service_id' => $service->id,
            'appointment_at' => '2026-09-12 14:30:00',
        ]);
        Appointment::factory()->create([
            'user_id' => $client->id,
            'service_id' => $service->id,
            'appointment_at' => '2026-10-12 14:30:00',
        ]);
        Appointment::factory()->create([
            'user_id' => $otherClient->id,
            'service_id' => $service->id,
            'appointment_at' => '2026-09-13 15:30:00',
        ]);

        $this->actingAs($client)
            ->get(route('appointments.index', ['month' => '2026-09']))
            ->assertOk()
            ->assertSee('September 2026')
            ->assertSee('14:30')
            ->assertDontSee('15:30');
    }

    public function test_admin_test_pages_render_for_an_admin(): void
    {
        $admin = User::factory()->create(['role' => Role::Admin]);

        foreach (['/admin/dashboard', '/admin/users', '/admin/appointments', '/admin/orders'] as $path) {
            $this->actingAs($admin)->get($path)->assertOk();
        }
    }

    public function test_admin_appointment_calendar_filters_the_selected_month_and_status(): void
    {
        $admin = User::factory()->create(['role' => Role::Admin]);
        $client = User::factory()->create(['first_name' => 'CalendarClient']);
        $service = Service::factory()->create(['name' => 'Executive Shave']);

        Appointment::factory()->create([
            'user_id' => $client->id,
            'service_id' => $service->id,
            'appointment_at' => '2026-09-18 11:00:00',
            'status' => AppointmentStatus::Confirmed,
        ]);
        Appointment::factory()->create([
            'user_id' => $client->id,
            'service_id' => Service::factory()->create(['name' => 'Pending Only Service'])->id,
            'appointment_at' => '2026-09-19 12:00:00',
            'status' => AppointmentStatus::Pending,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.appointments.index', ['month' => '2026-09', 'status' => 'confirmed']))
            ->assertOk()
            ->assertSee('September 2026')
            ->assertSee('CalendarClient')
            ->assertSee('11:00')
            ->assertDontSee('Pending Only Service');
    }

    public function test_admin_can_open_a_complete_daily_appointment_schedule(): void
    {
        $admin = User::factory()->create(['role' => Role::Admin]);
        $client = User::factory()->create(['first_name' => 'DailyClient']);
        $service = Service::factory()->create(['name' => 'Daily Grooming']);

        Appointment::factory()->create([
            'user_id' => $client->id,
            'service_id' => $service->id,
            'appointment_at' => '2026-09-18 09:30:00',
        ]);
        Appointment::factory()->create([
            'user_id' => $client->id,
            'service_id' => $service->id,
            'appointment_at' => '2026-09-18 17:45:00',
        ]);
        Appointment::factory()->create([
            'user_id' => $client->id,
            'service_id' => $service->id,
            'appointment_at' => '2026-09-19 13:00:00',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.appointments.index', ['month' => '2026-09', 'date' => '2026-09-18']))
            ->assertOk()
            ->assertSee('Friday, September 18, 2026')
            ->assertSee('2 appointments')
            ->assertSee('09:00')
            ->assertSee('09:30')
            ->assertSee('17:00')
            ->assertSee('17:45');
    }

    public function test_admin_can_load_a_daily_schedule_with_ajax(): void
    {
        $admin = User::factory()->create(['role' => Role::Admin]);
        $client = User::factory()->create(['first_name' => 'AjaxClient', 'phone' => '0612345678']);
        $service = Service::factory()->create(['name' => 'Ajax Haircut']);
        Appointment::factory()->create([
            'user_id' => $client->id,
            'service_id' => $service->id,
            'appointment_at' => '2026-09-18 14:30:00',
            'status' => AppointmentStatus::Confirmed,
        ]);

        $this->actingAs($admin)
            ->getJson(route('admin.appointments.day', ['date' => '2026-09-18']))
            ->assertOk()
            ->assertJsonPath('date', '2026-09-18')
            ->assertJsonPath('appointments_count', 1)
            ->assertJsonPath('appointments.0.time', '14:30')
            ->assertJsonPath('appointments.0.client', $client->full_name)
            ->assertJsonPath('appointments.0.phone', '0612345678')
            ->assertJsonPath('appointments.0.service', 'Ajax Haircut');
    }
}
