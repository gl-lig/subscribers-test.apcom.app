@extends('layouts.admin')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-batid-marine">Types d'abonnement</h1>
    <a href="{{ route('admin.subscription-types.create') }}" class="rounded-lg bg-batid-bleu px-4 py-2 text-sm font-medium text-white hover:bg-batid-marine">Nouveau type</a>
</div>

<div class="space-y-3">
    @foreach($types as $type)
    <div class="flex items-center justify-between rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
        <div class="flex items-center gap-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-batid-marine text-lg font-bold text-batid-vert">{{ $type->sort_order }}</div>
            <div>
                <h3 class="font-semibold text-batid-marine">{{ $type->translation('fr')?->name ?? 'Sans nom' }}</h3>
                <p class="text-sm text-gray-500">{{ $type->is_free ? 'Gratuit' : 'CHF ' . $type->price_chf . '/an' }}
                    @if($type->discount_24_months > 0)
                        <span class="ml-2 text-green-600">-{{ $type->discount_24_months }}% sur 24m</span>
                    @endif
                    @if($type->discount_36_months > 0)
                        <span class="ml-2 text-green-600">-{{ $type->discount_36_months }}% sur 36m</span>
                    @endif
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @if($type->status === 'online')
                <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">En ligne</span>
            @elseif($type->status === 'admin_only')
                <span class="inline-flex rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">Admin</span>
            @else
                <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">Inactif</span>
            @endif
            <a href="{{ route('admin.subscription-types.edit', $type) }}" class="text-batid-bleu hover:text-batid-marine" title="Modifier"><i class="fa-solid fa-pen"></i></a>
            <form method="POST" action="{{ route('admin.subscription-types.destroy', $type) }}" onsubmit="return confirm('Confirmer la suppression ?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-700" title="Supprimer"><i class="fa-solid fa-trash"></i></button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endsection
