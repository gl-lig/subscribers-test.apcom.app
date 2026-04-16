<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class AdminLanguageController extends Controller
{
    private const CATEGORY_META = [
        'accueil'         => ['label' => 'Accueil & Tarification', 'icon' => 'fa-solid fa-house'],
        'tunnel'          => ['label' => "Tunnel d'achat",         'icon' => 'fa-solid fa-cart-shopping'],
        'fonctionnalites' => ['label' => 'Fonctionnalités',        'icon' => 'fa-solid fa-puzzle-piece'],
        'facture'         => ['label' => 'Facture PDF',            'icon' => 'fa-solid fa-file-pdf'],
        'commun'          => ['label' => 'Éléments communs',       'icon' => 'fa-solid fa-layer-group'],
    ];

    private const FILE_MAP = [
        'resources/views/livewire/pricing-page.blade.php'          => ['category' => 'accueil',         'location' => "Page d'accueil",          'type' => 'fo'],
        'resources/views/livewire/phone-verification.blade.php'    => ['category' => 'tunnel',          'location' => 'Vérification téléphone',  'type' => 'fo'],
        'resources/views/livewire/cart.blade.php'                  => ['category' => 'tunnel',          'location' => 'Panier',                  'type' => 'fo'],
        'resources/views/livewire/payment-confirmation.blade.php'  => ['category' => 'tunnel',          'location' => 'Confirmation paiement',   'type' => 'fo'],
        'resources/views/livewire/payment-failed.blade.php'        => ['category' => 'tunnel',          'location' => 'Échec paiement',          'type' => 'fo'],
        'resources/views/components/features-explainer.blade.php'  => ['category' => 'fonctionnalites', 'location' => 'Détail fonctionnalités',  'type' => 'fo'],
        'resources/views/components/subscription-features.blade.php' => ['category' => 'fonctionnalites', 'location' => 'Liste caractéristiques', 'type' => 'fo'],
        'resources/views/layouts/app.blade.php'                    => ['category' => 'commun',          'location' => 'Pied de page',            'type' => 'fo'],
        'resources/views/pdf/invoice.blade.php'                    => ['category' => 'facture',         'location' => 'Facture PDF',             'type' => 'pdf'],
        'resources/views/admin/orders/index.blade.php'             => ['category' => 'commun',          'location' => 'Admin commandes',         'type' => 'bo'],
        'resources/views/admin/orders/show.blade.php'              => ['category' => 'commun',          'location' => 'Admin détail commande',   'type' => 'bo'],
        'resources/views/admin/subscribers/show.blade.php'         => ['category' => 'commun',          'location' => 'Admin détail abonné',     'type' => 'bo'],
        'app/Livewire/Cart.php'                                    => ['category' => 'tunnel',          'location' => 'Panier (logique)',         'type' => 'fo'],
        'app/Livewire/PhoneVerification.php'                       => ['category' => 'tunnel',          'location' => 'Vérification (logique)',   'type' => 'fo'],
    ];

    public function index()
    {
        $locales = config('app.available_locales', ['fr', 'de', 'it', 'en']);
        $languages = [];
        $frTranslations = $this->loadTranslations('fr');
        $totalKeys = count($frTranslations);

        foreach ($locales as $locale) {
            $translations = $this->loadTranslations($locale);
            $translated = collect($translations)->filter(fn ($v) => ! empty($v))->count();

            $languages[] = [
                'locale' => $locale,
                'total' => $totalKeys,
                'translated' => $translated,
                'percentage' => $totalKeys > 0 ? round(($translated / $totalKeys) * 100) : 0,
            ];
        }

        return view('admin.languages.index', compact('languages'));
    }

    public function translations(string $locale)
    {
        $frTranslations = $this->loadTranslations('fr');
        $translations = $this->loadTranslations($locale);
        $contextMap = $this->buildKeyContextMap();

        $items = [];
        foreach ($frTranslations as $key => $value) {
            $ctx = $contextMap[$key] ?? null;
            $items[] = [
                'key'         => $key,
                'source'      => $value,
                'translation' => $translations[$key] ?? '',
                'category'    => $ctx ? $ctx['categories'][0] : 'commun',
                'locations'   => $ctx ? $ctx['locations'] : [],
                'types'       => $ctx ? $ctx['types'] : [],
                'isLong'      => mb_strlen($value) > 80,
            ];
        }

        $categoryOrder = array_keys(self::CATEGORY_META);
        usort($items, function ($a, $b) use ($categoryOrder) {
            $catA = array_search($a['category'], $categoryOrder);
            $catB = array_search($b['category'], $categoryOrder);
            if ($catA !== $catB) return $catA - $catB;
            return mb_strlen($a['source']) - mb_strlen($b['source']);
        });

        $categoryMeta = self::CATEGORY_META;

        return view('admin.languages.translations', compact('locale', 'items', 'categoryMeta'));
    }

    public function updateTranslation(Request $request)
    {
        $request->validate([
            'locale' => 'required|string|in:' . implode(',', config('app.available_locales', ['fr', 'de', 'it', 'en'])),
            'key'    => 'required|string',
            'value'  => 'nullable|string',
        ]);

        $locale = $request->input('locale');
        $key = $request->input('key');
        $value = $request->input('value');

        $translations = $this->loadTranslations($locale);
        $translations[$key] = $value;

        $path = lang_path("{$locale}.json");
        File::put($path, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        Artisan::call('cache:clear');

        return response()->json(['status' => 'ok']);
    }

    private function loadTranslations(string $locale): array
    {
        $path = lang_path("{$locale}.json");

        if (File::exists($path)) {
            return json_decode(File::get($path), true) ?? [];
        }

        return [];
    }

    private function buildKeyContextMap(): array
    {
        $map = [];

        foreach (self::FILE_MAP as $relativePath => $meta) {
            $fullPath = base_path($relativePath);
            if (! File::exists($fullPath)) {
                continue;
            }

            $content = File::get($fullPath);
            preg_match_all("/__\(['\"](.+?)['\"]\)/s", $content, $matches);

            foreach ($matches[1] as $rawKey) {
                if (str_contains($rawKey, '$')) {
                    continue;
                }
                $key = str_replace("\\'", "'", $rawKey);

                if (! isset($map[$key])) {
                    $map[$key] = [
                        'categories' => [],
                        'locations'  => [],
                        'types'      => [],
                    ];
                }
                if (! in_array($meta['category'], $map[$key]['categories'])) {
                    $map[$key]['categories'][] = $meta['category'];
                }
                if (! in_array($meta['location'], $map[$key]['locations'])) {
                    $map[$key]['locations'][] = $meta['location'];
                }
                if (! in_array($meta['type'], $map[$key]['types'])) {
                    $map[$key]['types'][] = $meta['type'];
                }
            }
        }

        return $map;
    }
}
