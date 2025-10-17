<?php

namespace App\Console\Commands;

use App\Models\UserSubscription;
use Illuminate\Console\Command;

class CheckExpiredSubscriptions extends Command
{
    protected $signature = 'subscriptions:check-expired';
    protected $description = 'Vérifier et mettre à jour les abonnements expirés';

    public function handle()
    {
        $this->info('🔍 Vérification des abonnements expirés...');

        $expired = UserSubscription::where('status', 'active')
            ->where('expires_at', '<', now())
            ->get();

        if ($expired->isEmpty()) {
            $this->info('✅ Aucun abonnement expiré trouvé');
            return 0;
        }

        $bar = $this->output->createProgressBar($expired->count());
        $bar->start();

        foreach ($expired as $subscription) {
            $subscription->update(['status' => 'expired']);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $this->info("✅ {$expired->count()} abonnement(s) marqué(s) comme expiré(s)");

        return 0;
    }
}
