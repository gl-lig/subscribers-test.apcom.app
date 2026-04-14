<div>
    <!-- Hero -->
    <section class="bg-batid-marine py-16 text-center text-white">
        <div class="mx-auto max-w-4xl px-4">
            <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl">{{ __('Nos abonnements') }}</h1>
            <p class="mt-4 text-lg text-white/60">{{ __("Choisissez l'abonnement qui correspond à vos besoins") }}</p>
        </div>
    </section>

    <!-- Duration selector -->
    <div class="mx-auto -mt-6 max-w-md px-4">
        <div class="flex rounded-xl bg-white p-1.5 shadow-lg ring-1 ring-gray-200">
            @foreach([12, 24, 36] as $d)
            <button wire:click="selectDuration({{ $d }})"
                    class="flex-1 rounded-lg py-2.5 text-sm font-semibold transition {{ $selectedDuration === $d ? 'bg-batid-marine text-white shadow' : 'text-gray-600 hover:text-batid-marine' }}">
                {{ __("$d mois") }}
            </button>
            @endforeach
        </div>
    </div>

    <!-- Pricing cards -->
    <section class="mx-auto max-w-7xl px-4 py-16">
        <div class="grid gap-8 md:grid-cols-3">
            @foreach($types as $type)
            @php
                $trans = $type->translation($locale);
                $price = $type->priceForDuration($selectedDuration);
                $annualPrice = $price / ($selectedDuration / 12);
            @endphp
            <div class="relative flex flex-col rounded-2xl bg-white p-8 shadow-sm ring-1 ring-gray-200 transition hover:shadow-lg hover:ring-batid-bleu/30">
                @if($selectedDuration === 36 && $type->discount_36_months > 0)
                <div class="absolute -top-3 right-4 rounded-full bg-batid-vert px-3 py-1 text-xs font-bold text-batid-marine">
                    -{{ intval($type->discount_36_months) }}%
                </div>
                @endif

                <h3 class="text-xl font-bold text-batid-marine">{{ $trans?->name ?? 'N/A' }}</h3>
                <p class="mt-2 text-sm text-gray-500">{{ $trans?->description ?? '' }}</p>

                <div class="mt-6">
                    <span class="text-4xl font-extrabold text-batid-marine">CHF {{ number_format($annualPrice, 0) }}</span>
                    <span class="text-sm text-gray-500">/ {{ __('par an') }}</span>
                </div>

                @if($selectedDuration > 12)
                <p class="mt-1 text-xs text-gray-400">Total: CHF {{ number_format($price, 2) }} / {{ $selectedDuration }} {{ __('mois') }}</p>
                @endif

                <ul class="mt-8 flex-1 space-y-3">
                    <li class="flex items-center gap-3 text-sm">
                        <svg class="h-5 w-5 flex-shrink-0 text-batid-vert" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>{{ __('Parcelles') }}: {{ $type->parcelles_unlimited ? '∞' : $type->parcelles_count }}</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm">
                        <svg class="h-5 w-5 flex-shrink-0 text-batid-vert" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>{{ __('Alertes') }}: {{ $type->alertes_count }}</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm">
                        <svg class="h-5 w-5 flex-shrink-0 text-batid-vert" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>{{ __('Stockage') }}: {{ $type->stockage_unlimited ? '∞' : $type->stockage_go . ' Go' }}</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm">
                        @if($type->cloud_externe)
                        <svg class="h-5 w-5 flex-shrink-0 text-batid-vert" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        @else
                        <svg class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        @endif
                        <span class="{{ $type->cloud_externe ? '' : 'text-gray-400' }}">{{ __('Cloud externe') }}</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm">
                        @if($type->lot_sauvegarde)
                        <svg class="h-5 w-5 flex-shrink-0 text-batid-vert" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        @else
                        <svg class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        @endif
                        <span class="{{ $type->lot_sauvegarde ? '' : 'text-gray-400' }}">{{ __('Lot de sauvegarde') }}</span>
                    </li>
                    @if($type->workspace_enabled)
                    <li class="flex items-center gap-3 text-sm">
                        <svg class="h-5 w-5 flex-shrink-0 text-batid-vert" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>{{ __('Workspace') }}: {{ $type->workspace_unlimited ? '∞' : $type->workspace_count }}</span>
                    </li>
                    @endif
                </ul>

                <button wire:click="selectPlan({{ $type->id }})"
                        class="mt-8 w-full rounded-xl bg-batid-marine py-3.5 text-sm font-bold text-batid-vert transition hover:bg-batid-bleu hover:text-white">
                    {{ __('Commander') }}
                </button>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Phone modal -->
    @if($showPhoneModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,77,0.6); backdrop-filter: blur(8px);">
        <div class="w-full max-w-md animate-fade-in-up rounded-2xl bg-white p-8 shadow-2xl">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-xl font-bold text-batid-marine">{{ __('Vérification de votre numéro') }}</h2>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <livewire:phone-verification />
        </div>
    </div>
    @endif
</div>
