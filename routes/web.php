<?php

use App\Http\Controllers\ApiRegisterController;
use App\Http\Controllers\DatatransWebhookController;
use App\Http\Controllers\DeeplinkController;
use App\Http\Controllers\InvoiceController;
use App\Livewire\Cart;
use App\Livewire\PaymentConfirmation;
use App\Livewire\PaymentFailed;
use App\Livewire\PricingPage;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', PricingPage::class)->name('home');
Route::get('/deeplink', [DeeplinkController::class, 'handle'])->name('deeplink');
Route::get('/api/register', [ApiRegisterController::class, 'handle'])->name('api.register');
Route::get('/cart', Cart::class)->name('cart');
Route::get('/confirmation', PaymentConfirmation::class)->name('confirmation');
Route::get('/payment-failed', PaymentFailed::class)->name('payment.failed');

// Locale switch
Route::get('/locale/{locale}', function (string $locale) {
    if (in_array($locale, config('app.available_locales', ['fr']))) {
        session()->put('locale', $locale);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('locale.switch');

// Invoice (public, no auth, secured by token)
Route::get('/invoice/{token}', [InvoiceController::class, 'show'])->name('invoice.show');

// Public API — default subscription type
Route::get('/api/default-subscription', function () {
    $type = \App\Models\SubscriptionType::where('is_default', true)
        ->with('translations')
        ->first();

    if (! $type) {
        return response()->json(['error' => 'no_default_configured'], 404);
    }

    $translations = [];
    foreach ($type->translations as $t) {
        $translations[$t->locale] = [
            'name' => $t->name,
            'description' => $t->description,
        ];
    }

    return response()->json([
        'id' => $type->id,
        'status' => $type->status,
        'price_chf' => (float) $type->price_chf,
        'is_free' => $type->is_free,
        'discount_24_months' => (float) $type->discount_24_months,
        'discount_36_months' => (float) $type->discount_36_months,
        'parcelles_count' => $type->parcelles_count,
        'parcelles_unlimited' => $type->parcelles_unlimited,
        'alertes_count' => $type->alertes_count,
        'stockage_go' => $type->stockage_go,
        'stockage_unlimited' => $type->stockage_unlimited,
        'cloud_externe' => $type->cloud_externe,
        'lot_sauvegarde' => $type->lot_sauvegarde,
        'veille_robotisee' => $type->veille_robotisee,
        'veille_count' => $type->veille_count,
        'veille_unlimited' => $type->veille_unlimited,
        'workspace_enabled' => $type->workspace_enabled,
        'workspace_count' => $type->workspace_count,
        'workspace_unlimited' => $type->workspace_unlimited,
        'translations' => $translations,
    ]);
})->name('api.default-subscription');

// Health check
Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()->toIso8601String()]);
});

// Datatrans webhook (no CSRF)
Route::post('/webhook/datatrans', [DatatransWebhookController::class, 'handle'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('webhook.datatrans');

// Admin routes
require __DIR__ . '/admin.php';
