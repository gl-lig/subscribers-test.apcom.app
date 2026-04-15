<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class AdminLanguageController extends Controller
{
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

        $items = [];
        foreach ($frTranslations as $key => $value) {
            $items[$key] = [
                'source' => $value,
                'translation' => $translations[$key] ?? '',
            ];
        }

        return view('admin.languages.translations', compact('locale', 'items'));
    }

    public function updateTranslation(Request $request)
    {
        $request->validate([
            'locale' => 'required|string|in:' . implode(',', config('app.available_locales', ['fr', 'de', 'it', 'en'])),
            'key' => 'required|string|max:255',
            'value' => 'nullable|string',
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
}
