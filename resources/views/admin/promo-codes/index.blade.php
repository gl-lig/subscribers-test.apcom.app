@extends('layouts.admin')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-batid-marine">Codes promo</h1>
    <a href="{{ route('admin.promo-codes.create') }}" class="rounded-lg bg-batid-bleu px-4 py-2 text-sm font-medium text-white hover:bg-batid-marine">Nouveau code</a>
</div>

<div class="overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Code</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Titre</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Réduction</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Validité</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Périmètre</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Statut</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($promoCodes as $promo)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono text-sm font-bold text-batid-marine">{{ $promo->code }}</td>
                <td class="px-4 py-3 text-sm">{{ $promo->title }}</td>
                <td class="px-4 py-3 text-sm font-medium text-green-600">-{{ $promo->discount_pct }}%</td>
                <td class="px-4 py-3 text-xs text-gray-500">{{ $promo->valid_from->format('d.m.Y') }} → {{ $promo->valid_until?->format('d.m.Y') ?? '∞' }}</td>
                <td class="px-4 py-3 text-sm">{{ $promo->scope === 'all' ? 'Tous' : ($promo->scope === 'specific_user' ? 'Utilisateur' : 'Groupe') }}</td>
                <td class="px-4 py-3">
                    @if($promo->is_active)<span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Actif</span>
                    @else<span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">Inactif</span>@endif
                </td>
                <td class="px-4 py-3 text-right">
                    <a href="{{ route('admin.promo-codes.edit', $promo) }}" class="text-sm text-batid-bleu hover:underline">Modifier</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-sm text-gray-400">Aucun code promo.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $promoCodes->links() }}</div>
@endsection
