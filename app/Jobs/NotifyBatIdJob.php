<?php

namespace App\Jobs;

use App\Contracts\BatIdServiceInterface;
use App\Mail\ApiFailureAlert;
use App\Models\Admin;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotifyBatIdJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;
    public array $backoff = [60, 300, 900, 3600, 86400];

    public function __construct(
        public Order $order,
        public string $event
    ) {}

    public function handle(BatIdServiceInterface $batIdService): void
    {
        $order = $this->order->load(['subscriber', 'subscriptionType.translations']);
        $subscriber = $order->subscriber;

        $data = [
            'invoice_url' => $order->invoice_url,
            'subscription' => [
                'order_id' => $order->order_number,
                'type' => $order->subscriptionType->translation('fr')?->name ?? '',
                'status' => $order->status,
                'started_at' => $order->starts_at->toIso8601String(),
                'expires_at' => $order->expires_at->toIso8601String(),
                'duration_months' => $order->duration_months,
                'features' => [
                    'parcelles' => $order->subscriptionType->parcelles_count,
                    'parcelles_unlimited' => $order->subscriptionType->parcelles_unlimited,
                    'alertes' => $order->subscriptionType->alertes_count,
                    'stockage_go' => $order->subscriptionType->stockage_go,
                    'stockage_unlimited' => $order->subscriptionType->stockage_unlimited,
                    'cloud_externe' => $order->subscriptionType->cloud_externe,
                    'lot_sauvegarde' => $order->subscriptionType->lot_sauvegarde,
                    'workspace' => $order->subscriptionType->workspace_enabled,
                    'workspace_quantity' => $order->subscriptionType->workspace_count,
                    'workspace_unlimited' => $order->subscriptionType->workspace_unlimited,
                ],
            ],
        ];

        if ($this->event === 'subscription_expiring_soon') {
            $data = [
                'subscription' => [
                    'order_id' => $order->order_number,
                    'type' => $order->subscriptionType->translation('fr')?->name ?? '',
                    'expires_at' => $order->expires_at->toIso8601String(),
                    'days_remaining' => $order->daysRemaining(),
                ],
            ];
        }

        $batIdService->notifySubscription($subscriber->bat_id, $this->event, $data);
    }

    public function failed(\Throwable $exception): void
    {
        $superAdmins = Admin::active()->where('role', 'super_admin')->get();

        foreach ($superAdmins as $admin) {
            Mail::to($admin->email)->queue(new ApiFailureAlert(
                $this->order,
                $this->event,
                $exception->getMessage()
            ));
        }
    }
}
