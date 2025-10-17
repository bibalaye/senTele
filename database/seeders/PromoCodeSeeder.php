<?php

namespace Database\Seeders;

use App\Models\PromoCode;
use Illuminate\Database\Seeder;

class PromoCodeSeeder extends Seeder
{
    public function run(): void
    {
        $promoCodes = [
            [
                'code' => 'WELCOME2025',
                'type' => 'percentage',
                'value' => 50,
                'max_uses' => 100,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(3),
                'is_active' => true,
            ],
            [
                'code' => 'FIRST1000',
                'type' => 'fixed',
                'value' => 1000,
                'max_uses' => 50,
                'starts_at' => now(),
                'expires_at' => now()->addMonth(),
                'is_active' => true,
            ],
            [
                'code' => 'FREEMONTH',
                'type' => 'percentage',
                'value' => 100,
                'max_uses' => 20,
                'starts_at' => now(),
                'expires_at' => now()->addWeeks(2),
                'is_active' => true,
            ],
        ];

        foreach ($promoCodes as $code) {
            PromoCode::updateOrCreate(
                ['code' => $code['code']],
                $code
            );
        }
    }
}
