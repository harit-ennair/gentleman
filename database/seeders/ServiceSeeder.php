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
                'description' => 'Coupe classique à la tondeuse et aux ciseaux, adaptée à votre style préféré.',
                'price' => 70.00,
                'duration' => 30,
                'image_path' => 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Coupe Speciale',
                'description' => 'Coupe haut de gamme avec techniques avancées incluant dégradés, motifs ou coupes texturées.',
                'price' => 100.00,
                'duration' => 45,
                'image_path' => 'https://images.unsplash.com/photo-1599351431202-1e0f0137899a?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Coupe Enfant',
                'description' => 'Service de coupe doux et patient conçu pour les enfants de tous âges.',
                'price' => 50.00,
                'duration' => 20,
                'image_path' => 'https://images.unsplash.com/photo-1595152772835-219674b2a8a6?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],

            // ── Beard Services ────────────────────────────────────────
            [
                'name' => 'Barbe Normal',
                'description' => 'Taille et traçage traditionnels de la barbe aux ciseaux avec finition au rasoir droit.',
                'price' => 40.00,
                'duration' => 20,
                'image_path' => 'https://images.unsplash.com/photo-1621605815971-fbc98d665033?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Barbe Special',
                'description' => 'Soin complet de la barbe avec serviette chaude, traçage de précision et application d\'huile à barbe.',
                'price' => 70.00,
                'duration' => 30,
                'image_path' => 'https://images.unsplash.com/photo-1622287198514-5d10f64aaab7?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],

            // ── Styling Services ──────────────────────────────────────
            [
                'name' => 'Brushing',
                'description' => 'Séchage et coiffage professionnels pour obtenir un rendu impeccable et volumineux.',
                'price' => 30.00,
                'duration' => 20,
                'image_path' => 'https://images.unsplash.com/photo-1516975080664-ed2fc6a32937?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],

            // ── Coloration Services ───────────────────────────────────
            [
                'name' => 'Coloration Silver',
                'description' => 'Coloration argentée ou platine pour un look moderne et sophistiqué.',
                'price' => 300.00,
                'duration' => 90,
                'image_path' => 'https://images.unsplash.com/photo-1517832606589-7a598bb03b15?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Coloration Cheveux',
                'description' => 'Coloration complète des cheveux avec teinture professionnelle et soin fixateur de couleur.',
                'price' => 100.00,
                'duration' => 60,
                'image_path' => 'https://images.unsplash.com/photo-1605497746444-17dbd873c988?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Coloration Barbe',
                'description' => 'Coloration de la barbe pour couvrir les poils gris ou obtenir la teinte souhaitée avec une application précise.',
                'price' => 70.00,
                'duration' => 30,
                'image_path' => 'https://images.unsplash.com/photo-1621605815971-fbc98d665033?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],

            // ── Nail Services ─────────────────────────────────────────
            [
                'name' => 'Manucure',
                'description' => 'Manucure complète incluant coupe des ongles, soin des cuticules, limage et hydratation des mains.',
                'price' => 150.00,
                'duration' => 45,
                'image_path' => 'https://images.unsplash.com/photo-1632345031435-8727f6897d53?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Pédicure Normal',
                'description' => 'Pédicure classique avec bain de pieds, soin des ongles, exfoliation et soin hydratant.',
                'price' => 180.00,
                'duration' => 50,
                'image_path' => 'https://images.unsplash.com/photo-1519014816548-bf5fe059798b?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Pédicure Médicale',
                'description' => 'Pédicure médicale traitant les ongles incarnés, les callosités et les problèmes de santé des pieds.',
                'price' => 200.00,
                'duration' => 60,
                'image_path' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],

            // ── Hair Care & Treatment Services ────────────────────────
            [
                'name' => 'Soins Cheveux',
                'description' => 'Soin capillaire profond aux huiles nourrissantes et protéines pour cheveux abîmés ou secs.',
                'price' => 450.00,
                'duration' => 60,
                'image_path' => 'https://images.unsplash.com/photo-1527799820374-dcf8d9d4a388?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Soins Special',
                'description' => 'Soin rénovateur capillaire intensif haut de gamme utilisant des formules avancées de kératine et collagène.',
                'price' => 650.00,
                'duration' => 90,
                'image_path' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Shampoing + Brushing',
                'description' => 'Lavage shampoing professionnel suivi d\'un séchage brushing et d\'un coiffage léger.',
                'price' => 50.00,
                'duration' => 25,
                'image_path' => 'https://images.unsplash.com/photo-1560869713-7d0a29430803?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Soin Capillaire',
                'description' => 'Soin du cuir chevelu et des cheveux visant l\'hydratation, l\'anti-pelliculaire et la santé du follicule.',
                'price' => 100.00,
                'duration' => 40,
                'image_path' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Masque Capillaire',
                'description' => 'Masque capillaire nourrissant appliqué sous vapeur pour une pénétration et une réparation maximales.',
                'price' => 150.00,
                'duration' => 45,
                'image_path' => 'https://images.unsplash.com/photo-1535585209827-a15fcdbc4c2d?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Protéine pour Cheveux',
                'description' => 'Traitement intensif aux protéines pour reconstruire la fibre capillaire, réduire la casse et restaurer l\'élasticité.',
                'price' => 600.00,
                'duration' => 75,
                'image_path' => 'https://images.unsplash.com/photo-1519699047748-de8e457a634e?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],

            // ── Facial & Skincare Services ────────────────────
            [
                'name' => 'Masque Visage',
                'description' => 'Masque facial détoxifiant et hydratant pour une peau plus propre et plus douce.',
                'price' => 100.00,
                'duration' => 30,
                'image_path' => 'https://images.unsplash.com/photo-1512290900672-1f5518b5941a?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Soin du Visage Normal',
                'description' => 'Soin du visage essentiel incluant nettoyage, tonification et hydratation pour une fraîcheur au quotidien.',
                'price' => 100.00,
                'duration' => 30,
                'image_path' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Soin du Visage Moyenne',
                'description' => 'Soin du visage intermédiaire avec nettoyage en profondeur, exfoliation et application de sérum.',
                'price' => 250.00,
                'duration' => 45,
                'image_path' => 'https://images.unsplash.com/photo-1512290903080-60b691060935?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Soin du Visage Extra',
                'description' => 'Expérience visage premium avec sérums anti-âge, thérapie LED et massage de drainage lymphatique.',
                'price' => 400.00,
                'duration' => 60,
                'image_path' => 'https://images.unsplash.com/photo-1507652313519-d4e9174996dd?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],

            // ── Hair Removal Services ─────────────────────────────────
            [
                'name' => 'Épilation des Poils du Nez et des Oreilles',
                'description' => 'Épilation douce à la cire des poils indésirables du nez et des oreilles pour un aspect soigné et raffiné.',
                'price' => 50.00,
                'duration' => 15,
                'image_path' => 'https://images.unsplash.com/photo-1516975080664-ed2fc6a32937?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Épilation Complète du Visage',
                'description' => 'Épilation complète du visage incluant joues, front et lèvre supérieure à la cire professionnelle.',
                'price' => 80.00,
                'duration' => 25,
                'image_path' => 'https://images.unsplash.com/photo-1585747860715-2ba37e788b70?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Facial (Nettoyage du Visage)',
                'description' => 'Nettoyage rapide du visage pour éliminer les impuretés, points noirs et excès de sébum.',
                'price' => 30.00,
                'duration' => 20,
                'image_path' => 'https://images.unsplash.com/photo-1515377905703-c4788e51af15?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],

            // ── Combo Packs ───────────────────────────────────────────
            [
                'name' => 'Pack Coupe + Barbe',
                'description' => 'Formule combinée : coupe de cheveux de précision avec taille et traçage professionnels de la barbe.',
                'price' => 100.00,
                'duration' => 50,
                'image_path' => 'https://images.unsplash.com/photo-1622286342621-4bd786c2447c?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Pack Coupe + Barbe + Soins Visage',
                'description' => 'Formule complète avec coupe de cheveux, taille de barbe et soin du visage revitalisant.',
                'price' => 180.00,
                'duration' => 75,
                'image_path' => 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Pack Complet',
                'description' => 'Le pack ultime du gentleman : coupe, barbe, soin du visage, soin capillaire et coiffage.',
                'price' => 600.00,
                'duration' => 90,
                'image_path' => 'https://images.unsplash.com/photo-1585747860715-2ba37e788b70?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Pack 3-IN-1 450ml',
                'description' => 'Pack de produits à emporter : shampoing, après-shampoing et produit coiffant en format 450ml.',
                'price' => 155.00,
                'duration' => 15,
                'image_path' => 'https://images.unsplash.com/photo-1532712938310-34cb3982ef74?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Coupe de Cheveux + Taille de Barbe + Soin du Visage',
                'description' => 'Trio de soins complet : coupe de cheveux, taille de barbe et soin du visage professionnel.',
                'price' => 300.00,
                'duration' => 75,
                'image_path' => 'https://images.unsplash.com/photo-1622287198514-5d10f64aaab7?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Coupe de Cheveux + Taille de Barbe + Soin Capillaire',
                'description' => 'Coupe et taille de barbe combinées à un soin nourrissant profond du cuir chevelu et des cheveux.',
                'price' => 200.00,
                'duration' => 70,
                'image_path' => 'https://images.unsplash.com/photo-1622286342621-4bd786c2447c?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Coupe de Cheveux + Taille de Barbe + Gommage du Visage',
                'description' => 'Coupe et taille de barbe suivies d\'un gommage exfoliant du visage pour une peau douce et rafraîchie.',
                'price' => 200.00,
                'duration' => 65,
                'image_path' => 'https://images.unsplash.com/photo-1512690196222-7c7c72491214?auto=format&fit=crop&w=800&q=80',
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
