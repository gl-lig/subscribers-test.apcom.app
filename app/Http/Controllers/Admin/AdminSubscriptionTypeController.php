<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionType;
use App\Models\SubscriptionTypeTranslation;
use App\Services\AdminLogService;
use Illuminate\Http\Request;

class AdminSubscriptionTypeController extends Controller
{
    public function index()
    {
        $types = SubscriptionType::with('translations')->ordered()->get();
        return view('admin.subscription-types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.subscription-types.form', ['type' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:online,admin_only,inactive',
            'parcelles_count' => 'nullable|integer|min:0',
            'parcelles_unlimited' => 'boolean',
            'alertes_count' => 'required|integer|min:0',
            'stockage_go' => 'nullable|integer|min:0',
            'stockage_unlimited' => 'boolean',
            'cloud_externe' => 'boolean',
            'lot_sauvegarde' => 'boolean',
            'workspace_enabled' => 'boolean',
            'workspace_count' => 'nullable|integer|min:0',
            'workspace_unlimited' => 'boolean',
            'price_chf' => 'nullable|numeric|min:0',
            'is_free' => 'boolean',
            'discount_24_months' => 'nullable|numeric|min:0|max:100',
            'discount_36_months' => 'nullable|numeric|min:0|max:100',
            'translations' => 'required|array',
            'translations.fr.name' => 'required|string|max:255',
        ]);

        $type = SubscriptionType::create(array_merge($validated, [
            'sort_order' => SubscriptionType::max('sort_order') + 1,
            'is_free' => $request->boolean('is_free'),
            'price_chf' => $request->boolean('is_free') ? 0 : $request->input('price_chf', 0),
            'parcelles_unlimited' => $request->boolean('parcelles_unlimited'),
            'stockage_unlimited' => $request->boolean('stockage_unlimited'),
            'cloud_externe' => $request->boolean('cloud_externe'),
            'lot_sauvegarde' => $request->boolean('lot_sauvegarde'),
            'veille_robotisee' => $request->boolean('veille_robotisee'),
            'veille_count' => $request->input('veille_count'),
            'veille_unlimited' => $request->boolean('veille_unlimited'),
            'workspace_enabled' => $request->boolean('workspace_enabled'),
            'workspace_unlimited' => $request->boolean('workspace_unlimited'),
        ]));

        foreach ($request->input('translations', []) as $locale => $trans) {
            if (! empty($trans['name'])) {
                SubscriptionTypeTranslation::create([
                    'subscription_type_id' => $type->id,
                    'locale' => $locale,
                    'name' => $trans['name'],
                    'description' => $trans['description'] ?? null,
                ]);
            }
        }

        AdminLogService::log('create', 'subscription_types', null, $type->toArray());

        return redirect()->route('admin.subscription-types.index')
            ->with('success', 'Type d\'abonnement créé.');
    }

    public function edit(SubscriptionType $subscriptionType)
    {
        $subscriptionType->load('translations');
        return view('admin.subscription-types.form', ['type' => $subscriptionType]);
    }

    public function update(Request $request, SubscriptionType $subscriptionType)
    {
        $before = $subscriptionType->toArray();

        $subscriptionType->update([
            'status' => $request->input('status'),
            'parcelles_count' => $request->input('parcelles_count'),
            'parcelles_unlimited' => $request->boolean('parcelles_unlimited'),
            'alertes_count' => $request->input('alertes_count'),
            'stockage_go' => $request->input('stockage_go'),
            'stockage_unlimited' => $request->boolean('stockage_unlimited'),
            'cloud_externe' => $request->boolean('cloud_externe'),
            'lot_sauvegarde' => $request->boolean('lot_sauvegarde'),
            'veille_robotisee' => $request->boolean('veille_robotisee'),
            'veille_count' => $request->input('veille_count'),
            'veille_unlimited' => $request->boolean('veille_unlimited'),
            'workspace_enabled' => $request->boolean('workspace_enabled'),
            'workspace_count' => $request->input('workspace_count'),
            'workspace_unlimited' => $request->boolean('workspace_unlimited'),
            'is_free' => $request->boolean('is_free'),
            'price_chf' => $request->boolean('is_free') ? 0 : $request->input('price_chf', 0),
            'discount_24_months' => $request->input('discount_24_months', 0),
            'discount_36_months' => $request->input('discount_36_months', 0),
        ]);

        foreach ($request->input('translations', []) as $locale => $trans) {
            SubscriptionTypeTranslation::updateOrCreate(
                ['subscription_type_id' => $subscriptionType->id, 'locale' => $locale],
                ['name' => $trans['name'] ?? '', 'description' => $trans['description'] ?? null]
            );
        }

        AdminLogService::log('update', 'subscription_types', $before, $subscriptionType->fresh()->toArray());

        return redirect()->route('admin.subscription-types.index')
            ->with('success', 'Type d\'abonnement mis à jour.');
    }

    public function destroy(SubscriptionType $subscriptionType)
    {
        AdminLogService::log('delete', 'subscription_types', $subscriptionType->toArray());
        $subscriptionType->delete();

        return redirect()->route('admin.subscription-types.index')
            ->with('success', 'Type d\'abonnement désactivé.');
    }

    public function reorder(Request $request)
    {
        $order = $request->input('order', []);

        foreach ($order as $index => $id) {
            SubscriptionType::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['status' => 'ok']);
    }

    public function setDefault(SubscriptionType $subscriptionType)
    {
        SubscriptionType::where('is_default', true)->update(['is_default' => false]);
        $subscriptionType->update(['is_default' => true]);

        AdminLogService::log('update', 'subscription_types', null, [
            'action' => 'set_default',
            'type_id' => $subscriptionType->id,
        ]);

        return redirect()->route('admin.subscription-types.index')
            ->with('success', 'Type par défaut défini : ' . ($subscriptionType->translation('fr')?->name ?? '-'));
    }
}
