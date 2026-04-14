<?php

namespace App\Http\Controllers;

use App\Contracts\DatatransServiceInterface;
use App\Events\OrderCompleted;
use App\Events\OrderUpgraded;
use App\Jobs\GenerateInvoicePdfJob;
use App\Jobs\NotifyBatIdJob;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DatatransWebhookController extends Controller
{
    public function handle(Request $request, DatatransServiceInterface $datatransService)
    {
        $payload = $request->all();

        Log::info('Datatrans webhook received', $payload);

        $result = $datatransService->handleWebhook($payload);
        $status = $result['status'] ?? 'unknown';

        if (in_array($status, ['authorized', 'settled']) && $result['order']) {
            $order = $result['order'];
            $order->update([
                'status' => 'active',
                'concluded_at' => now(),
            ]);

            // Generate invoice PDF
            GenerateInvoicePdfJob::dispatch($order);

            // Fire events
            $replacedOrder = Order::where('replaced_by_order_id', $order->id)->first();
            if ($replacedOrder) {
                event(new OrderUpgraded($order, $replacedOrder));
            } else {
                event(new OrderCompleted($order));
            }

            // Notify bat-id.ch
            $event = $replacedOrder ? 'subscription_upgraded' : 'subscription_activated';
            NotifyBatIdJob::dispatch($order, $event);
        }

        return response()->json(['status' => 'received'], 200);
    }
}
