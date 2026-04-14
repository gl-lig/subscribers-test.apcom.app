@extends('layouts.admin')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-batid-marine">Abonnés</h1>
    <a href="{{ route('admin.export', 'subscribers') }}" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">Export CSV</a>
</div>

<div class="mb-4">
    <form method="GET" class="flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par bat-ID ou téléphone..."
               class="flex-1 rounded-lg border-gray-300 text-sm focus:border-batid-bleu focus:ring-batid-bleu">
        <button type="submit" class="rounded-lg bg-batid-bleu px-4 py-2 text-sm text-white hover:bg-batid-marine">Rechercher</button>
    </form>
</div>

<div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">bat-ID</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Téléphone</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Abonnement actif</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Créé le</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($subscribers as $subscriber)
            @php $active = $subscriber->orders->firstWhere('status', 'active'); @endphp
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium text-batid-marine">{{ $subscriber->bat_id }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $subscriber->phone }}</td>
                <td class="px-6 py-4 text-sm">
                    @if($active)
                        <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                            {{ $active->subscriptionType->translation('fr')?->name ?? '-' }}
                        </span>
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $subscriber->created_at->format('d.m.Y') }}</td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.subscribers.show', $subscriber) }}" class="text-sm font-medium text-batid-bleu hover:underline">Voir</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-8 text-center text-sm text-gray-400">Aucun abonné trouvé.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $subscribers->withQueryString()->links() }}</div>
@endsection
