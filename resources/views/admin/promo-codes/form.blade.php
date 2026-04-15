@extends('layouts.admin')

@section('content')
<div class="mb-6"><a href="{{ route('admin.promo-codes.index') }}" class="text-sm text-batid-bleu hover:underline">&larr; Retour</a></div>
<h1 class="mb-6 text-2xl font-bold text-batid-marine">{{ $promoCode ? 'Modifier' : 'Nouveau' }} code promo</h1>

<form method="POST" action="{{ $promoCode ? route('admin.promo-codes.update', $promoCode) : route('admin.promo-codes.store') }}" class="space-y-6">
    @csrf
    @if($promoCode) @method('PUT') @endif

    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <div class="grid gap-4 sm:grid-cols-2">
            <div><label class="mb-1 block text-sm font-medium text-gray-700">Code</label><input type="text" name="code" value="{{ old('code', $promoCode?->code) }}" required class="w-full rounded-lg border-gray-300 text-sm font-mono uppercase"></div>
            <div><label class="mb-1 block text-sm font-medium text-gray-700">Titre</label><input type="text" name="title" value="{{ old('title', $promoCode?->title) }}" required class="w-full rounded-lg border-gray-300 text-sm"></div>
            <div class="sm:col-span-2"><label class="mb-1 block text-sm font-medium text-gray-700">Description</label><textarea name="description" rows="2" class="w-full rounded-lg border-gray-300 text-sm">{{ old('description', $promoCode?->description) }}</textarea></div>
            <div><label class="mb-1 block text-sm font-medium text-gray-700">Réduction (%)</label><input type="number" step="0.01" name="discount_pct" value="{{ old('discount_pct', $promoCode?->discount_pct) }}" required class="w-full rounded-lg border-gray-300 text-sm"></div>
            <div><label class="mb-1 block text-sm font-medium text-gray-700">Utilisations par utilisateur</label><input type="number" name="usage_limit_per_user" value="{{ old('usage_limit_per_user', $promoCode?->usage_limit_per_user ?? 1) }}" required class="w-full rounded-lg border-gray-300 text-sm"></div>
            <div><label class="mb-1 block text-sm font-medium text-gray-700">Début de validité</label><input type="datetime-local" name="valid_from" value="{{ old('valid_from', $promoCode?->valid_from?->format('Y-m-d\TH:i')) }}" required class="w-full rounded-lg border-gray-300 text-sm"></div>
            <div><label class="mb-1 block text-sm font-medium text-gray-700">Fin de validité</label><input type="datetime-local" name="valid_until" value="{{ old('valid_until', $promoCode?->valid_until?->format('Y-m-d\TH:i')) }}" class="w-full rounded-lg border-gray-300 text-sm"><p class="mt-1 text-xs text-gray-400">Laisser vide = durée indéterminée</p></div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Périmètre</label>
                <select name="scope" x-data x-model="$el.value" class="w-full rounded-lg border-gray-300 text-sm">
                    <option value="all" {{ old('scope', $promoCode?->scope) === 'all' ? 'selected' : '' }}>Tous les utilisateurs</option>
                    <option value="specific_user" {{ old('scope', $promoCode?->scope) === 'specific_user' ? 'selected' : '' }}>Utilisateur spécifique</option>
                    <option value="group" {{ old('scope', $promoCode?->scope) === 'group' ? 'selected' : '' }}>Groupe</option>
                </select>
            </div>
            <div><label class="mb-1 block text-sm font-medium text-gray-700">bat-ID spécifique</label><input type="text" name="bat_id_specific" value="{{ old('bat_id_specific', $promoCode?->bat_id_specific) }}" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Si périmètre = utilisateur"></div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Groupe</label>
                <select name="user_group_id" class="w-full rounded-lg border-gray-300 text-sm">
                    <option value="">— Aucun —</option>
                    @foreach($groups as $group)<option value="{{ $group->id }}" {{ old('user_group_id', $promoCode?->user_group_id) == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>@endforeach
                </select>
            </div>
            <div><label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $promoCode?->is_active ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-batid-bleu"> Actif</label></div>
        </div>
    </div>

    <div class="flex justify-end gap-3">
        @if($promoCode)
        <form method="POST" action="{{ route('admin.promo-codes.destroy', $promoCode) }}" onsubmit="return confirm('Supprimer ce code ?')">@csrf @method('DELETE')<button type="submit" class="rounded-lg bg-red-100 px-4 py-2 text-sm text-red-700 hover:bg-red-200"><i class="fa-solid fa-trash mr-1"></i> Supprimer</button></form>
        @endif
        <button type="submit" class="rounded-lg bg-batid-bleu px-6 py-3 text-sm font-semibold text-white hover:bg-batid-marine">{{ $promoCode ? 'Mettre à jour' : 'Créer' }}</button>
    </div>
</form>
@endsection
