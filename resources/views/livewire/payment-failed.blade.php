<div class="flex min-h-[60vh] items-center justify-center px-4 py-16">
    <div class="max-w-md text-center">
        <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-red-100">
            <svg class="h-10 w-10 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </div>
        <h1 class="mb-2 text-2xl font-bold text-batid-marine">{{ __("Le paiement n'a pas abouti") }}</h1>
        <p class="mb-8 text-gray-500">{{ __("Votre paiement n'a pas pu être traité. Aucun montant n'a été débité.") }}</p>

        <div class="space-y-3">
            <a href="{{ route('cart') }}" class="block w-full rounded-xl bg-batid-marine py-3.5 text-sm font-bold text-batid-vert transition hover:bg-batid-bleu hover:text-white">
                {{ __('Réessayer') }}
            </a>
            <a href="{{ route('home') }}" class="block text-sm text-gray-500 hover:text-batid-bleu">
                {{ __('Retour aux abonnements') }}
            </a>
        </div>
    </div>
</div>
