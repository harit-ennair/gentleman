<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the products table.
     *
     * Products are grouped by category and each product is linked
     * to its category via a runtime lookup. Uses updateOrCreate
     * to prevent duplicate records on repeated runs.
     */
    public function run(): void
    {
        // ── Hair Wax Products ─────────────────────────────────────
        $hairWax = Category::where('name', 'Hair Wax')->firstOrFail();

        $hairWaxProducts = [
            [
                'name' => 'Mate Clay',
                'description' => 'Matte finish clay with strong hold for textured, natural-looking hairstyles.',
                'price' => 120.00,
            ],
            [
                'name' => 'Fiber',
                'description' => 'Flexible fiber wax that adds texture and movement with a natural finish.',
                'price' => 110.00,
            ],
            [
                'name' => 'Pomade',
                'description' => 'Classic pomade with high shine and medium hold for slick, polished styles.',
                'price' => 100.00,
            ],
            [
                'name' => 'Defining Paste',
                'description' => 'Light hold paste for defining and separating hair with a matte finish.',
                'price' => 115.00,
            ],
            [
                'name' => 'Molding Clay',
                'description' => 'Pliable molding clay for reshaping hair throughout the day with a natural look.',
                'price' => 125.00,
            ],
            [
                'name' => 'Heavy Hold Pomade',
                'description' => 'Maximum hold pomade for styles that need to last all day with a glossy finish.',
                'price' => 130.00,
            ],
            [
                'name' => 'Forming Cream',
                'description' => 'Versatile forming cream with medium hold and a natural, lightweight finish.',
                'price' => 105.00,
            ],
            [
                'name' => 'Grooming Cream',
                'description' => 'All-purpose grooming cream for everyday styling with added moisture and control.',
                'price' => 95.00,
            ],
            [
                'name' => 'Sada Wax',
                'description' => 'Traditional-style hair wax with firm hold and a subtle, non-greasy shine.',
                'price' => 90.00,
            ],
        ];

        $this->seedProducts($hairWax->id, $hairWaxProducts);

        // ── Shampoo Products ──────────────────────────────────────
        $shampoo = Category::where('name', 'Shampoo')->firstOrFail();

        $shampooProducts = [
            [
                'name' => 'Daily Deep Moist Shampoo',
                'description' => 'Deeply moisturizing daily shampoo that hydrates and strengthens hair from root to tip.',
                'price' => 85.00,
            ],
            [
                'name' => 'Detox Shampoo',
                'description' => 'Purifying detox shampoo that removes product buildup and environmental impurities.',
                'price' => 95.00,
            ],
            [
                'name' => 'Daily Silver Shampoo',
                'description' => 'Purple-toned shampoo that neutralizes yellow tones in grey, silver, or blonde hair.',
                'price' => 100.00,
            ],
            [
                'name' => 'Shampo Macadamia',
                'description' => 'Macadamia oil-infused shampoo for deep nourishment, shine, and frizz control.',
                'price' => 110.00,
            ],
        ];

        $this->seedProducts($shampoo->id, $shampooProducts);

        // ── Conditioner Products ──────────────────────────────────
        $conditioner = Category::where('name', 'Conditioner')->firstOrFail();

        $conditionerProducts = [
            [
                'name' => 'Daily Conditioner 1000ML',
                'description' => 'Professional-size daily conditioner for salon use, providing deep hydration and detangling.',
                'price' => 180.00,
            ],
            [
                'name' => 'Daily Conditioner 450ML',
                'description' => 'Everyday conditioner that smooths, softens, and protects hair against damage.',
                'price' => 95.00,
            ],
        ];

        $this->seedProducts($conditioner->id, $conditionerProducts);

        // ── Hair Treatment Products ───────────────────────────────
        $hairTreatment = Category::where('name', 'Hair Treatment')->firstOrFail();

        $hairTreatmentProducts = [
            [
                'name' => 'Masque Macadamia',
                'description' => 'Intensive macadamia oil hair mask for deep repair, hydration, and restored vitality.',
                'price' => 150.00,
            ],
            [
                'name' => 'Démêleur Macadamia',
                'description' => 'Macadamia-infused detangling spray that smooths knots and adds lightweight shine.',
                'price' => 120.00,
            ],
        ];

        $this->seedProducts($hairTreatment->id, $hairTreatmentProducts);

        // ── Beard Care Products ───────────────────────────────────
        $beardCare = Category::where('name', 'Beard Care')->firstOrFail();

        $beardCareProducts = [
            [
                'name' => 'Beard Serum',
                'description' => 'Lightweight beard serum with argan and jojoba oils to soften, condition, and add shine.',
                'price' => 140.00,
            ],
        ];

        $this->seedProducts($beardCare->id, $beardCareProducts);

        // ── Shaving Products ──────────────────────────────────────
        $shaving = Category::where('name', 'Shaving')->firstOrFail();

        $shavingProducts = [
            [
                'name' => 'Precision Shave Gel 150ML',
                'description' => 'Transparent shave gel for precision grooming around beard lines and edges.',
                'price' => 75.00,
            ],
            [
                'name' => 'Precision Shave Gel 450ML',
                'description' => 'Professional-size precision shave gel for salon use, providing a smooth, irritation-free shave.',
                'price' => 160.00,
            ],
        ];

        $this->seedProducts($shaving->id, $shavingProducts);

        // ── Hair Color Products ───────────────────────────────────
        $hairColor = Category::where('name', 'Hair Color')->firstOrFail();

        $hairColorProducts = [
            [
                'name' => 'Redone Flash Green',
                'description' => 'Vibrant green temporary hair color wax for bold, expressive styling.',
                'price' => 85.00,
            ],
            [
                'name' => 'Redone Flash Yellow',
                'description' => 'Bright yellow temporary hair color wax for a standout, trendy look.',
                'price' => 85.00,
            ],
            [
                'name' => 'Redone Flash Violetto',
                'description' => 'Rich violet temporary hair color wax for a sophisticated, eye-catching style.',
                'price' => 85.00,
            ],
        ];

        $this->seedProducts($hairColor->id, $hairColorProducts);

        // ── Hair Styling Products ─────────────────────────────────
        $hairStyling = Category::where('name', 'Hair Styling')->firstOrFail();

        $hairStylingProducts = [
            [
                'name' => 'Spray Elegance',
                'description' => 'Finishing hairspray with flexible hold that locks in style without stiffness.',
                'price' => 70.00,
            ],
            [
                'name' => 'Nelly Crème Bouclage',
                'description' => 'Curl-defining cream that enhances natural curls and waves with frizz control.',
                'price' => 90.00,
            ],
        ];

        $this->seedProducts($hairStyling->id, $hairStylingProducts);

        // ── Body Care Products ────────────────────────────────────
        $bodyCare = Category::where('name', 'Body Care')->firstOrFail();

        $bodyCareProducts = [
            [
                'name' => '24 Hour Deodorant Body Wash',
                'description' => 'Dual-action body wash with 24-hour deodorant protection and a fresh, clean scent.',
                'price' => 65.00,
            ],
        ];

        $this->seedProducts($bodyCare->id, $bodyCareProducts);
    }

    /**
     * Seed a list of products for a given category.
     *
     * @param  string  $categoryId  The UUID of the parent category.
     * @param  array<int, array{name: string, description: string, price: float}>  $products
     */
    private function seedProducts(string $categoryId, array $products): void
    {
        foreach ($products as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                [
                    'category_id' => $categoryId,
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'stock_quantity' => 0,
                    'image_path' => null,
                    'is_active' => true,
                ],
            );
        }
    }
}
