<?php

namespace App\Jobs;

use App\Events\SubscriptionExpired;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckExpiredSubscriptionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 120;
    public int $tries = 1;

    public function handle(): void
    {
        $orders = Order::where('status', 'active')
            ->where('expires_at', '<=', now()->toDateString())
            ->get();

        foreach ($orders as $order) {
            $order->update(['status' => 'expired']);
            event(new SubscriptionExpired($order));
            NotifyBatIdJob::dispatch($order, 'subscription_expired');
        }
    }
}
