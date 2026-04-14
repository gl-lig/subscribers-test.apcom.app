@extends('layouts.admin')

@section('content')
<div class="mb-6"><a href="{{ route('admin.user-groups.index') }}" class="text-sm text-batid-bleu hover:underline">&larr; Retour</a></div>
<h1 class="mb-6 text-2xl font-bold text-batid-marine">{{ $group ? 'Modifier' : 'Nouveau' }} groupe</h1>

<form method="POST" action="{{ $group ? route('admin.user-groups.update', $group) : route('admin.user-groups.store') }}" class="space-y-6">
    @csrf
    @if($group) @method('PUT') @endif
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <div class="space-y-4">
            <div><label class="mb-1 block text-sm font-medium text-gray-700">Nom</label><input type="text" name="name" value="{{ old('name', $group?->name) }}" required class="w-full rounded-lg border-gray-300 text-sm"></div>
            <div><label class="mb-1 block text-sm font-medium text-gray-700">Description</label><textarea name="description" rows="2" class="w-full rounded-lg border-gray-300 text-sm">{{ old('description', $group?->description) }}</textarea></div>
        </div>
    </div>
    <button type="submit" class="rounded-lg bg-batid-bleu px-6 py-3 text-sm font-semibold text-white hover:bg-batid-marine">{{ $group ? 'Mettre à jour' : 'Créer' }}</button>
</form>

@if($group && isset($members))
<div class="mt-8 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
    <h3 class="mb-4 font-semibold text-batid-marine">Membres</h3>
    <form method="POST" action="{{ route('admin.user-groups.members.add', $group) }}" class="mb-4 flex gap-3">
        @csrf
        <input type="text" name="bat_id" placeholder="bat-ID du membre" required class="flex-1 rounded-lg border-gray-300 text-sm">
        <button type="submit" class="rounded-lg bg-batid-bleu px-4 py-2 text-sm text-white hover:bg-batid-marine">Ajouter</button>
    </form>
    <div class="space-y-1">
        @foreach($members as $member)
        <div class="flex items-center justify-between rounded-lg bg-gray-50 px-4 py-2 text-sm">
            <span class="font-medium">{{ $member->bat_id }}</span>
            <form method="POST" action="{{ route('admin.user-groups.members.remove', [$group, $member->bat_id]) }}">@csrf @method('DELETE')<button class="text-red-600 hover:underline text-xs">Retirer</button></form>
        </div>
        @endforeach
    </div>
    <div class="mt-3">{{ $members->links() }}</div>
</div>
@endif
@endsection
