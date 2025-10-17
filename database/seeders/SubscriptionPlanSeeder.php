<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Gratuit',
                'slug' => 'free',
                'description' => 'Accès aux chaînes publiques gratuites',
                'price' => 0,
                'currency' => 'XOF',
                'duration_days' => 365,
                'max_devices' => 1,
                'features' => [
                    'Chaînes publiques uniquement',
                    'Qualité SD',
                    '1 appareil simultané',
                    'Publicités',
                ],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'description' => 'Accès aux chaînes régionales et nationales',
                'price' => 2500,
                'currency' => 'XOF',
                'duration_days' => 30,
                'max_devices' => 2,
                'features' => [
                    'Toutes les chaînes gratuites',
                    'Chaînes régionales',
                    'Qualité HD',
                    '2 appareils simultanés',
                    'Sans publicité',
                ],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'description' => 'Accès complet aux sports et films',
                'price' => 5000,
                'currency' => 'XOF',
                'duration_days' => 30,
                'max_devices' => 3,
                'features' => [
                    'Toutes les chaînes Basic',
                    'Chaînes sportives',
                    'Chaînes de films',
                    'Qualité Full HD',
                    '3 appareils simultanés',
                    'Enregistrement cloud',
                ],
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'VIP',
                'slug' => 'vip',
                'description' => 'Accès illimité à tout le contenu',
                'price' => 10000,
                'currency' => 'XOF',
                'duration_days' => 30,
                'max_devices' => 5,
                'features' => [
                    'Toutes les chaînes disponibles',
                    'VOD illimité',
                    'Qualité 4K',
                    '5 appareils simultanés',
                    'Support prioritaire',
                    'Accès anticipé aux nouveautés',
                ],
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
