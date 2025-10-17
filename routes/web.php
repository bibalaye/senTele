<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return redirect()->route('channels');
})->middleware(['auth'])->name('home');

Route::get('/channels', App\Livewire\ChannelList::class)
    ->middleware(['auth'])
    ->name('channels');

// Proxy pour les flux HLS (résout les problèmes CORS)
Route::middleware(['auth'])->group(function () {
    Route::get('/stream/proxy/{channel}', [App\Http\Controllers\StreamProxyController::class, 'proxy'])->name('stream.proxy');
    Route::get('/stream/segment', [App\Http\Controllers\StreamProxyController::class, 'segment'])->name('stream.segment');
});

// Redirection du dashboard vers les chaînes
Route::get('/dashboard', function () {
    return redirect()->route('channels');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes Abonnements
Route::middleware(['auth'])->prefix('subscriptions')->name('subscriptions.')->group(function () {
    Route::get('/', App\Livewire\SubscriptionPlans::class)->name('index');
    Route::get('/my-subscription', App\Livewire\MySubscription::class)->name('my');
});

// Routes Paiement
Route::middleware(['auth'])->prefix('payment')->name('payment.')->group(function () {
    Route::get('/checkout/{plan}', [App\Http\Controllers\PaymentController::class, 'checkout'])->name('checkout');
    Route::post('/process', [App\Http\Controllers\PaymentController::class, 'processPayment'])->name('process');
    Route::post('/promo/apply', [App\Http\Controllers\PaymentController::class, 'applyPromoCode'])->name('promo.apply');
    Route::post('/promo/remove', [App\Http\Controllers\PaymentController::class, 'removePromoCode'])->name('promo.remove');
    Route::get('/success/{subscription}', [App\Http\Controllers\PaymentController::class, 'success'])->name('success');
    Route::get('/cancel', [App\Http\Controllers\PaymentController::class, 'cancel'])->name('cancel');
});

// Routes Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', App\Livewire\Admin\Dashboard::class)->name('dashboard');
    Route::get('/users', App\Livewire\Admin\UserManagement::class)->name('users');
    Route::get('/subscriptions', App\Livewire\Admin\SubscriptionManagement::class)->name('subscriptions');
    Route::get('/channels', App\Livewire\Admin\ChannelManagement::class)->name('channels');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

require __DIR__.'/auth.php';
