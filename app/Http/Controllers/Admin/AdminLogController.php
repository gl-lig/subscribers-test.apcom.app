<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\NotifyBatIdJob;
use App\Models\AdminActivityLog;
use App\Models\ApiLog;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminLogController extends Controller
{
    public function activity(Request $request)
    {
        $query = AdminActivityLog::with('admin');

        if ($module = $request->get('module')) {
            $query->where('module', $module);
        }

        $logs = $query->latest()->paginate(30);
        return view('admin.logs.activity', compact('logs'));
    }

    public function api(Request $request)
    {
        $query = ApiLog::query();

        if ($event = $request->get('event')) {
            $query->where('event', $event);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $logs = $query->latest()->paginate(30);
        return view('admin.logs.api', compact('logs'));
    }

    public function replay(ApiLog $apiLog)
    {
        $order = Order::whereHas('subscriber', fn ($q) => $q->where('bat_id', $apiLog->bat_id))->latest()->first();

        if ($order) {
            NotifyBatIdJob::dispatch($order, $apiLog->event);
            return back()->with('success', 'Notification rejouée.');
        }

        return back()->with('error', 'Commande introuvable pour ce bat-ID.');
    }
}
