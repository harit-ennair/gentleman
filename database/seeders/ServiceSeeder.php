<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the services table.
     *
     * Each service is created using updateOrCreate to prevent
     * duplicate records when the seeder is run multiple times.
     * Prices are in the local currency and durations are in minutes.
     */
    public function run(): void
    {
        $services = [
            // ── Haircut Services ──────────────────────────────────────
            [
                'name' => 'Coupe Normal',
                'description' => 'Classic haircut with clippers and scissors, tailored to your preferred style.',
                'price' => 70.00,
                'duration' => 30,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Coupe Speciale',
                'description' => 'Premium haircut with advanced techniques including fades, designs, or textured cuts.',
                'price' => 100.00,
                'duration' => 45,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Coupe Enfant',
                'description' => 'Gentle and patient haircut service designed for children of all ages.',
                'price' => 50.00,
                'duration' => 20,
                'image_path' => null,
                'is_active' => true,
            ],

            // ── Beard Services ────────────────────────────────────────
            [
                'name' => 'Barbe Normal',
                'description' => 'Traditional beard trim and shaping with scissors and a straight razor finish.',
                'price' => 40.00,
                'duration' => 20,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Barbe Special',
                'description' => 'Premium beard grooming with hot towel treatment, precision shaping, and beard oil application.',
                'price' => 70.00,
                'duration' => 30,
                'image_path' => null,
                'is_active' => true,
            ],

            // ── Styling Services ──────────────────────────────────────
            [
                'name' => 'Brushing',
                'description' => 'Professional blow-dry and styling to achieve a polished, voluminous look.',
                'price' => 30.00,
                'duration' => 20,
                'image_path' => null,
                'is_active' => true,
            ],

            // ── Coloration Services ───────────────────────────────────
            [
                'name' => 'Coloration Silver',
                'description' => 'Silver or platinum hair coloring treatment for a modern, sophisticated appearance.',
                'price' => 300.00,
                'duration' => 90,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Coloration Cheveux',
                'description' => 'Full hair coloring service with professional-grade dye and post-color conditioning.',
                'price' => 100.00,
                'duration' => 60,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Coloration Barbe',
                'description' => 'Beard coloring to cover grey hairs or achieve a desired shade, with precision application.',
                'price' => 70.00,
                'duration' => 30,
                'image_path' => null,
                'is_active' => true,
            ],

            // ── Nail Services ─────────────────────────────────────────
            [
                'name' => 'Manucure',
                'description' => 'Complete manicure including nail trimming, cuticle care, filing, and hand moisturizing.',
                'price' => 150.00,
                'duration' => 45,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Pédicure Normal',
                'description' => 'Standard pedicure with foot soak, nail care, exfoliation, and moisturizing treatment.',
                'price' => 180.00,
                'duration' => 50,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Pédicure Médicale',
                'description' => 'Medical-grade pedicure addressing ingrown nails, calluses, and foot health concerns.',
                'price' => 200.00,
                'duration' => 60,
                'image_path' => null,
                'is_active' => true,
            ],

            // ── Hair Care & Treatment Services ────────────────────────
            [
                'name' => 'Soins Cheveux',
                'description' => 'Deep hair care treatment with nourishing oils and proteins for damaged or dry hair.',
                'price' => 450.00,
                'duration' => 60,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Soins Special',
                'description' => 'Premium intensive hair restoration treatment using advanced keratin and collagen formulas.',
                'price' => 650.00,
                'duration' => 90,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Shampoing + Brushing',
                'description' => 'Professional shampoo wash followed by a blow-dry and light styling.',
                'price' => 50.00,
                'duration' => 25,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Soin Capillaire',
                'description' => 'Scalp and hair treatment targeting hydration, dandruff control, and follicle health.',
                'price' => 100.00,
                'duration' => 40,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Masque Capillaire',
                'description' => 'Deep conditioning hair mask applied with steam for maximum penetration and repair.',
                'price' => 150.00,
                'duration' => 45,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Protéine pour Cheveux',
                'description' => 'Intensive protein treatment to rebuild hair structure, reduce breakage, and restore elasticity.',
                'price' => 600.00,
                'duration' => 75,
                'image_path' => null,
                'is_active' => true,
            ],

            // ── Facial & Skincare Services ────────────────────────────
            [
                'name' => 'Masque Visage',
                'description' => 'Detoxifying and hydrating facial mask treatment for cleaner, smoother skin.',
                'price' => 100.00,
                'duration' => 30,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Soin du Visage Normal',
                'description' => 'Basic facial care including cleansing, toning, and moisturizing for everyday freshness.',
                'price' => 100.00,
                'duration' => 30,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Soin du Visage Moyenne',
                'description' => 'Intermediate facial treatment with deep cleansing, exfoliation, and serum application.',
                'price' => 250.00,
                'duration' => 45,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Soin du Visage Extra',
                'description' => 'Premium facial experience with anti-aging serums, LED therapy, and lymphatic drainage massage.',
                'price' => 400.00,
                'duration' => 60,
                'image_path' => null,
                'is_active' => true,
            ],

            // ── Hair Removal Services ─────────────────────────────────
            [
                'name' => 'Épilation des Poils du Nez et des Oreilles',
                'description' => 'Gentle wax removal of unwanted nose and ear hair for a clean, refined look.',
                'price' => 50.00,
                'duration' => 15,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Épilation Complète du Visage',
                'description' => 'Full facial hair removal including cheeks, forehead, and upper lip using professional wax.',
                'price' => 80.00,
                'duration' => 25,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Facial (Nettoyage du Visage)',
                'description' => 'Quick facial cleanse to remove impurities, blackheads, and excess oil.',
                'price' => 30.00,
                'duration' => 20,
                'image_path' => null,
                'is_active' => true,
            ],

            // ── Combo Packs ───────────────────────────────────────────
            [
                'name' => 'Pack Coupe + Barbe',
                'description' => 'Combination package: precision haircut plus a professional beard trim and shaping.',
                'price' => 100.00,
                'duration' => 50,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Pack Coupe + Barbe + Soins Visage',
                'description' => 'Full grooming package with haircut, beard trim, and a rejuvenating facial treatment.',
                'price' => 180.00,
                'duration' => 75,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Pack Complet',
                'description' => 'The ultimate gentleman\'s package: haircut, beard grooming, facial treatment, hair care, and styling.',
                'price' => 600.00,
                'duration' => 90,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Pack 3-IN-1 450ml',
                'description' => 'Take-home product pack: shampoo, conditioner, and styling product in 450ml sizes.',
                'price' => 155.00,
                'duration' => 15,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Coupe de Cheveux + Taille de Barbe + Soin du Visage',
                'description' => 'Complete grooming trio: haircut with beard trimming and a professional facial treatment.',
                'price' => 300.00,
                'duration' => 75,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Coupe de Cheveux + Taille de Barbe + Soin Capillaire',
                'description' => 'Haircut and beard trim combined with a deep scalp and hair nourishing treatment.',
                'price' => 200.00,
                'duration' => 70,
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Coupe de Cheveux + Taille de Barbe + Gommage du Visage',
                'description' => 'Haircut and beard trim followed by an exfoliating facial scrub for smooth, refreshed skin.',
                'price' => 200.00,
                'duration' => 65,
                'image_path' => null,
                'is_active' => true,
            ],
        ];

        // Create or update each service using the name as the unique key
        foreach ($services as $service) {
            Service::updateOrCreate(
                ['name' => $service['name']],
                $service,
            );
        }
    }
}
