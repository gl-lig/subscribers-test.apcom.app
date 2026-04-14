@extends('layouts.admin')
@section('content')
<div class="mb-6"><a href="{{ route('admin.admins.index') }}" class="text-sm text-batid-bleu hover:underline">&larr; Retour</a></div>
<h1 class="mb-6 text-2xl font-bold text-batid-marine">{{ $admin ? 'Modifier' : 'Nouvel' }} administrateur</h1>

<form method="POST" action="{{ $admin ? route('admin.admins.update', $admin) : route('admin.admins.store') }}" class="space-y-6">
    @csrf
    @if($admin) @method('PUT') @endif
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <div class="grid gap-4 sm:grid-cols-2">
            <div><label class="mb-1 block text-sm font-medium text-gray-700">Prénom</label><input type="text" name="first_name" value="{{ old('first_name', $admin?->first_name) }}" required class="w-full rounded-lg border-gray-300 text-sm"></div>
            <div><label class="mb-1 block text-sm font-medium text-gray-700">Nom</label><input type="text" name="last_name" value="{{ old('last_name', $admin?->last_name) }}" required class="w-full rounded-lg border-gray-300 text-sm"></div>
            <div><label class="mb-1 block text-sm font-medium text-gray-700">Email</label><input type="email" name="email" value="{{ old('email', $admin?->email) }}" required class="w-full rounded-lg border-gray-300 text-sm"></div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Rôle</label>
                <select name="role" class="w-full rounded-lg border-gray-300 text-sm">
                    <option value="admin" {{ old('role', $admin?->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="super_admin" {{ old('role', $admin?->role) === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                </select>
            </div>
            <div><label class="mb-1 block text-sm font-medium text-gray-700">Mot de passe {{ $admin ? '(laisser vide pour garder)' : '' }}</label><input type="password" name="password" {{ $admin ? '' : 'required' }} class="w-full rounded-lg border-gray-300 text-sm"></div>
            <div><label class="mb-1 block text-sm font-medium text-gray-700">Confirmer le mot de passe</label><input type="password" name="password_confirmation" class="w-full rounded-lg border-gray-300 text-sm"></div>
            @if($admin)
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Statut</label>
                <select name="status" class="w-full rounded-lg border-gray-300 text-sm">
                    <option value="active" {{ $admin->status === 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="inactive" {{ $admin->status === 'inactive' ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>
            @endif
            <div class="flex items-end"><label class="flex items-center gap-2 text-sm"><input type="checkbox" name="notify_new_order" value="1" {{ old('notify_new_order', $admin?->notify_new_order ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-batid-bleu"> Notifier nouvelle commande</label></div>
        </div>
    </div>
    <div class="flex justify-end"><button type="submit" class="rounded-lg bg-batid-bleu px-6 py-3 text-sm font-semibold text-white hover:bg-batid-marine">{{ $admin ? 'Mettre à jour' : 'Créer' }}</button></div>
</form>
@endsection
