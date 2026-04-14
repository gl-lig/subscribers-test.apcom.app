@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.subscription-types.index') }}" class="text-sm text-batid-bleu hover:underline">&larr; Retour</a>
</div>

<h1 class="mb-6 text-2xl font-bold text-batid-marine">{{ $type ? 'Modifier' : 'Nouveau' }} type d'abonnement</h1>

<form method="POST" action="{{ $type ? route('admin.subscription-types.update', $type) : route('admin.subscription-types.store') }}" class="space-y-6">
    @csrf
    @if($type) @method('PUT') @endif

    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h3 class="mb-4 font-semibold text-batid-marine">Paramètres</h3>
        <div class="grid gap-4 sm:grid-cols-3">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Statut</label>
                <select name="status" class="w-full rounded-lg border-gray-300 text-sm">
                    <option value="online" {{ old('status', $type?->status) === 'online' ? 'selected' : '' }}>En ligne</option>
                    <option value="admin_only" {{ old('status', $type?->status) === 'admin_only' ? 'selected' : '' }}>Admin seulement</option>
                    <option value="inactive" {{ old('status', $type?->status) === 'inactive' ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Prix CHF / an</label>
                <input type="number" step="0.01" name="price_chf" value="{{ old('price_chf', $type?->price_chf) }}" required class="w-full rounded-lg border-gray-300 text-sm">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Rabais 36 mois (%)</label>
                <input type="number" step="0.01" name="discount_36_months" value="{{ old('discount_36_months', $type?->discount_36_months ?? 0) }}" class="w-full rounded-lg border-gray-300 text-sm">
            </div>
        </div>
    </div>

    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h3 class="mb-4 font-semibold text-batid-marine">Caractéristiques</h3>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Parcelles</label>
                <input type="number" name="parcelles_count" value="{{ old('parcelles_count', $type?->parcelles_count) }}" placeholder="Laisser vide = illimité" class="w-full rounded-lg border-gray-300 text-sm">
                <label class="mt-2 flex items-center gap-2 text-sm"><input type="checkbox" name="parcelles_unlimited" value="1" {{ old('parcelles_unlimited', $type?->parcelles_unlimited) ? 'checked' : '' }} class="rounded border-gray-300 text-batid-bleu"> Illimité</label>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Alertes</label>
                <input type="number" name="alertes_count" value="{{ old('alertes_count', $type?->alertes_count ?? 0) }}" required class="w-full rounded-lg border-gray-300 text-sm">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Stockage (Go)</label>
                <input type="number" name="stockage_go" value="{{ old('stockage_go', $type?->stockage_go) }}" placeholder="Laisser vide = illimité" class="w-full rounded-lg border-gray-300 text-sm">
                <label class="mt-2 flex items-center gap-2 text-sm"><input type="checkbox" name="stockage_unlimited" value="1" {{ old('stockage_unlimited', $type?->stockage_unlimited) ? 'checked' : '' }} class="rounded border-gray-300 text-batid-bleu"> Illimité</label>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Workspace</label>
                <input type="number" name="workspace_count" value="{{ old('workspace_count', $type?->workspace_count) }}" placeholder="Laisser vide = illimité" class="w-full rounded-lg border-gray-300 text-sm">
                <label class="mt-2 flex items-center gap-2 text-sm"><input type="checkbox" name="workspace_enabled" value="1" {{ old('workspace_enabled', $type?->workspace_enabled) ? 'checked' : '' }} class="rounded border-gray-300 text-batid-bleu"> Activé</label>
                <label class="mt-1 flex items-center gap-2 text-sm"><input type="checkbox" name="workspace_unlimited" value="1" {{ old('workspace_unlimited', $type?->workspace_unlimited) ? 'checked' : '' }} class="rounded border-gray-300 text-batid-bleu"> Illimité</label>
            </div>
            <div class="space-y-2">
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="cloud_externe" value="1" {{ old('cloud_externe', $type?->cloud_externe) ? 'checked' : '' }} class="rounded border-gray-300 text-batid-bleu"> Cloud externe</label>
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="lot_sauvegarde" value="1" {{ old('lot_sauvegarde', $type?->lot_sauvegarde) ? 'checked' : '' }} class="rounded border-gray-300 text-batid-bleu"> Lot de sauvegarde</label>
            </div>
        </div>
    </div>

    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h3 class="mb-4 font-semibold text-batid-marine">Traductions</h3>
        <div x-data="{ tab: 'fr' }">
            <div class="mb-4 flex gap-2">
                @foreach(['fr', 'de', 'it', 'en'] as $loc)
                <button type="button" @click="tab = '{{ $loc }}'" :class="tab === '{{ $loc }}' ? 'bg-batid-bleu text-white' : 'bg-gray-100 text-gray-600'" class="rounded-lg px-3 py-1.5 text-sm font-medium transition">{{ strtoupper($loc) }}</button>
                @endforeach
            </div>
            @foreach(['fr', 'de', 'it', 'en'] as $loc)
            @php $trans = $type?->translations->firstWhere('locale', $loc); @endphp
            <div x-show="tab === '{{ $loc }}'" class="space-y-3">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Nom ({{ strtoupper($loc) }})</label>
                    <input type="text" name="translations[{{ $loc }}][name]" value="{{ old("translations.{$loc}.name", $trans?->name) }}" {{ $loc === 'fr' ? 'required' : '' }} class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Description ({{ strtoupper($loc) }})</label>
                    <textarea name="translations[{{ $loc }}][description]" rows="2" class="w-full rounded-lg border-gray-300 text-sm">{{ old("translations.{$loc}.description", $trans?->description) }}</textarea>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="flex justify-end">
        <button type="submit" class="rounded-lg bg-batid-bleu px-6 py-3 text-sm font-semibold text-white transition hover:bg-batid-marine">
            {{ $type ? 'Mettre à jour' : 'Créer' }}
        </button>
    </div>
</form>
@endsection
