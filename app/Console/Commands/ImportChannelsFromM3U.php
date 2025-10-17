<?php

namespace App\Console\Commands;

use App\Models\Channel;
use App\Models\SubscriptionPlan;
use Illuminate\Console\Command;

class ImportChannelsFromM3U extends Command
{
    protected $signature = 'channels:import {url?} {--category=Sports} {--plan=free}';
    protected $description = 'Importer des chaînes depuis une playlist M3U';

    public function handle()
    {
        $url = $this->argument('url') ?? 'https://iptv-org.github.io/iptv/categories/sports.m3u';
        $category = $this->option('category');
        $planSlug = $this->option('plan');

        $this->info("📥 Téléchargement de la playlist depuis: {$url}");

        try {
            $content = file_get_contents($url);
        } catch (\Exception $e) {
            $this->error("❌ Erreur lors du téléchargement: " . $e->getMessage());
            return 1;
        }

        // Parser le fichier M3U
        preg_match_all('/#EXTINF:-1([^\n]*)\n([^\n]*)/', $content, $matches, PREG_SET_ORDER);

        if (empty($matches)) {
            $this->error("❌ Aucune chaîne trouvée dans la playlist");
            return 1;
        }

        $this->info("✅ " . count($matches) . " chaînes trouvées");

        $plan = SubscriptionPlan::where('slug', $planSlug)->first();
        if (!$plan) {
            $this->error("❌ Plan d'abonnement '{$planSlug}' introuvable");
            return 1;
        }

        $bar = $this->output->createProgressBar(count($matches));
        $bar->start();

        $imported = 0;
        $skipped = 0;

        foreach ($matches as $match) {
            $info = $match[1];
            $streamUrl = trim($match[2]);

            // Extraire les informations
            preg_match('/tvg-logo="([^"]*)"/', $info, $logoMatch);
            preg_match('/tvg-name="([^"]*)"/', $info, $nameMatch);
            preg_match('/,(.*)$/', $info, $titleMatch);

            $name = $nameMatch[1] ?? ($titleMatch[1] ?? 'Unknown');
            $logo = $logoMatch[1] ?? null;

            if (empty($streamUrl) || !filter_var($streamUrl, FILTER_VALIDATE_URL)) {
                $skipped++;
                $bar->advance();
                continue;
            }

            $channel = Channel::updateOrCreate(
                ['stream_url' => $streamUrl],
                [
                    'name' => trim($name),
                    'logo' => $logo,
                    'category' => $category,
                    'is_active' => true,
                ]
            );

            // Associer au plan
            if (!$channel->subscriptionPlans()->where('subscription_plan_id', $plan->id)->exists()) {
                $channel->subscriptionPlans()->attach($plan->id);
            }

            $imported++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✅ Import terminé:");
        $this->info("   - {$imported} chaînes importées");
        $this->info("   - {$skipped} chaînes ignorées");
        $this->info("   - Catégorie: {$category}");
        $this->info("   - Plan: {$plan->name}");

        return 0;
    }
}
