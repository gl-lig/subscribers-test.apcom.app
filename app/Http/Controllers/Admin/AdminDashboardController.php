<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestResult;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $results = TestResult::orderBy('suite')
            ->orderBy('group')
            ->orderBy('test_name')
            ->get()
            ->groupBy('group');

        $groupLabels = [
            'DeeplinkService' => ['label' => 'Tokens & signatures HMAC', 'icon' => 'fa-key'],
            'Models' => ['label' => 'Modeles de donnees', 'icon' => 'fa-database'],
            'OrderService' => ['label' => 'Service de commande & prix', 'icon' => 'fa-calculator'],
            'AdminAuth' => ['label' => 'Authentification admin', 'icon' => 'fa-lock'],
            'AdminCrud' => ['label' => 'Back-office CRUD', 'icon' => 'fa-table-columns'],
            'ApiRegister' => ['label' => 'API inscription', 'icon' => 'fa-user-plus'],
            'Deeplink' => ['label' => 'API deeplink', 'icon' => 'fa-link'],
            'DefaultSubscriptionApi' => ['label' => 'API abonnement par defaut', 'icon' => 'fa-star'],
            'PublicRoutes' => ['label' => 'Routes publiques', 'icon' => 'fa-globe'],
        ];

        $counts = TestResult::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'passed' THEN 1 ELSE 0 END) as passed,
            SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
            MAX(last_run_at) as last_run
        ")->first();

        $total = (int) $counts->total;
        $totalPassed = (int) $counts->passed;
        $totalFailed = (int) $counts->failed;
        $totalPending = (int) $counts->pending;
        $lastRun = $counts->last_run;

        return view('admin.dashboard', compact(
            'results', 'groupLabels',
            'totalPassed', 'totalFailed', 'totalPending', 'total', 'lastRun'
        ));
    }

    public function runTests()
    {
        if (! auth()->guard('admin')->user()?->isSuper()) {
            abort(403);
        }

        \Artisan::call('tests:run');
        $output = \Artisan::output();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Tests executes. ' . trim($output));
    }
}
