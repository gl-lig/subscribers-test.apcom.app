@extends('layouts.admin')
@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-batid-marine">Journal des paiements</h1>
    <a href="{{ route('admin.export', 'payments') }}" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">Export CSV</a>
</div>
<div class="mb-4 flex gap-3">
    <form method="GET" class="flex flex-1 gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Transaction ID ou N° commande..." class="flex-1 rounded-lg border-gray-300 text-sm">
        <select name="event" class="rounded-lg border-gray-300 text-sm">
            <option value="">Tous les événements</option>
            <option value="authorized" {{ request('event') === 'authorized' ? 'selected' : '' }}>Autorisé</option>
            <option value="settled" {{ request('event') === 'settled' ? 'selected' : '' }}>Réglé</option>
            <option value="declined" {{ request('event') === 'declined' ? 'selected' : '' }}>Refusé</option>
            <option value="error" {{ request('event') === 'error' ? 'selected' : '' }}>Erreur</option>
        </select>
        <button type="submit" class="rounded-lg bg-batid-bleu px-4 py-2 text-sm text-white">Filtrer</button>
    </form>
</div>
<div class="overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50"><tr>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">ID Transaction</th>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">N° Commande</th>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Événement</th>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Date</th>
            <th class="px-4 py-3"></th>
        </tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($payments as $log)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono text-xs">{{ $log->datatrans_transaction_id ?? '-' }}</td>
                <td class="px-4 py-3 text-sm">{{ $log->order->order_number ?? '-' }}</td>
                <td class="px-4 py-3">
                    @if(in_array($log->event, ['authorized','settled']))<span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">{{ $log->event }}</span>
                    @elseif($log->event === 'declined')<span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">{{ $log->event }}</span>
                    @else<span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">{{ $log->event }}</span>@endif
                </td>
                <td class="px-4 py-3 text-sm text-gray-500">{{ $log->received_at?->format('d.m.Y H:i:s') }}</td>
                <td class="px-4 py-3 text-right"><a href="{{ route('admin.payments.show', $log) }}" class="text-batid-bleu hover:text-batid-marine" title="Détail"><i class="fa-solid fa-eye"></i></a></td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-sm text-gray-400">Aucun paiement.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $payments->withQueryString()->links() }}</div>
@endsection
