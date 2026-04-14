@extends('layouts.admin')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-batid-marine">Commandes</h1>
    <a href="{{ route('admin.export', 'orders') }}" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">Export CSV</a>
</div>

<div class="mb-4 flex gap-3">
    <form method="GET" class="flex flex-1 gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="N° commande ou bat-ID..."
               class="flex-1 rounded-lg border-gray-300 text-sm focus:border-batid-bleu focus:ring-batid-bleu">
        <select name="status" class="rounded-lg border-gray-300 text-sm">
            <option value="">Tous les statuts</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expiré</option>
            <option value="replaced" {{ request('status') === 'replaced' ? 'selected' : '' }}>Remplacé</option>
        </select>
        <button type="submit" class="rounded-lg bg-batid-bleu px-4 py-2 text-sm text-white hover:bg-batid-marine">Filtrer</button>
    </form>
</div>

<div class="overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">N° Commande</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">bat-ID</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Type</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Durée</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Montant</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Statut</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Dates</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($orders as $order)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm font-medium text-batid-marine">{{ $order->order_number }}</td>
                <td class="px-4 py-3 text-sm">{{ $order->subscriber->bat_id ?? '-' }}</td>
                <td class="px-4 py-3 text-sm">{{ $order->subscriptionType->translation('fr')?->name ?? '-' }}</td>
                <td class="px-4 py-3 text-sm">{{ $order->duration_months }}m</td>
                <td class="px-4 py-3 text-sm font-medium">CHF {{ $order->price_paid }}</td>
                <td class="px-4 py-3">
                    @if($order->status === 'active')
                        <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Actif</span>
                    @elseif($order->status === 'expired')
                        <span class="inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">Expiré</span>
                    @else
                        <span class="inline-flex rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">Remplacé</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-xs text-gray-500">{{ $order->starts_at->format('d.m.Y') }} → {{ $order->expires_at->format('d.m.Y') }}</td>
                <td class="px-4 py-3 text-right">
                    <a href="{{ route('admin.orders.show', $order) }}" class="text-sm text-batid-bleu hover:underline">Voir</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="px-4 py-8 text-center text-sm text-gray-400">Aucune commande.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $orders->withQueryString()->links() }}</div>
@endsection
