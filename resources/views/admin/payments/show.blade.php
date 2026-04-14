@extends('layouts.admin')
@section('content')
<div class="mb-6"><a href="{{ route('admin.payments.index') }}" class="text-sm text-batid-bleu hover:underline">&larr; Retour</a></div>
<h1 class="mb-6 text-2xl font-bold text-batid-marine">Détail paiement</h1>
<div class="grid gap-6 lg:grid-cols-2">
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h3 class="mb-4 font-semibold text-batid-marine">Information</h3>
        <dl class="space-y-2 text-sm">
            <div class="flex justify-between"><dt class="text-gray-500">Transaction ID</dt><dd class="font-mono">{{ $paymentLog->datatrans_transaction_id }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Événement</dt><dd>{{ $paymentLog->event }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Commande</dt><dd>{{ $paymentLog->order->order_number ?? '-' }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">bat-ID</dt><dd>{{ $paymentLog->order->subscriber->bat_id ?? '-' }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Date</dt><dd>{{ $paymentLog->received_at?->format('d.m.Y H:i:s') }}</dd></div>
        </dl>
    </div>
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h3 class="mb-4 font-semibold text-batid-marine">Payload brut</h3>
        <pre class="max-h-96 overflow-auto rounded-lg bg-gray-900 p-4 text-xs text-green-400">{{ json_encode($paymentLog->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    </div>
</div>
@endsection
