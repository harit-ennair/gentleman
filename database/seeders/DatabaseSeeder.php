<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Service;
use App\Enums\Role;
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
        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'test@example.com',
                'role' => Role::Customer,
            ]);
        }

        // Seed Admin User
        if (!User::where('email', 'admin@gentleman.com')->exists()) {
            User::factory()->create([
                'first_name' => 'Alexander',
                'last_name' => 'Mercer',
                'email' => 'admin@gentleman.com',
                'role' => Role::Admin,
            ]);
        }

        // Seed Services
        $services = [
            [
                'name' => 'Haircut',
                'description' => 'Precision haircut tailored to your head shape, including a styling consultation, wash, and hot towel finish.',
                'price' => 45.00,
                'duration' => 45,
                'image_path' => 'services/haircut.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Beard Trim',
                'description' => 'Expert shaping and conditioning of your beard, lined up with a straight razor and finished with premium oil.',
                'price' => 30.00,
                'duration' => 30,
                'image_path' => 'services/beard-trim.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Hair + Beard',
                'description' => 'Our signature package: precision haircut combined with a custom beard shape and hot towel line-up.',
                'price' => 70.00,
                'duration' => 75,
                'image_path' => 'services/hair-beard.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Royal Shave',
                'description' => 'Traditional straight razor shave with pre-shave oil, hot and cold towels, rich lather, and post-shave balm.',
                'price' => 50.00,
                'duration' => 60,
                'image_path' => 'services/royal-shave.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Hair Coloring',
                'description' => 'Grey coverage or custom hair coloring consultation and application, completed with a wash and style.',
                'price' => 65.00,
                'duration' => 60,
                'image_path' => 'services/hair-coloring.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['name' => $service['name']], $service);
        }

        // Seed Categories
        $categories = [
            ['name' => 'Styling', 'description' => 'Premium hair styling products, clays, and pomades.'],
            ['name' => 'Beard Care', 'description' => 'Oils, balms, and washes to keep your beard healthy.'],
            ['name' => 'Shave', 'description' => 'Shaving creams, soaps, and aftershaves.'],
        ];

        $seededCategories = [];
        foreach ($categories as $cat) {
            $seededCategories[$cat['name']] = Category::updateOrCreate(['name' => $cat['name']], $cat);
        }

        // Seed Products
        $products = [
            [
                'category_id' => $seededCategories['Styling']->id,
                'name' => 'Styling Pomade',
                'description' => 'Strong hold, high shine pomade perfect for classic and retro styles.',
                'price' => 28.00,
                'stock_quantity' => 50,
                'image_path' => 'products/pomade.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $seededCategories['Beard Care']->id,
                'name' => 'Beard Oil',
                'description' => 'Nourishing blend of organic oils to soften beard hair and moisturize the skin.',
                'price' => 24.00,
                'stock_quantity' => 40,
                'image_path' => 'products/beard-oil.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $seededCategories['Styling']->id,
                'name' => 'Texture Clay',
                'description' => 'Matte finish, medium hold clay to add volume and texture to modern hairstyles.',
                'price' => 28.00,
                'stock_quantity' => 35,
                'image_path' => 'products/clay.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $seededCategories['Shave']->id,
                'name' => 'Shaving Cream',
                'description' => 'Rich lathering shaving cream infused with sandalwood and cedarwood essential oils.',
                'price' => 22.00,
                'stock_quantity' => 60,
                'image_path' => 'products/shave-cream.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($products as $prod) {
            Product::updateOrCreate(['name' => $prod['name']], $prod);
        }
    }
}
