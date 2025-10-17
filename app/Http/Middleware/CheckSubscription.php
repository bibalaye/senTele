<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    public function handle(Request $request, Closure $next, ?string $planSlug = null): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour accéder à ce contenu.');
        }

        if ($user->isBanned()) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Votre compte a été suspendu. Raison: ' . $user->banned_reason);
        }

        $subscription = $user->activeSubscription;

        if (!$subscription && $planSlug !== 'free') {
            return redirect()->route('subscriptions.index')
                ->with('error', 'Vous devez souscrire à un abonnement pour accéder à ce contenu.');
        }

        if ($planSlug && $subscription && $subscription->plan->slug !== $planSlug) {
            $requiredPlan = \App\Models\SubscriptionPlan::where('slug', $planSlug)->first();
            
            return redirect()->route('subscriptions.upgrade')
                ->with('error', "Votre abonnement actuel ne permet pas d'accéder à ce contenu. Passez au plan {$requiredPlan->name}.");
        }

        return $next($request);
    }
}
