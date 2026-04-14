<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\NotifyBatIdJob;
use App\Models\Order;
use App\Services\AdminLogService;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['subscriber', 'subscriptionType.translations']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('subscriber', fn ($s) => $s->where('bat_id', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $orders = $query->latest('concluded_at')->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['subscriber', 'subscriptionType.translations', 'metadata', 'paymentLogs']);
        return view('admin.orders.show', compact('order'));
    }

    public function replayNotification(Order $order)
    {
        $event = $order->status === 'active' ? 'subscription_activated' : 'subscription_expired';
        NotifyBatIdJob::dispatch($order, $event);

        AdminLogService::log('replay_notification', 'orders', null, [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'event' => $event,
        ]);

        return back()->with('success', 'Notification rejouée.');
    }

    public function destroy(Order $order)
    {
        AdminLogService::log('delete', 'orders', $order->toArray());

        $order->paymentLogs()->delete();
        $order->metadata()->delete();
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', "Commande {$order->order_number} supprimée.");
    }
}
