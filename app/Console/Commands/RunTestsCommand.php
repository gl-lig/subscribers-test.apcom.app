<?php

namespace App\Console\Commands;

use App\Models\TestResult;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class RunTestsCommand extends Command
{
    protected $signature = 'tests:run';
    protected $description = 'Execute PHPUnit tests and store results in database';

    private array $testComments = [
        // DeeplinkService
        'test_generate_token_returns_valid_format' => 'Verifie que le token deeplink genere a le bon format base64.signature',
        'test_validate_token_returns_data_for_valid_token' => 'Verifie qu\'un token valide retourne les bonnes donnees (phone, bat-id, type, duree)',
        'test_validate_token_returns_null_for_tampered_token' => 'Verifie qu\'un token modifie est rejete (securite HMAC)',
        'test_validate_token_returns_null_for_invalid_format' => 'Verifie le rejet des tokens mal formes (vide, sans point, trop de segments)',
        'test_validate_token_returns_null_when_secret_empty' => 'Verifie que l\'API refuse les tokens si la cle secrete n\'est pas configuree',
        'test_validate_token_returns_null_for_expired_token' => 'Verifie qu\'un token expire est rejete apres le TTL',
        'test_generate_register_token_returns_valid_format' => 'Verifie le format du token d\'inscription',
        'test_validate_register_token_returns_data' => 'Verifie qu\'un token register valide retourne phone et bat-id',
        'test_register_token_cannot_be_used_as_deeplink' => 'Empeche la reutilisation croisee register vers deeplink',
        'test_deeplink_token_cannot_be_used_as_register' => 'Empeche la reutilisation croisee deeplink vers register',
        'test_validate_register_token_returns_null_for_tampered' => 'Verifie le rejet des tokens register falsifies',
        'test_generate_webhook_token' => 'Verifie la generation des tokens webhook sortants',
        'test_default_duration_is_12' => 'Verifie que la duree par defaut est 12 mois si non specifiee',

        // Models - Admin
        'test_admin_is_super' => 'Verifie la detection du role super_admin',
        'test_admin_is_api_user' => 'Verifie la detection du role api_user',
        'test_admin_is_active' => 'Verifie la detection du statut actif/inactif',
        'test_admin_full_name' => 'Verifie la concatenation prenom + nom',
        'test_admin_has_two_factor' => 'Verifie la detection de la 2FA activee',
        'test_admin_scope_active' => 'Verifie le filtre des admins actifs uniquement',
        'test_admin_scope_notifiable' => 'Verifie le filtre des admins recevant les notifications',

        // Models - Order
        'test_order_is_active' => 'Verifie qu\'une commande active est bien detectee',
        'test_order_is_expired' => 'Verifie qu\'une commande expiree est bien detectee',
        'test_order_days_remaining' => 'Verifie le calcul des jours restants',
        'test_order_days_remaining_expired' => 'Verifie que les jours restants sont 0 pour une commande expiree',
        'test_order_calculate_prorata' => 'Verifie le calcul du remboursement prorata temporis',
        'test_order_calculate_prorata_returns_zero_for_expired' => 'Verifie que le prorata est 0 pour une commande expiree',
        'test_order_generate_order_number' => 'Verifie le format CMD-000001 du numero de commande',
        'test_order_generate_order_number_increments' => 'Verifie l\'incrementation automatique des numeros',
        'test_order_relationships' => 'Verifie les relations abonne et type d\'abonnement',

        // Models - Subscriber
        'test_subscriber_active_order' => 'Verifie la recuperation de la commande active d\'un abonne',
        'test_subscriber_active_order_ignores_expired' => 'Verifie que les commandes expirees sont ignorees',

        // Models - SubscriptionType
        'test_subscription_type_discount_for_duration' => 'Verifie les rabais 24 et 36 mois',
        'test_subscription_type_price_for_duration' => 'Verifie le calcul du prix total avec rabais duree',
        'test_subscription_type_scope_online' => 'Verifie le filtre des types en ligne uniquement',
        'test_subscription_type_translation' => 'Verifie la recuperation des traductions par locale',
        'test_subscription_type_translation_fallback_fr' => 'Verifie le fallback vers le francais si traduction absente',

        // Models - PromoCode
        'test_promo_code_is_valid' => 'Verifie qu\'un code promo actif et dans les dates est valide',
        'test_promo_code_inactive' => 'Verifie qu\'un code desactive est rejete',
        'test_promo_code_expired' => 'Verifie qu\'un code expire est rejete',
        'test_promo_code_not_yet_valid' => 'Verifie qu\'un code pas encore actif est rejete',
        'test_promo_code_specific_user' => 'Verifie la restriction par bat-id specifique',

        // Models - Setting
        'test_setting_get_set' => 'Verifie l\'ecriture et lecture d\'un parametre',
        'test_setting_get_default' => 'Verifie la valeur par defaut si cle inexistante',
        'test_setting_update' => 'Verifie la mise a jour d\'un parametre existant',

        // OrderService
        'test_calculate_price_basic' => 'Calcul prix de base 12 mois sans rabais',
        'test_calculate_price_24_months_with_discount' => 'Calcul prix 24 mois avec rabais duree',
        'test_calculate_price_36_months_with_discount' => 'Calcul prix 36 mois avec rabais duree',
        'test_calculate_price_with_promo_code' => 'Calcul prix avec code promo valide',
        'test_calculate_price_with_invalid_promo_code' => 'Calcul prix avec code promo invalide (ignore)',
        'test_calculate_price_with_duration_and_promo' => 'Calcul prix cumul rabais duree + code promo',
        'test_calculate_price_with_prorata' => 'Calcul prix avec deduction prorata (upgrade)',
        'test_calculate_price_total_never_negative' => 'Verifie que le total ne descend jamais sous zero',
        'test_create_order' => 'Verifie la creation complete d\'une commande en base',
        'test_process_upgrade_replaces_current_order' => 'Verifie le remplacement de l\'ancienne commande lors d\'un upgrade',

        // Feature - AdminAuth
        'test_login_page_accessible' => 'Page de connexion admin accessible',
        'test_login_with_valid_credentials' => 'Connexion avec identifiants corrects',
        'test_login_with_invalid_credentials' => 'Connexion refusee avec mauvais mot de passe',
        'test_login_with_inactive_admin_blocked_by_middleware' => 'Admin inactif bloque par le middleware',
        'test_logout' => 'Deconnexion admin fonctionnelle',
        'test_dashboard_requires_auth' => 'Dashboard inaccessible sans connexion',
        'test_api_user_redirected_from_dashboard' => 'Role api_user redirige vers Journal API',
        'test_api_user_can_access_api_logs' => 'Role api_user peut voir les logs API',
        'test_api_user_can_access_documentation' => 'Role api_user peut voir la documentation',

        // Feature - AdminCrud
        'test_dashboard_loads' => 'Page systeme charge correctement',
        'test_subscribers_index' => 'Liste des abonnes accessible',
        'test_subscriber_show' => 'Detail d\'un abonne accessible',
        'test_subscriber_destroy' => 'Suppression d\'un abonne fonctionnelle',
        'test_subscription_types_index' => 'Liste des types d\'abonnement accessible',
        'test_subscription_type_create' => 'Creation d\'un type d\'abonnement avec traductions',
        'test_subscription_type_update' => 'Modification d\'un type d\'abonnement',
        'test_subscription_type_set_default' => 'Designation du type par defaut (un seul a la fois)',
        'test_subscription_type_destroy' => 'Suppression d\'un type d\'abonnement',
        'test_promo_codes_index' => 'Liste des codes promo accessible',
        'test_promo_code_create' => 'Creation d\'un code promo avec validation',
        'test_orders_index' => 'Liste des commandes accessible',
        'test_order_show' => 'Detail d\'une commande accessible',
        'test_payments_index' => 'Liste des paiements accessible',
        'test_admins_index' => 'Liste des administrateurs accessible',
        'test_admin_create' => 'Creation d\'un administrateur',
        'test_settings_index' => 'Page parametres accessible',
        'test_activity_logs' => 'Journal d\'activite admin accessible',
        'test_api_logs' => 'Journal API accessible',
        'test_languages_index' => 'Page langues et traductions accessible',

        // Feature - API Register
        'test_register_creates_subscriber' => 'Creation d\'un abonne via API register',
        'test_register_missing_token' => 'Erreur 400 si token absent',
        'test_register_invalid_token' => 'Erreur 401 si token invalide',
        'test_register_duplicate_bat_id' => 'Erreur 409 si bat-id deja existant',
        'test_register_duplicate_phone' => 'Erreur 409 si telephone deja existant',
        'test_register_duplicate_soft_deleted' => 'Erreur 409 meme pour abonne supprime (soft delete)',

        // Feature - Deeplink
        'test_deeplink_with_valid_token_redirects_to_cart' => 'Deeplink valide redirige vers le panier',
        'test_deeplink_sets_session_data' => 'Deeplink stocke bat-id, phone, type et duree en session',
        'test_deeplink_without_token_redirects_home' => 'Deeplink sans token redirige vers l\'accueil',
        'test_deeplink_with_invalid_token_redirects_home' => 'Deeplink avec token invalide redirige vers l\'accueil',
        'test_deeplink_with_nonexistent_type_redirects_home' => 'Deeplink avec type inexistant redirige vers l\'accueil',
        'test_deeplink_with_inactive_type_redirects_home' => 'Deeplink avec type inactif redirige vers l\'accueil',
        'test_deeplink_normalizes_invalid_duration' => 'Duree invalide normalisee a 12 mois',

        // Feature - Default Subscription API
        'test_returns_default_subscription' => 'API retourne le type par defaut en JSON',
        'test_returns_404_when_no_default' => 'API retourne 404 si aucun type par defaut',
        'test_returns_all_features' => 'API retourne toutes les features du type',

        // Feature - Public Routes
        'test_home_page' => 'Page d\'accueil publique accessible',
        'test_health_check' => 'Health check retourne status ok en JSON',
        'test_locale_switch' => 'Changement de langue fonctionnel',
        'test_locale_switch_invalid_keeps_default' => 'Langue invalide ignoree',
        'test_cart_page_redirects_without_session' => 'Panier redirige sans donnees en session',
    ];

    private array $groupLabels = [
        'DeeplinkService' => 'Tokens & signatures HMAC',
        'Models-Admin' => 'Modele administrateur',
        'Models-Order' => 'Modele commande',
        'Models-Subscriber' => 'Modele abonne',
        'Models-SubscriptionType' => 'Modele type d\'abonnement',
        'Models-PromoCode' => 'Modele code promo',
        'Models-Setting' => 'Modele parametres',
        'OrderService' => 'Service de commande & prix',
        'AdminAuth' => 'Authentification admin',
        'AdminCrud' => 'Back-office CRUD',
        'ApiRegister' => 'API inscription',
        'Deeplink' => 'API deeplink',
        'DefaultSubscriptionApi' => 'API abonnement par defaut',
        'PublicRoutes' => 'Routes publiques',
    ];

    public function handle(): int
    {
        $this->info('Execution des tests PHPUnit...');

        $process = new Process(
            ['php', 'vendor/bin/phpunit', '--log-junit', 'storage/app/test-results.xml'],
            base_path()
        );
        $process->setTimeout(120);
        $process->run();

        $xmlPath = storage_path('app/test-results.xml');
        if (! file_exists($xmlPath)) {
            $this->error('Fichier de resultats introuvable.');
            return 1;
        }

        $xml = simplexml_load_file($xmlPath);
        $now = now();
        $nextRun = $now->copy()->addHours(6);

        foreach ($xml->testsuite as $rootSuite) {
            $this->parseSuite($rootSuite, $now, $nextRun);
        }

        @unlink($xmlPath);

        $passed = TestResult::where('status', 'passed')->count();
        $failed = TestResult::where('status', 'failed')->count();
        $total = $passed + $failed;

        $this->info("Resultats: {$passed}/{$total} passes" . ($failed > 0 ? ", {$failed} echecs" : ''));

        return $failed > 0 ? 1 : 0;
    }

    private function parseSuite(\SimpleXMLElement $suite, $now, $nextRun): void
    {
        foreach ($suite->testcase as $testcase) {
            $class = (string) $testcase['classname'];
            $method = (string) $testcase['name'];

            $group = $this->resolveGroup($class);
            $suiteName = str_contains($class, '\\Unit\\') ? 'Unit' : 'Feature';

            $status = isset($testcase->failure) || isset($testcase->error) ? 'failed' : 'passed';
            $errorMessage = null;
            if (isset($testcase->failure)) {
                $errorMessage = (string) $testcase->failure;
            } elseif (isset($testcase->error)) {
                $errorMessage = (string) $testcase->error;
            }

            TestResult::updateOrCreate(
                ['suite' => $suiteName, 'group' => $group, 'test_name' => $method],
                [
                    'status' => $status,
                    'comment' => $this->testComments[$method] ?? null,
                    'last_run_at' => $now,
                    'next_run_at' => $nextRun,
                    'error_message' => $errorMessage ? substr($errorMessage, 0, 1000) : null,
                ]
            );
        }

        foreach ($suite->testsuite as $childSuite) {
            $this->parseSuite($childSuite, $now, $nextRun);
        }
    }

    private function resolveGroup(string $class): string
    {
        $short = class_basename($class);

        return match (true) {
            $short === 'DeeplinkServiceTest' => 'DeeplinkService',
            $short === 'OrderServiceTest' => 'OrderService',
            $short === 'ModelsTest' => 'Models',
            $short === 'AdminAuthTest' => 'AdminAuth',
            $short === 'AdminCrudTest' => 'AdminCrud',
            $short === 'ApiRegisterTest' => 'ApiRegister',
            $short === 'DeeplinkTest' => 'Deeplink',
            $short === 'DefaultSubscriptionApiTest' => 'DefaultSubscriptionApi',
            $short === 'PublicRoutesTest' => 'PublicRoutes',
            default => $short,
        };
    }
}
