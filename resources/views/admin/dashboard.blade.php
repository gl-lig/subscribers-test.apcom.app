@extends('layouts.admin')

@section('content')
<h1 class="mb-6 text-2xl font-bold text-batid-marine">Tableau de bord</h1>

<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
    <!-- Abonnements actifs -->
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <div class="text-sm font-medium text-gray-500">Abonnements actifs</div>
        <div class="mt-2 text-3xl font-bold text-batid-marine">{{ $activeSubscriptions }}</div>
    </div>

    <!-- Commandes du jour -->
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <div class="text-sm font-medium text-gray-500">Commandes du jour</div>
        <div class="mt-2 text-3xl font-bold text-batid-bleu">{{ $todayCount }}</div>
        <div class="mt-1 text-sm text-gray-400">CHF {{ number_format($todayAmount, 2, '.', "'") }}</div>
    </div>

    <!-- Commandes du mois -->
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <div class="text-sm font-medium text-gray-500">Commandes du mois</div>
        <div class="mt-2 text-3xl font-bold text-batid-bleu">{{ $monthCount }}</div>
        <div class="mt-1 text-sm text-gray-400">CHF {{ number_format($monthAmount, 2, '.', "'") }}</div>
    </div>

    <!-- Expirent dans 30 jours -->
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <div class="text-sm font-medium text-gray-500">Expirent dans 30 jours</div>
        <div class="mt-2 text-3xl font-bold text-yellow-600">{{ $expiringIn30Days }}</div>
    </div>

    <!-- Expirés non renouvelés -->
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <div class="text-sm font-medium text-gray-500">Expirés non renouvelés</div>
        <div class="mt-2 text-3xl font-bold text-red-600">{{ $expiredNotRenewed }}</div>
    </div>

    <!-- Alertes système -->
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100 {{ $failedJobs > 0 ? 'ring-red-200 bg-red-50' : '' }}">
        <div class="text-sm font-medium text-gray-500">Alertes système</div>
        <div class="mt-2 text-3xl font-bold {{ $failedJobs > 0 ? 'text-red-600' : 'text-green-600' }}">{{ $failedJobs }}</div>
        <div class="mt-1 text-sm text-gray-400">{{ $failedJobs > 0 ? 'jobs en échec' : 'Tout est normal' }}</div>
    </div>
</div>
@endsection
