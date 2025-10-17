<?php

namespace App\Console\Commands;

use App\Models\Channel;
use App\Models\SubscriptionPlan;
use Illuminate\Console\Command;

class AddTestChannel extends Command
{
    protected $signature = 'channel:add-test';
    protected $description = 'Ajouter une chaîne de test avec un flux HLS public';

    public function handle()
    {
        $this->info('🎬 Ajout d\'une chaîne de test...');

        // Vérifier si la chaîne existe déjà
        if (Channel::where('name', 'Test Channel - Big Buck Bunny')->exists()) {
            $this->warn('⚠️  La chaîne de test existe déjà');
            
            if (!$this->confirm('Voulez-vous la recréer ?')) {
                return 0;
            }
            
            Channel::where('name', 'Test Channel - Big Buck Bunny')->delete();
        }

        // Créer la chaîne de test
        $channel = Channel::create([
            'name' => 'Test Channel - Big Buck Bunny',
            'stream_url' => 'https://test-streams.mux.dev/x36xhzz/x36xhzz.m3u8',
            'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c5/Big_buck_bunny_poster_big.jpg/220px-Big_buck_bunny_poster_big.jpg',
            'category' => 'Test',
            'country' => 'International',
            'is_active' => true,
        ]);

        // Associer au plan gratuit
        $freePlan = SubscriptionPlan::where('slug', 'free')->first();
        
        if ($freePlan) {
            $channel->subscriptionPlans()->attach($freePlan->id);
            $this->info('✅ Chaîne associée au plan gratuit');
        }

        $this->info('✅ Chaîne de test créée avec succès !');
        $this->line('');
        $this->line('📺 Nom: ' . $channel->name);
        $this->line('🔗 URL: ' . $channel->stream_url);
        $this->line('📁 Catégorie: ' . $channel->category);
        $this->line('');
        $this->info('👉 Allez sur /channels pour la tester');

        return 0;
    }
}
