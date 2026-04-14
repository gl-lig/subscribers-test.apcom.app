<?php

namespace App\Providers;

use App\Contracts\BatIdServiceInterface;
use App\Contracts\DatatransServiceInterface;
use App\Contracts\OrderServiceInterface;
use App\Services\BatIdApiService;
use App\Services\BatIdMockService;
use App\Services\DatatransService;
use App\Services\OrderService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BatIdServiceInterface::class, function () {
            return config('batid.api_mode') === 'mock'
                ? new BatIdMockService()
                : new BatIdApiService();
        });

        $this->app->bind(DatatransServiceInterface::class, DatatransService::class);
        $this->app->bind(OrderServiceInterface::class, OrderService::class);
    }

    public function boot(): void
    {
        //
    }
}
