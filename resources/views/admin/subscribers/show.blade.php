@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.subscribers.index') }}" class="text-sm text-batid-bleu hover:underline">&larr; Retour aux abonnés</a>
</div>

<div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
    <h2 class="mb-4 text-lg font-bold text-batid-marine">Abonné</h2>
    <div class="grid gap-4 sm:grid-cols-3">
        <div><span class="text-xs text-gray-500">bat-ID</span><p class="font-medium">{{ $subscriber->bat_id }}</p></div>
        <div><span class="text-xs text-gray-500">Téléphone</span><p class="font-medium">{{ $subscriber->phone }}</p></div>
        <div><span class="text-xs text-gray-500">Inscrit le</span><p class="font-medium">{{ $subscriber->created_at->format('d.m.Y H:i') }}</p></div>
    </div>
</div>

<h3 class="mb-4 text-lg font-bold text-batid-marine">Historique des commandes</h3>
<div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">N° Commande</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Type</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Durée</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Montant</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Statut</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Dates</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($subscriber->orders as $order)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm font-medium">{{ $order->order_number }}</td>
                <td class="px-4 py-3 text-sm">{{ $order->subscriptionType->translation('fr')?->name ?? '-' }}</td>
                <td class="px-4 py-3 text-sm">{{ $order->duration_months }} mois</td>
                <td class="px-4 py-3 text-sm font-medium">CHF {{ $order->price_paid }}</td>
                <td class="px-4 py-3">
                    @if($order->status === 'active')
                        <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Actif</span>
                    @elseif($order->status === 'expired')
                        <span class="inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">Expiré</span>
                    @else
                        <span class="inline-flex rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800">Remplacé</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-xs text-gray-500">{{ $order->starts_at->format('d.m.Y') }} → {{ $order->expires_at->format('d.m.Y') }}</td>
                <td class="px-4 py-3 text-right">
                    <a href="{{ route('admin.orders.show', $order) }}" class="text-sm text-batid-bleu hover:underline">Détail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
