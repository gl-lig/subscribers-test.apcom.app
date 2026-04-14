<div class="min-h-screen py-16" style="background: linear-gradient(to top right, #3DFF9E 0%, #38F3A4 6%, #2DD3B6 17%, #1B9FD2 32%, #0050FF 52%, #00004D 84%);">
    <div class="mx-auto max-w-2xl px-4">
        <!-- Checkmark animation -->
        <div class="mb-8 flex justify-center">
            <div class="flex h-24 w-24 items-center justify-center rounded-full bg-white shadow-2xl">
                <svg class="h-14 w-14 text-green-500" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path class="animate-checkmark" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>

        <h1 class="mb-2 text-center text-3xl font-extrabold text-white">{{ __('Merci pour votre confiance !') }}</h1>
        <p class="mb-8 text-center text-lg text-white/70">{{ __('Votre abonnement est maintenant actif') }}</p>

        @if($order)
        @php $trans = $order->subscriptionType->translation($locale); @endphp
        <div class="rounded-2xl bg-white p-8 shadow-2xl">
            <h2 class="mb-6 text-lg font-bold text-batid-marine">{{ __('Détails de votre abonnement') }}</h2>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500">{{ __("Type d'abonnement") }}</dt><dd class="font-semibold">{{ $trans?->name ?? '' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">{{ __('Numéro de commande') }}</dt><dd class="font-mono">{{ $order->order_number }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">{{ __('Durée') }}</dt><dd>{{ $order->duration_months }} {{ __('mois') }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">{{ __('Date de début') }}</dt><dd>{{ $order->starts_at->format('d.m.Y') }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">{{ __('Date de fin') }}</dt><dd>{{ $order->expires_at->format('d.m.Y') }}</dd></div>
                <div class="flex justify-between border-t pt-3"><dt class="text-gray-500">{{ __('Montant payé') }}</dt><dd class="text-lg font-bold text-batid-marine">CHF {{ $order->price_paid }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">{{ __('Moyen de paiement') }}</dt><dd>{{ ucfirst($order->payment_method ?? '-') }}</dd></div>
            </dl>

            <div class="mt-6 rounded-lg bg-blue-50 p-4 text-center text-sm text-blue-700">
                {{ __('Votre facture a été transmise à bat-id.ch') }}
            </div>

            <a href="https://bat-id.ch/qr-login" class="mt-6 block w-full rounded-xl bg-batid-marine py-3.5 text-center text-sm font-bold text-batid-vert transition hover:bg-batid-bleu hover:text-white">
                {{ __("Retourner dans l'app bat-id") }}
            </a>
        </div>
        @endif
    </div>
</div>
