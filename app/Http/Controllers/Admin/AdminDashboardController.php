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

        $totalPassed = TestResult::where('status', 'passed')->count();
        $totalFailed = TestResult::where('status', 'failed')->count();
        $totalPending = TestResult::where('status', 'pending')->count();
        $total = $totalPassed + $totalFailed + $totalPending;
        $lastRun = TestResult::max('last_run_at');

        return view('admin.dashboard', compact(
            'results', 'groupLabels',
            'totalPassed', 'totalFailed', 'totalPending', 'total', 'lastRun'
        ));
    }

    public function runTests()
    {
        \Artisan::call('tests:run');
        $output = \Artisan::output();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Tests executes. ' . trim($output));
    }
}
