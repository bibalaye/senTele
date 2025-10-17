<?php

namespace App\Http\Controllers;

use App\Models\PromoCode;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function checkout(Request $request, SubscriptionPlan $plan)
    {
        $user = auth()->user();
        
        // Vérifier si l'utilisateur a déjà un abonnement actif
        $activeSubscription = $user->activeSubscription;
        
        $promoCode = null;
        $discount = 0;
        $finalPrice = $plan->price;
        
        // Vérifier le code promo en session
        if (session()->has('promo_code')) {
            $promoCode = session('promo_code');
            if ($promoCode->isValid()) {
                $discount = $promoCode->calculateDiscount($plan->price);
                $finalPrice = $plan->price - $discount;
            }
        }
        
        return view('payment.checkout', compact('plan', 'activeSubscription', 'promoCode', 'discount', 'finalPrice'));
    }
    
    public function applyPromoCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);
        
        $promoCode = PromoCode::where('code', strtoupper($request->code))
            ->active()
            ->first();
        
        if (!$promoCode || !$promoCode->isValid()) {
            return back()->with('error', 'Code promo invalide ou expiré.');
        }
        
        session(['promo_code' => $promoCode]);
        
        return back()->with('success', 'Code promo appliqué avec succès !');
    }
    
    public function removePromoCode()
    {
        session()->forget('promo_code');
        return back()->with('success', 'Code promo retiré.');
    }
    
    public function processPayment(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'payment_method' => 'required|in:wave,orange_money,free_money,stripe,free',
            'phone' => 'required_if:payment_method,wave,orange_money,free_money',
        ]);
        
        $plan = SubscriptionPlan::findOrFail($validated['plan_id']);
        $user = auth()->user();
        
        // Calculer le prix final avec le code promo
        $finalPrice = $plan->price;
        $promoCode = null;
        
        if (session()->has('promo_code')) {
            $promoCode = session('promo_code');
            if ($promoCode->isValid()) {
                $discount = $promoCode->calculateDiscount($plan->price);
                $finalPrice = $plan->price - $discount;
            }
        }
        
        // Si c'est un plan gratuit ou le prix final est 0, activer directement
        if ($finalPrice == 0 || $validated['payment_method'] === 'free') {
            return $this->activateSubscription($user, $plan, 'free', null, $promoCode);
        }
        
        // Sinon, rediriger vers le provider de paiement
        switch ($validated['payment_method']) {
            case 'wave':
                return $this->processWavePayment($user, $plan, $finalPrice, $request->phone, $promoCode);
            case 'orange_money':
                return $this->processOrangeMoneyPayment($user, $plan, $finalPrice, $request->phone, $promoCode);
            case 'free_money':
                return $this->processFreeMoneyPayment($user, $plan, $finalPrice, $request->phone, $promoCode);
            case 'stripe':
                return $this->processStripePayment($user, $plan, $finalPrice, $promoCode);
            default:
                return back()->with('error', 'Méthode de paiement non supportée.');
        }
    }
    
    protected function activateSubscription($user, $plan, $paymentMethod, $transactionId = null, $promoCode = null)
    {
        // Annuler l'abonnement actif s'il existe
        $activeSubscription = $user->activeSubscription;
        if ($activeSubscription) {
            $activeSubscription->update(['status' => 'cancelled']);
        }
        
        // Créer le nouvel abonnement
        $subscription = UserSubscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'starts_at' => now(),
            'expires_at' => now()->addDays($plan->duration_days),
            'status' => 'active',
            'payment_method' => $paymentMethod,
            'transaction_id' => $transactionId,
        ]);
        
        // Incrémenter l'utilisation du code promo
        if ($promoCode) {
            $promoCode->incrementUsage();
            session()->forget('promo_code');
        }
        
        return redirect()->route('payment.success', $subscription)
            ->with('success', 'Abonnement activé avec succès !');
    }
    
    protected function processWavePayment($user, $plan, $amount, $phone, $promoCode)
    {
        // TODO: Intégrer l'API Wave Money
        // Pour l'instant, on simule un paiement réussi
        
        // En production, vous devrez :
        // 1. Appeler l'API Wave pour initier le paiement
        // 2. Rediriger l'utilisateur vers la page de paiement Wave
        // 3. Gérer le callback de Wave pour confirmer le paiement
        
        // Simulation pour le développement
        $transactionId = 'WAVE_' . uniqid();
        
        return $this->activateSubscription($user, $plan, 'wave', $transactionId, $promoCode);
    }
    
    protected function processOrangeMoneyPayment($user, $plan, $amount, $phone, $promoCode)
    {
        // TODO: Intégrer l'API Orange Money
        $transactionId = 'ORANGE_' . uniqid();
        
        return $this->activateSubscription($user, $plan, 'orange_money', $transactionId, $promoCode);
    }
    
    protected function processFreeMoneyPayment($user, $plan, $amount, $phone, $promoCode)
    {
        // TODO: Intégrer l'API Free Money
        $transactionId = 'FREE_' . uniqid();
        
        return $this->activateSubscription($user, $plan, 'free_money', $transactionId, $promoCode);
    }
    
    protected function processStripePayment($user, $plan, $amount, $promoCode)
    {
        // TODO: Intégrer Stripe
        $transactionId = 'STRIPE_' . uniqid();
        
        return $this->activateSubscription($user, $plan, 'stripe', $transactionId, $promoCode);
    }
    
    public function success(UserSubscription $subscription)
    {
        if ($subscription->user_id !== auth()->id()) {
            abort(403);
        }
        
        return view('payment.success', compact('subscription'));
    }
    
    public function cancel()
    {
        return view('payment.cancel');
    }
}
