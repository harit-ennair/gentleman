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
                'name' => 'Cire Capillaire',
                'description' => 'Cires capillaires haut de gamme pour coiffer, texturer et sculpter. De l\'argile mate aux pommades brillantes.',
            ],
            [
                'name' => 'Shampoing',
                'description' => 'Shampoings de qualité professionnelle pour un nettoyage en profondeur, une hydratation et une détoxification du cuir chevelu.',
            ],
            [
                'name' => 'Après-shampoing',
                'description' => 'Après-shampoings nourrissants pour hydrater, adoucir et renforcer les cheveux après chaque lavage.',
            ],
            [
                'name' => 'Soin Capillaire',
                'description' => 'Produits de soins capillaires intensifs incluant masques et démêlants pour cheveux abîmés ou secs.',
            ],
            [
                'name' => 'Entretien de la Barbe',
                'description' => 'Huiles, sérums et baumes à barbe pour nourrir, adoucir et entretenir une barbe saine.',
            ],
            [
                'name' => 'Rasage',
                'description' => 'Gels de rasage de précision, crèmes et soins après-rasage pour un rasage doux et sans irritation.',
            ],
            [
                'name' => 'Coloration Capillaire',
                'description' => 'Produits de coloration temporaire et semi-permanente dans une variété de teintes éclatantes.',
            ],
            [
                'name' => 'Coiffage',
                'description' => 'Sprays, crèmes et gels pour le coiffage quotidien, la fixation et la finition des cheveux.',
            ],
            [
                'name' => 'Soins du Corps',
                'description' => 'Gels douche, déodorants et soins essentiels du corps pour l\'hygiène et la fraîcheur quotidiennes.',
            ],
            [
                'name' => 'Masque Capillaire',
                'description' => 'Masques capillaires ultra-nourrissants pour une réparation intensive, une hydratation et un éclat restauré.',
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
