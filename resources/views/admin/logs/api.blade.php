@extends('layouts.admin')
@section('content')
<h1 class="mb-6 text-2xl font-bold text-batid-marine">Journal API</h1>
<div class="mb-4 flex gap-3">
    <form method="GET" class="flex gap-3">
        <select name="event" class="rounded-lg border-gray-300 text-sm">
            <option value="">Tous les événements</option>
            <option value="subscription_activated" {{ request('event') === 'subscription_activated' ? 'selected' : '' }}>Activation</option>
            <option value="subscription_upgraded" {{ request('event') === 'subscription_upgraded' ? 'selected' : '' }}>Upgrade</option>
            <option value="subscription_expiring_soon" {{ request('event') === 'subscription_expiring_soon' ? 'selected' : '' }}>Expiration J-30</option>
            <option value="subscription_expired" {{ request('event') === 'subscription_expired' ? 'selected' : '' }}>Expiré</option>
        </select>
        <select name="status" class="rounded-lg border-gray-300 text-sm">
            <option value="">Tous les statuts</option>
            <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Succès</option>
            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Échec</option>
            <option value="error" {{ request('status') === 'error' ? 'selected' : '' }}>Erreur</option>
        </select>
        <button type="submit" class="rounded-lg bg-batid-bleu px-4 py-2 text-sm text-white">Filtrer</button>
    </form>
</div>
<div class="overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50"><tr>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Date</th>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Direction</th>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Événement</th>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">bat-ID</th>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Statut</th>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Tentatives</th>
            <th class="px-4 py-3"></th>
        </tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($logs as $log)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-500">{{ $log->created_at->format('d.m.Y H:i') }}</td>
                <td class="px-4 py-3 text-sm">{{ $log->direction === 'outgoing' ? '→ Sortant' : '← Entrant' }}</td>
                <td class="px-4 py-3 text-sm">{{ $log->event }}</td>
                <td class="px-4 py-3 text-sm font-medium">{{ $log->bat_id ?? '-' }}</td>
                <td class="px-4 py-3">
                    @if($log->status === 'success')<span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Succès</span>
                    @elseif($log->status === 'failed')<span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">Échec</span>
                    @else<span class="rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-800">{{ $log->status }}</span>@endif
                </td>
                <td class="px-4 py-3 text-sm">{{ $log->attempts }}</td>
                <td class="px-4 py-3 text-right">
                    @if($log->status !== 'success')
                    <form method="POST" action="{{ route('admin.logs.api.replay', $log) }}" class="inline">@csrf
                        <button type="submit" class="text-sm text-yellow-600 hover:underline">Rejouer</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-sm text-gray-400">Aucun log API.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $logs->withQueryString()->links() }}</div>
@endsection
