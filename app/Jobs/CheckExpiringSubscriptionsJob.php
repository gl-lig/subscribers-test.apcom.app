<?php

namespace App\Jobs;

use App\Events\SubscriptionExpiringSoon;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckExpiringSubscriptionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 120;
    public int $tries = 1;

    public function handle(): void
    {
        $days = (int) Setting::get('expiry_notification_days', 30);

        $orders = Order::where('status', 'active')
            ->whereDate('expires_at', now()->addDays($days)->toDateString())
            ->whereNull('expiry_notified_at')
            ->get();

        foreach ($orders as $order) {
            event(new SubscriptionExpiringSoon($order));
            NotifyBatIdJob::dispatch($order, 'subscription_expiring_soon');
            $order->update(['expiry_notified_at' => now()]);
        }
    }
}
