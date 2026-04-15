@extends('layouts.admin')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('admin.orders.index') }}" class="text-sm text-batid-bleu hover:underline">&larr; Retour</a>
    <form method="POST" action="{{ route('admin.orders.replay', $order) }}">
        @csrf
        <button type="submit" class="rounded-lg bg-yellow-500 px-4 py-2 text-sm font-medium text-white hover:bg-yellow-600">Rejouer notification</button>
    </form>
</div>

<div class="grid gap-6 lg:grid-cols-2">
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h3 class="mb-4 font-semibold text-batid-marine">Commande {{ $order->order_number }}</h3>
        <dl class="space-y-2 text-sm">
            <div class="flex justify-between"><dt class="text-gray-500">Statut</dt><dd>
                @if($order->status === 'active')<span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Actif</span>
                @elseif($order->status === 'expired')<span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">Expiré</span>
                @else<span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">Remplacé</span>@endif
            </dd></div>
            @if($order->status === 'replaced' && $order->replacedByOrder)
            <div class="flex justify-between rounded-lg bg-amber-50 px-3 py-2 ring-1 ring-amber-200">
                <dt class="text-xs text-amber-700">Remplacée par</dt>
                <dd class="text-xs font-medium"><a href="{{ route('admin.orders.show', $order->replacedByOrder) }}" class="text-batid-bleu hover:underline">{{ $order->replacedByOrder->order_number }}</a> — {{ $order->replacedByOrder->subscriptionType?->translation('fr')?->name }} ({{ $order->replacedByOrder->concluded_at?->format('d.m.Y') }})</dd>
            </div>
            @endif
            @if($order->replacesOrder)
            <div class="flex justify-between rounded-lg bg-blue-50 px-3 py-2 ring-1 ring-blue-200">
                <dt class="text-xs text-blue-700">Remplace</dt>
                <dd class="text-xs font-medium"><a href="{{ route('admin.orders.show', $order->replacesOrder) }}" class="text-batid-bleu hover:underline">{{ $order->replacesOrder->order_number }}</a> — {{ $order->replacesOrder->subscriptionType?->translation('fr')?->name }} ({{ $order->replacesOrder->concluded_at?->format('d.m.Y') }})</dd>
            </div>
            @endif
            <div class="flex justify-between"><dt class="text-gray-500">bat-ID</dt><dd class="font-medium">{{ $order->subscriber->bat_id ?? '-' }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Type</dt><dd>{{ $order->subscriptionType?->translation('fr')?->name ?? '-' }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Durée</dt><dd>{{ $order->duration_months > 0 ? $order->duration_months . ' mois' : __('Illimitée') }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Début</dt><dd>{{ $order->starts_at->format('d.m.Y') }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Fin</dt><dd>{{ $order->expires_at ? $order->expires_at->format('d.m.Y') : __('Illimité') }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Conclu le</dt><dd>{{ $order->concluded_at?->format('d.m.Y H:i') ?? '-' }}</dd></div>
        </dl>
    </div>

    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h3 class="mb-4 font-semibold text-batid-marine">Paiement</h3>
        <dl class="space-y-2 text-sm">
            <div class="flex justify-between"><dt class="text-gray-500">Prix catalogue</dt><dd>CHF {{ $order->price_catalogue }}</dd></div>
            @if($order->discount_duration_pct > 0)
            <div class="flex justify-between"><dt class="text-gray-500">Rabais durée ({{ $order->discount_duration_pct }}%)</dt><dd class="text-green-600">- CHF {{ number_format($order->price_catalogue * $order->discount_duration_pct / 100, 2) }}</dd></div>
            @endif
            @if($order->discount_promo_pct > 0)
            <div class="flex justify-between"><dt class="text-gray-500">Promo {{ $order->promo_code }} ({{ $order->discount_promo_pct }}%)</dt><dd class="text-green-600">remise appliquée</dd></div>
            @endif
            @if($order->prorata_deducted > 0)
            <div class="flex justify-between"><dt class="text-gray-500">Prorata déduit</dt><dd class="text-green-600">- CHF {{ $order->prorata_deducted }}</dd></div>
            @endif
            <div class="flex justify-between border-t pt-2 font-bold"><dt>Total payé</dt><dd>CHF {{ $order->price_paid }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Moyen</dt><dd>{{ $order->payment_method ?? '-' }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Transaction Datatrans</dt><dd class="font-mono text-xs">{{ $order->datatrans_transaction_id ?? '-' }}</dd></div>
            @if($order->invoice_url)
            <div class="flex justify-between"><dt class="text-gray-500">Facture</dt><dd><a href="{{ $order->invoice_url }}" target="_blank" class="text-batid-bleu hover:underline">Voir PDF</a></dd></div>
            @endif
        </dl>
    </div>
</div>

@if($order->metadata)
<div class="mt-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
    <h3 class="mb-4 font-semibold text-batid-marine">Métadonnées</h3>
    <div class="grid gap-2 text-sm sm:grid-cols-3">
        <div><span class="text-gray-500">IP:</span> {{ $order->metadata->ip_address }}</div>
        <div><span class="text-gray-500">Navigateur:</span> {{ $order->metadata->browser ?? '-' }}</div>
        <div><span class="text-gray-500">OS:</span> {{ $order->metadata->os ?? '-' }}</div>
        <div><span class="text-gray-500">Langue:</span> {{ $order->metadata->language ?? '-' }}</div>
        <div><span class="text-gray-500">Résolution:</span> {{ $order->metadata->screen_resolution ?? '-' }}</div>
        <div><span class="text-gray-500">Timezone:</span> {{ $order->metadata->timezone ?? '-' }}</div>
    </div>
</div>
@endif

@if($order->paymentLogs->count())
<div class="mt-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
    <h3 class="mb-4 font-semibold text-batid-marine">Logs paiement</h3>
    <div class="space-y-2">
        @foreach($order->paymentLogs as $log)
        <div class="flex items-center justify-between rounded-lg bg-gray-50 px-4 py-2 text-sm">
            <span class="font-medium">{{ $log->event }}</span>
            <span class="font-mono text-xs text-gray-500">{{ $log->received_at?->format('d.m.Y H:i:s') }}</span>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection
