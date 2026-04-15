@extends('layouts.admin')

@section('content')
@if(session('success'))
<div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
@endif

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
                <td class="px-4 py-3 text-sm">{{ $order->subscriptionType?->translation('fr')?->name ?? '-' }}</td>
                <td class="px-4 py-3 text-sm">{{ $order->duration_months }}m</td>
                <td class="px-4 py-3 text-sm font-medium">CHF {{ $order->price_paid }}</td>
                <td class="px-4 py-3">
                    @if($order->status === 'active')
                        <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Actif</span>
                        @if($order->replacesOrder)
                            <div class="mt-1 text-[10px] text-gray-400">Remplace <a href="{{ route('admin.orders.show', $order->replacesOrder) }}" class="text-batid-bleu hover:underline">{{ $order->replacesOrder->order_number }}</a></div>
                        @endif
                    @elseif($order->status === 'expired')
                        <span class="inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">Expiré</span>
                    @else
                        <span class="inline-flex rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">Remplacé</span>
                        @if($order->replacedByOrder)
                            <div class="mt-1 text-[10px] text-gray-400">Par <a href="{{ route('admin.orders.show', $order->replacedByOrder) }}" class="text-batid-bleu hover:underline">{{ $order->replacedByOrder->order_number }}</a> ({{ $order->replacedByOrder->subscriptionType?->translation('fr')?->name }}, {{ $order->replacedByOrder->concluded_at?->format('d.m.Y') }})</div>
                        @endif
                    @endif
                </td>
                <td class="px-4 py-3 text-xs text-gray-500">{{ $order->starts_at->format('d.m.Y') }} → {{ $order->expires_at ? $order->expires_at->format('d.m.Y') : __('Illimité') }}</td>
                <td class="px-4 py-3 text-right flex items-center justify-end gap-3">
                    <a href="{{ route('admin.orders.show', $order) }}" class="text-batid-bleu hover:text-batid-marine" title="Voir"><i class="fa-solid fa-eye"></i></a>
                    <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('Supprimer la commande {{ $order->order_number }} ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700" title="Supprimer"><i class="fa-solid fa-trash"></i></button>
                    </form>
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
