<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un utilisateur admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@sentele.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Créer un utilisateur test
        User::create([
            'name' => 'Test User',
            'email' => 'test@sentele.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Créer quelques utilisateurs aléatoires
        User::factory(10)->create();
    }
}
