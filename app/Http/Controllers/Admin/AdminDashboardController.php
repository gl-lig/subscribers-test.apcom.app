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
            'DeeplinkService' => [
                'label' => 'Tokens & signatures HMAC',
                'icon' => 'fa-key',
                'desc' => 'Verifie que les tokens signes (deeplink, inscription, webhook) sont generes et valides correctement, et qu\'un token falsifie ou expire est systematiquement rejete.',
            ],
            'Models' => [
                'label' => 'Modeles de donnees',
                'icon' => 'fa-database',
                'desc' => 'Verifie le bon fonctionnement des donnees metier : calculs de prix, prorata, rabais, codes promo, droits administrateurs, detection des abonnements actifs et expires.',
            ],
            'OrderService' => [
                'label' => 'Service de commande & prix',
                'icon' => 'fa-calculator',
                'desc' => 'Verifie que les prix sont calcules correctement dans tous les cas de figure : duree, rabais, code promo, upgrade avec prorata. Garantit qu\'aucun montant errone ne sera facture.',
            ],
            'AdminAuth' => [
                'label' => 'Authentification admin',
                'icon' => 'fa-lock',
                'desc' => 'Verifie que la connexion au back-office fonctionne, que les identifiants incorrects sont refuses, et que les comptes inactifs ou restreints sont correctement bloques.',
            ],
            'AdminCrud' => [
                'label' => 'Back-office CRUD',
                'icon' => 'fa-table-columns',
                'desc' => 'Verifie que toutes les pages du back-office se chargent sans erreur et que les operations de gestion (creer, modifier, supprimer) fonctionnent sur les abonnes, abonnements, commandes, codes promo et parametres.',
            ],
            'ApiRegister' => [
                'label' => 'API inscription',
                'icon' => 'fa-user-plus',
                'desc' => 'Verifie que l\'API d\'inscription cree correctement un abonne, refuse les doublons (meme bat-id ou telephone), et rejette les tokens invalides ou manquants.',
            ],
            'Deeplink' => [
                'label' => 'API deeplink',
                'icon' => 'fa-link',
                'desc' => 'Verifie que les liens profonds depuis l\'app bat-id redirigent correctement vers le panier avec l\'abonnement pre-selectionne, et que les liens invalides redirigent vers l\'accueil.',
            ],
            'DefaultSubscriptionApi' => [
                'label' => 'API abonnement par defaut',
                'icon' => 'fa-star',
                'desc' => 'Verifie que l\'API publique retourne correctement le type d\'abonnement par defaut avec toutes ses caracteristiques et traductions.',
            ],
            'PublicRoutes' => [
                'label' => 'Routes publiques',
                'icon' => 'fa-globe',
                'desc' => 'Verifie que les pages publiques du site (accueil, panier, changement de langue, health check) sont accessibles et fonctionnent correctement.',
            ],
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
