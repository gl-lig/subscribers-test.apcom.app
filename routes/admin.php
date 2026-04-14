<?php

use App\Http\Controllers\Admin\AdminAdminController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminExportController;
use App\Http\Controllers\Admin\AdminLanguageController;
use App\Http\Controllers\Admin\AdminLogController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminPromoCodeController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminSubscriberController;
use App\Http\Controllers\Admin\AdminSubscriptionTypeController;
use App\Http\Controllers\Admin\AdminUserGroupController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    // Auth routes (guest)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    // 2FA routes (authenticated but not 2FA verified)
    Route::middleware('admin.auth')->group(function () {
        Route::get('/2fa', [AdminAuthController::class, 'show2fa'])->name('2fa');
        Route::post('/2fa', [AdminAuthController::class, 'verify2fa'])->name('2fa.verify');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });

    // Protected admin routes
    Route::middleware(['admin.auth', 'admin.2fa', 'admin.timeout'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Subscribers
        Route::get('/subscribers', [AdminSubscriberController::class, 'index'])->name('subscribers.index');
        Route::get('/subscribers/{subscriber}', [AdminSubscriberController::class, 'show'])->name('subscribers.show');

        // Subscription types
        Route::resource('subscription-types', AdminSubscriptionTypeController::class)->except(['show']);
        Route::post('/subscription-types/reorder', [AdminSubscriptionTypeController::class, 'reorder'])->name('subscription-types.reorder');

        // Orders
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/replay-notification', [AdminOrderController::class, 'replayNotification'])->name('orders.replay');
        Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');

        // Promo codes
        Route::resource('promo-codes', AdminPromoCodeController::class)->except(['show']);

        // User groups
        Route::resource('user-groups', AdminUserGroupController::class);
        Route::post('/user-groups/{userGroup}/members', [AdminUserGroupController::class, 'addMember'])->name('user-groups.members.add');
        Route::delete('/user-groups/{userGroup}/members/{batId}', [AdminUserGroupController::class, 'removeMember'])->name('user-groups.members.remove');

        // Payments
        Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/{paymentLog}', [AdminPaymentController::class, 'show'])->name('payments.show');

        // Languages
        Route::get('/languages', [AdminLanguageController::class, 'index'])->name('languages.index');
        Route::get('/languages/{locale}/translations', [AdminLanguageController::class, 'translations'])->name('languages.translations');
        Route::post('/languages/translations', [AdminLanguageController::class, 'updateTranslation'])->name('languages.translations.update');

        // Admins
        Route::resource('admins', AdminAdminController::class)->except(['show']);

        // Settings
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

        // Logs
        Route::get('/logs/activity', [AdminLogController::class, 'activity'])->name('logs.activity');
        Route::get('/logs/api', [AdminLogController::class, 'api'])->name('logs.api');
        Route::post('/logs/api/{apiLog}/replay', [AdminLogController::class, 'replay'])->name('logs.api.replay');

        // Exports
        Route::get('/export/{type}', [AdminExportController::class, 'export'])->name('export');
    });
});
