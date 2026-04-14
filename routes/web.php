<?php

use App\Http\Controllers\DatatransWebhookController;
use App\Http\Controllers\InvoiceController;
use App\Livewire\Cart;
use App\Livewire\PaymentConfirmation;
use App\Livewire\PaymentFailed;
use App\Livewire\PricingPage;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', PricingPage::class)->name('home');
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
