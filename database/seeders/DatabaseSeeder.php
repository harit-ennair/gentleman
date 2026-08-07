<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Test User (Customer)
        if (! User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'test@example.com',
                'role' => Role::Customer,
            ]);
        }

        // Seed Admin User
        if (! User::where('email', 'admin@gentleman.com')->exists()) {
            User::factory()->create([
                'first_name' => 'Alexander',
                'last_name' => 'Mercer',
                'email' => 'admin@gentleman.com',
                'role' => Role::Admin,
            ]);
        }

        // Seed product categories, services, and products in order
        $this->call([
            ProductCategorySeeder::class,
            ServiceSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
