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
        // ── Cire Capillaire Products ──────────────────────────────
        $hairWax = Category::where('name', 'Cire Capillaire')->firstOrFail();

        $hairWaxProducts = [
            [
                'name' => 'Argile Mate',
                'description' => 'Argile à effet mat avec tenue forte pour des coiffures texturées au rendu naturel.',
                'price' => 120.00,
                'image_path' => 'https://images.unsplash.com/photo-1617897902633-82a170b6d214?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Cire Fibrée',
                'description' => 'Cire fibrée souple qui apporte texture et mouvement avec un fini naturel.',
                'price' => 110.00,
                'image_path' => 'https://images.unsplash.com/photo-1608248597279-f99d160bfcbc?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Pommade Coiffante',
                'description' => 'Pommade classique à forte brillance et tenue moyenne pour des styles lisses et soignés.',
                'price' => 100.00,
                'image_path' => 'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Pâte Sculptante',
                'description' => 'Pâte à tenue légère pour définir et séparer les cheveux avec un fini mat.',
                'price' => 115.00,
                'image_path' => 'https://images.unsplash.com/photo-1617897903246-719242758050?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Argile Modelante',
                'description' => 'Argile modelante malléable pour recoiffer les cheveux tout au long de la journée.',
                'price' => 125.00,
                'image_path' => 'https://images.unsplash.com/photo-1616683693504-3ea7e9ad6fec?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Pommade Forte Tenue',
                'description' => 'Pommade tenue maximale pour des coiffures qui durent toute la journée avec un fini brillant.',
                'price' => 130.00,
                'image_path' => 'https://images.unsplash.com/photo-1608248597279-f99d160bfcbc?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Crème Modelante',
                'description' => 'Crème modelante polyvalente avec tenue moyenne et un fini naturel et léger.',
                'price' => 105.00,
                'image_path' => 'https://images.unsplash.com/photo-1617897902633-82a170b6d214?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Crème Coiffante',
                'description' => 'Crème coiffante multi-usages pour le coiffage quotidien apportant hydratation et contrôle.',
                'price' => 95.00,
                'image_path' => 'https://images.unsplash.com/photo-1616683693504-3ea7e9ad6fec?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Cire Sada',
                'description' => 'Cire capillaire de style traditionnel avec tenue ferme et une brillance subtile non grasse.',
                'price' => 90.00,
                'image_path' => 'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        $this->seedProducts($hairWax->id, $hairWaxProducts);

        // ── Shampoing Products ────────────────────────────────────
        $shampoo = Category::where('name', 'Shampoing')->firstOrFail();

        $shampooProducts = [
            [
                'name' => 'Shampoing Hydratation Intense',
                'description' => 'Shampoing quotidien hydratant en profondeur qui fortifie les cheveux de la racine aux pointes.',
                'price' => 85.00,
                'image_path' => 'https://images.unsplash.com/photo-1535585209827-a15fcdbc4c2d?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Shampoing Détox',
                'description' => 'Shampoing détox purifiant qui élimine les résidus de produits et les impuretés.',
                'price' => 95.00,
                'image_path' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Shampoing Silver',
                'description' => 'Shampoing aux pigments violets qui neutralise les reflets jaunes des cheveux gris, argentés ou blonds.',
                'price' => 100.00,
                'image_path' => 'https://images.unsplash.com/photo-1527799820374-dcf8d9d4a388?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Shampoing Macadamia',
                'description' => 'Shampoing infusé à l\'huile de macadamia pour une nutrition intense, de la brillance et un contrôle des frisottis.',
                'price' => 110.00,
                'image_path' => 'https://images.unsplash.com/photo-1608248597279-f99d160bfcbc?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        $this->seedProducts($shampoo->id, $shampooProducts);

        // ── Après-shampoing Products ──────────────────────────────
        $conditioner = Category::where('name', 'Après-shampoing')->firstOrFail();

        $conditionerProducts = [
            [
                'name' => 'Après-shampoing Quotidien 1000ML',
                'description' => 'Après-shampoing quotidien format professionnel pour salon, offrant une hydratation profonde et un démêlage facile.',
                'price' => 180.00,
                'image_path' => 'https://images.unsplash.com/photo-1535585209827-a15fcdbc4c2d?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Après-shampoing Quotidien 450ML',
                'description' => 'Après-shampoing quotidien qui lisse, adoucit et protège les cheveux contre les agressions.',
                'price' => 95.00,
                'image_path' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        $this->seedProducts($conditioner->id, $conditionerProducts);

        // ── Soin Capillaire Products ──────────────────────────────
        $hairTreatment = Category::where('name', 'Soin Capillaire')->firstOrFail();

        $hairTreatmentProducts = [
            [
                'name' => 'Masque Macadamia',
                'description' => 'Masque capillaire intensif à l\'huile de macadamia pour une réparation profonde et une vitalité retrouvée.',
                'price' => 150.00,
                'image_path' => 'https://images.unsplash.com/photo-1527799820374-dcf8d9d4a388?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Démêlant Macadamia',
                'description' => 'Spray démêlant infusé à la macadamia qui élimine les nœuds et apporte une brillance légère.',
                'price' => 120.00,
                'image_path' => 'https://images.unsplash.com/photo-1608248597279-f99d160bfcbc?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        $this->seedProducts($hairTreatment->id, $hairTreatmentProducts);

        // ── Entretien de la Barbe Products ────────────────────────
        $beardCare = Category::where('name', 'Entretien de la Barbe')->firstOrFail();

        $beardCareProducts = [
            [
                'name' => 'Sérum à Barbe',
                'description' => 'Sérum léger pour la barbe aux huiles d\'argan et de jojoba pour adoucir, revitaliser et faire briller.',
                'price' => 140.00,
                'image_path' => 'https://images.unsplash.com/photo-1617897903246-719242758050?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        $this->seedProducts($beardCare->id, $beardCareProducts);

        // ── Rasage Products ───────────────────────────────────────
        $shaving = Category::where('name', 'Rasage')->firstOrFail();

        $shavingProducts = [
            [
                'name' => 'Gel de Rasage de Précision 150ML',
                'description' => 'Gel de rasage transparent pour un traçage de précision des contours de la barbe.',
                'price' => 75.00,
                'image_path' => 'https://images.unsplash.com/photo-1616683693504-3ea7e9ad6fec?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Gel de Rasage de Précision 450ML',
                'description' => 'Gel de rasage de précision format professionnel pour un rasage fluide et sans irritation.',
                'price' => 160.00,
                'image_path' => 'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        $this->seedProducts($shaving->id, $shavingProducts);

        // ── Coloration Capillaire Products ────────────────────────
        $hairColor = Category::where('name', 'Coloration Capillaire')->firstOrFail();

        $hairColorProducts = [
            [
                'name' => 'Redone Flash Vert',
                'description' => 'Cire de coloration temporaire verte éclatante pour un style audacieux et expressif.',
                'price' => 85.00,
                'image_path' => 'https://images.unsplash.com/photo-1617897902633-82a170b6d214?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Redone Flash Jaune',
                'description' => 'Cire de coloration temporaire jaune vif pour un look tendance et original.',
                'price' => 85.00,
                'image_path' => 'https://images.unsplash.com/photo-1608248597279-f99d160bfcbc?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Redone Flash Violet',
                'description' => 'Cire de coloration temporaire violet intense pour un style élégant et remarquable.',
                'price' => 85.00,
                'image_path' => 'https://images.unsplash.com/photo-1616683693504-3ea7e9ad6fec?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        $this->seedProducts($hairColor->id, $hairColorProducts);

        // ── Coiffage Products ─────────────────────────────────────
        $hairStyling = Category::where('name', 'Coiffage')->firstOrFail();

        $hairStylingProducts = [
            [
                'name' => 'Laque Élégance',
                'description' => 'Laque de finition avec tenue souple qui fixe la coiffure sans cartonner.',
                'price' => 70.00,
                'image_path' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Nelly Crème Bouclage',
                'description' => 'Crème définissante pour boucles qui sublime les boucles naturelles et contrôle les frisottis.',
                'price' => 90.00,
                'image_path' => 'https://images.unsplash.com/photo-1535585209827-a15fcdbc4c2d?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        $this->seedProducts($hairStyling->id, $hairStylingProducts);

        // ── Soins du Corps Products ───────────────────────────────
        $bodyCare = Category::where('name', 'Soins du Corps')->firstOrFail();

        $bodyCareProducts = [
            [
                'name' => 'Gel Douche Déodorant 24H',
                'description' => 'Gel douche double action avec protection déodorante 24 heures et un parfum frais et propre.',
                'price' => 65.00,
                'image_path' => 'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        $this->seedProducts($bodyCare->id, $bodyCareProducts);
    }

    /**
     * Seed a list of products for a given category.
     *
     * @param  string  $categoryId  The UUID of the parent category.
     * @param  array<int, array{name: string, description: string, price: float, image_path?: string|null}>  $products
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
                    'stock_quantity' => 15,
                    'image_path' => $product['image_path'] ?? null,
                    'is_active' => true,
                ],
            );
        }
    }
}
