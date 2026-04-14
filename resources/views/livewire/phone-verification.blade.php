<div>
    @if($notFound)
    <div class="text-center">
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-yellow-100">
            <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
        </div>
        <h3 class="mb-2 text-lg font-semibold text-batid-marine">{{ __("Vous n'avez pas encore l'app bat-id") }}</h3>
        <p class="mb-6 text-sm text-gray-500">{{ __("Pour souscrire à un abonnement, vous devez d'abord installer l'application bat-id.") }}</p>
        <a href="https://bat-id.ch/qr-login" target="_blank" class="inline-block rounded-xl bg-batid-bleu px-6 py-3 text-sm font-semibold text-white transition hover:bg-batid-marine">
            {{ __('Télécharger bat-id') }}
        </a>
        <button wire:click="$set('notFound', false)" class="mt-3 block w-full text-sm text-gray-500 hover:text-batid-bleu">{{ __('Réessayer') }}</button>
    </div>
    @else
    <div>
        <p class="mb-4 text-sm text-gray-500">{{ __('Entrez votre numéro de téléphone mobile') }}</p>
        <div class="mb-4">
            <input type="tel" wire:model="phone" inputmode="tel" placeholder="+41 79 xxx xx xx"
                   class="w-full rounded-xl border-gray-300 px-4 py-3.5 text-lg tracking-wider focus:border-batid-bleu focus:ring-batid-bleu"
                   wire:keydown.enter="verifyPhone">
        </div>

        @if($error)
        <p class="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-600">{{ $error }}</p>
        @endif

        <button wire:click="verifyPhone" wire:loading.attr="disabled"
                class="w-full rounded-xl bg-batid-marine py-3.5 text-sm font-bold text-batid-vert transition hover:bg-batid-bleu hover:text-white disabled:opacity-50">
            <span wire:loading.remove>{{ __('Vérifier') }}</span>
            <span wire:loading class="flex items-center justify-center gap-2">
                <span class="spinner"></span> {{ __('Chargement...') }}
            </span>
        </button>
    </div>
    @endif
</div>
