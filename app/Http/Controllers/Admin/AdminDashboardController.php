<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $activeSubscriptions = Order::where('status', 'active')
            ->where('expires_at', '>', now())
            ->count();

        $todayOrders = Order::whereDate('concluded_at', today());
        $todayCount = $todayOrders->count();
        $todayAmount = $todayOrders->sum('price_paid');

        $monthOrders = Order::whereMonth('concluded_at', now()->month)
            ->whereYear('concluded_at', now()->year);
        $monthCount = $monthOrders->count();
        $monthAmount = $monthOrders->sum('price_paid');

        $expiringIn30Days = Order::where('status', 'active')
            ->whereBetween('expires_at', [now(), now()->addDays(30)])
            ->count();

        $expiredNotRenewed = Order::where('status', 'expired')
            ->whereNull('replaced_by_order_id')
            ->where('expires_at', '>=', now()->subMonths(3))
            ->count();

        $failedJobs = DB::table('failed_jobs')->count();

        return view('admin.dashboard', compact(
            'activeSubscriptions',
            'todayCount', 'todayAmount',
            'monthCount', 'monthAmount',
            'expiringIn30Days',
            'expiredNotRenewed',
            'failedJobs'
        ));
    }
}
