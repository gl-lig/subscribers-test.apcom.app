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
    <div x-data="{
        iti: null,
        fullNumber: '',
        init() {
            this.$nextTick(() => {
                const input = this.$refs.phoneField;
                if (!input || !window.intlTelInput) return;
                this.iti = window.intlTelInput(input, {
                    initialCountry: 'ch',
                    preferredCountries: ['ch', 'fr', 'de', 'it', 'at'],
                    separateDialCode: true,
                    nationalMode: true,
                    formatOnDisplay: true,
                    countrySearch: true,
                    showFlags: true,
                    useFullscreenPopup: false,
                    loadUtilsOnInit: false,
                });
            });
        },
        getFullNumber() {
            if (this.iti && window.intlTelInputUtils) {
                return this.iti.getNumber(intlTelInputUtils.numberFormat.E164);
            }
            if (this.iti) {
                const dialCode = this.iti.getSelectedCountryData().dialCode;
                const national = this.$refs.phoneField.value.replace(/\s/g, '');
                return '+' + dialCode + national.replace(/^0+/, '');
            }
            return this.$refs.phoneField.value;
        },
        submit() {
            const number = this.getFullNumber();
            $wire.set('phone', number);
            $wire.verifyPhone();
        }
    }">
        <p class="mb-4 text-sm text-gray-500">{{ __('Entrez votre numéro de téléphone mobile') }}</p>
        <div class="mb-4" wire:ignore>
            <input type="tel" x-ref="phoneField" inputmode="tel"
                   placeholder="79 123 45 67"
                   class="w-full rounded-xl border-gray-300 px-4 py-3.5 text-lg tracking-wider focus:border-batid-bleu focus:ring-batid-bleu"
                   @keydown.enter.prevent="submit()">
        </div>

        @if($error)
        <p class="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-600">{{ $error }}</p>
        @endif

        <button @click="submit()" wire:loading.attr="disabled" wire:target="verifyPhone"
                class="w-full rounded-xl bg-batid-marine py-3.5 text-sm font-bold text-batid-vert transition hover:bg-batid-bleu hover:text-white disabled:opacity-50">
            <span wire:loading.remove wire:target="verifyPhone">{{ __('Vérifier') }}</span>
            <span wire:loading wire:target="verifyPhone" class="flex items-center justify-center gap-2">
                <span class="spinner"></span> {{ __('Chargement...') }}
            </span>
        </button>
    </div>
    @endif
</div>
