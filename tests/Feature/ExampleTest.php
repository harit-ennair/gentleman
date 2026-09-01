<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_home_page_displays_top_six_most_requested_services(): void
    {
        $user = User::factory()->create();

        // Create 8 services
        $services = Service::factory()->count(8)->create([
            'is_active' => true,
        ]);

        // Create different appointment counts for services
        // Service 0 has 5 appointments, Service 1 has 4 appointments, ..., Service 7 has 0
        foreach ($services as $index => $service) {
            Appointment::factory()->count(8 - $index)->create([
                'user_id' => $user->id,
                'service_id' => $service->id,
            ]);
        }

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('services', function ($viewServices) use ($services) {
            return $viewServices->count() === 6
                && $viewServices->first()->id === $services[0]->id
                && $viewServices->last()->id === $services[5]->id;
        });
    }
}
