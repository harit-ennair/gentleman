<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the product categories table.
     *
     * Each category is created using updateOrCreate to prevent
     * duplicate records when the seeder is run multiple times.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Hair Wax',
                'description' => 'Premium hair waxes for styling, texturizing, and shaping. From matte clays to high-shine pomades.',
            ],
            [
                'name' => 'Shampoo',
                'description' => 'Professional-grade shampoos for deep cleansing, moisturizing, and scalp detoxification.',
            ],
            [
                'name' => 'Conditioner',
                'description' => 'Nourishing conditioners to hydrate, soften, and strengthen hair after every wash.',
            ],
            [
                'name' => 'Hair Treatment',
                'description' => 'Intensive hair treatment products including masks and detanglers for damaged or dry hair.',
            ],
            [
                'name' => 'Beard Care',
                'description' => 'Beard oils, serums, and balms to nourish, soften, and maintain a healthy beard.',
            ],
            [
                'name' => 'Shaving',
                'description' => 'Precision shaving gels, creams, and aftershave products for a smooth, irritation-free shave.',
            ],
            [
                'name' => 'Hair Color',
                'description' => 'Temporary and semi-permanent hair coloring products in a variety of vibrant shades.',
            ],
            [
                'name' => 'Hair Styling',
                'description' => 'Sprays, creams, and gels for everyday hair styling, hold, and finishing.',
            ],
            [
                'name' => 'Body Care',
                'description' => 'Body washes, deodorants, and skincare essentials for daily grooming and freshness.',
            ],
            [
                'name' => 'Hair Mask',
                'description' => 'Deep-conditioning hair masks for intensive repair, hydration, and shine restoration.',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                $category,
            );
        }
    }
}
