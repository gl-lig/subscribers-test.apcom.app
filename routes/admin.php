<?php

use App\Http\Controllers\Admin\AdminAdminController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminProfileController;
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

        // API section — accessible to all roles including api_user
        Route::get('/logs/api', [AdminLogController::class, 'api'])->name('logs.api');
        Route::post('/logs/api/{apiLog}/replay', [AdminLogController::class, 'replay'])->name('logs.api.replay');
        Route::get('/api/documentation', [AdminLogController::class, 'documentation'])->name('api.documentation');
        Route::get('/api/test-token', [AdminLogController::class, 'generateTestToken'])->name('api.test-token');
        Route::get('/api/test-register-token', [AdminLogController::class, 'generateTestRegisterToken'])->name('api.test-register-token');
        Route::post('/api/send-documentation', [AdminLogController::class, 'sendDocumentation'])->name('api.send-documentation');

        // Profile — accessible to all roles
        Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile.index');
        Route::post('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');
        Route::get('/profile/2fa/setup', [AdminProfileController::class, 'setup2fa'])->name('profile.2fa.setup');
        Route::post('/profile/2fa/confirm', [AdminProfileController::class, 'confirm2fa'])->name('profile.2fa.confirm');
        Route::post('/profile/2fa/disable', [AdminProfileController::class, 'disable2fa'])->name('profile.2fa.disable');

        // Full access routes — blocked for api_user
        Route::middleware('admin.full')->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
            Route::post('/dashboard/run-tests', [AdminDashboardController::class, 'runTests'])->name('dashboard.run-tests');

            // Subscribers
            Route::get('/subscribers', [AdminSubscriberController::class, 'index'])->name('subscribers.index');
            Route::get('/subscribers/{subscriber}', [AdminSubscriberController::class, 'show'])->name('subscribers.show');
            Route::delete('/subscribers/{subscriber}', [AdminSubscriberController::class, 'destroy'])->name('subscribers.destroy');

            // Subscription types
            Route::resource('subscription-types', AdminSubscriptionTypeController::class)->except(['show']);
            Route::post('/subscription-types/reorder', [AdminSubscriptionTypeController::class, 'reorder'])->name('subscription-types.reorder');
            Route::post('/subscription-types/{subscription_type}/set-default', [AdminSubscriptionTypeController::class, 'setDefault'])->name('subscription-types.set-default');

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

            // Exports
            Route::get('/export/{type}', [AdminExportController::class, 'export'])->name('export');
        });
    });
});
