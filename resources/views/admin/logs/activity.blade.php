@extends('layouts.admin')
@section('content')
<h1 class="mb-6 text-2xl font-bold text-batid-marine">Journal d'activité admin</h1>
<div class="mb-4">
    <form method="GET" class="flex gap-3">
        <select name="module" class="rounded-lg border-gray-300 text-sm">
            <option value="">Tous les modules</option>
            @foreach(['auth','admins','subscription_types','orders','promo_codes','user_groups','settings'] as $mod)
            <option value="{{ $mod }}" {{ request('module') === $mod ? 'selected' : '' }}>{{ $mod }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-lg bg-batid-bleu px-4 py-2 text-sm text-white">Filtrer</button>
    </form>
</div>
<div class="overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50"><tr>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Date</th>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Admin</th>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Action</th>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Module</th>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">IP</th>
        </tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($logs as $log)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-500">{{ $log->created_at->format('d.m.Y H:i:s') }}</td>
                <td class="px-4 py-3 text-sm">{{ $log->admin?->full_name ?? 'Système' }}</td>
                <td class="px-4 py-3 text-sm font-medium">{{ $log->action }}</td>
                <td class="px-4 py-3"><span class="rounded bg-gray-100 px-2 py-0.5 text-xs">{{ $log->module }}</span></td>
                <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $log->ip_address }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-sm text-gray-400">Aucune activité.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $logs->withQueryString()->links() }}</div>
@endsection
