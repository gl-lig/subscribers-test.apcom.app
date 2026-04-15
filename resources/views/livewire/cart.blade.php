<div x-data="{
    duration: @entangle('duration'),
    allPrices: {{ Js::from($allPrices) }},
    discounts: {{ Js::from($discounts) }},
    fmt(v) { return parseFloat(v || 0).toFixed(2); }
}" class="mx-auto max-w-3xl px-4 py-12">
    <a href="{{ route('home') }}" class="mb-8 inline-flex items-center text-sm text-batid-bleu hover:underline">&larr; {{ __('Retour aux abonnements') }}</a>

    <h1 class="mb-8 text-3xl font-bold text-batid-marine">{{ __('Votre panier') }}</h1>

    @if(session('error'))
    <div class="mb-6 rounded-lg bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <!-- Current subscription (upgrade) -->
    @if($isUpgrade && $currentOrderData)
    <div class="mb-6 rounded-xl border-2 border-yellow-300 bg-yellow-50 p-6">
        <h3 class="font-semibold text-yellow-800">{{ __('Votre abonnement actif') }}</h3>
        <p class="mt-1 text-sm text-yellow-700">{{ $currentOrderData['type_name'] }} — {{ __("Validité jusqu'au") }} {{ $currentOrderData['expires_at'] }}</p>
    </div>
    @endif

    <!-- User identification -->
    <div class="mb-6 flex items-center gap-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
        <div class="flex h-16 w-12 flex-shrink-0 items-center justify-center rounded-2xl" style="background: linear-gradient(to bottom, #00004D 0%, #0050FF 50%, #3DFF9E 100%);">
            <img src="{{ asset('assets/brand/BATID_Monogramme_blanc.svg') }}" alt="bat-id" class="h-6 w-6">
        </div>
        <div class="min-w-0">
            <p class="text-lg font-bold text-batid-marine">{{ session('bat_id') }}</p>
            <p class="text-base text-gray-600">{{ session('bat_phone') }}</p>
        </div>
    </div>

    <!-- Subscription details -->
    @if($type)
    @php $trans = $type->translation($locale); @endphp
    <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
        <h2 class="text-xl font-bold text-batid-marine">{{ $trans?->name ?? '' }}</h2>
        <p class="mt-1 text-sm text-gray-500">{{ $trans?->description ?? '' }}</p>

        <!-- Duration selector (Alpine = instant) -->
        <div class="mt-6">
            <label class="mb-2 block text-sm font-medium text-gray-700">{{ __('Durée') }}</label>
            <div class="flex gap-2">
                @foreach([12, 24, 36] as $d)
                <button @click="duration = {{ $d }}; $wire.changeDuration({{ $d }})"
                        class="relative flex-1 rounded-lg py-2.5 text-sm font-semibold transition"
                        :class="duration === {{ $d }} ? 'bg-batid-marine text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                    {{ $d }} {{ __('mois') }}
                    <template x-if="discounts[{{ $d }}] > 0">
                        <sup class="ml-0.5 inline-block rounded-full px-1.5 py-0.5 text-[9px] font-bold leading-none"
                             :class="duration === {{ $d }} ? 'bg-white text-batid-marine' : 'bg-batid-vert text-batid-marine'"
                             style="vertical-align:super;" x-text="'-' + discounts[{{ $d }}] + '%'"></sup>
                    </template>
                </button>
                @endforeach
            </div>
        </div>

        <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
            <div><span class="text-gray-500">{{ __('Date de début') }}</span><p class="font-medium">{{ now()->format('d.m.Y') }}</p></div>
            <div><span class="text-gray-500">{{ __('Date de fin') }}</span>
                <p class="font-medium">
                    <template x-if="duration === 12">{{ now()->addMonths(12)->format('d.m.Y') }}</template>
                    <template x-if="duration === 24">{{ now()->addMonths(24)->format('d.m.Y') }}</template>
                    <template x-if="duration === 36">{{ now()->addMonths(36)->format('d.m.Y') }}</template>
                </p>
            </div>
        </div>

        <div class="mt-5 border-t pt-5">
            <x-subscription-features :type="$type" :compact="true" />
        </div>
    </div>

    <!-- Price breakdown (Alpine = instant) -->
    <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
        <div class="space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-600">{{ __('Prix catalogue TTC') }}</span>
                <span>CHF <span x-text="fmt(allPrices[duration]?.price_catalogue)"></span></span>
            </div>
            <template x-if="(allPrices[duration]?.discount_duration_pct || 0) > 0">
                <div>
                    <div class="flex justify-between text-green-600">
                        <span>{{ __('Rabais') }} <span x-text="allPrices[duration]?.discount_duration_pct"></span>%</span>
                        <span>- CHF <span x-text="fmt(allPrices[duration]?.discount_duration_amount)"></span></span>
                    </div>
                    <div class="mt-3 flex justify-between">
                        <span class="text-gray-600">{{ __('Sous-total') }}</span>
                        <span>CHF <span x-text="fmt(allPrices[duration]?.subtotal_after_duration)"></span></span>
                    </div>
                </div>
            </template>
            @if($promoValid)
            <div class="flex justify-between text-green-600">
                <span>{{ __('Code promo') }} {{ $promoCode }} (-{{ $promoDiscount }}%)</span>
                <span>- CHF <span x-text="fmt(allPrices[duration]?.discount_promo_amount)"></span></span>
            </div>
            @endif
            <template x-if="(allPrices[duration]?.prorata || 0) > 0">
                <div class="flex justify-between text-green-600">
                    <span>{{ __('Prorata résiduel abonnement actif') }}</span>
                    <span>- CHF <span x-text="fmt(allPrices[duration]?.prorata)"></span></span>
                </div>
            </template>
            <div class="flex justify-between border-t pt-3 text-lg font-bold text-batid-marine">
                <span>{{ __('Total à payer TTC') }}</span>
                <span>CHF <span x-text="fmt(allPrices[duration]?.total)"></span></span>
            </div>
        </div>
        <template x-if="(allPrices[duration]?.prorata || 0) > 0">
            <p class="mt-3 text-xs text-gray-500">{{ __('Votre prorata est immédiatement récupéré et déduit de votre nouvelle commande.') }}</p>
        </template>
    </div>

    <!-- Promo code -->
    <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
        <label class="mb-2 block text-sm font-medium text-gray-700">{{ __('Code promo') }}</label>
        <div class="flex gap-2">
            <input type="text" wire:model="promoCode" placeholder="CODE" class="flex-1 rounded-lg border-gray-300 text-sm font-mono uppercase">
            @if($promoValid)
            <button wire:click="removePromoCode" class="rounded-lg bg-red-100 px-4 py-2 text-sm text-red-700 hover:bg-red-200">{{ __('Supprimer') }}</button>
            @else
            <button wire:click="applyPromoCode" class="rounded-lg bg-batid-bleu px-4 py-2 text-sm text-white hover:bg-batid-marine">{{ __('Appliquer') }}</button>
            @endif
        </div>
        @if($promoValid)<p class="mt-2 text-sm text-green-600">{{ __('Code promo appliqué') }} (-{{ $promoDiscount }}%)</p>@endif
        @if($promoError)<p class="mt-2 text-sm text-red-600">{{ $promoError }}</p>@endif
    </div>

    <!-- CGV + Pay -->
    <div class="space-y-4">
        <label class="flex items-start gap-3">
            <input type="checkbox" wire:model.live="cgvAccepted" class="mt-0.5 rounded border-gray-300 text-batid-bleu">
            <span class="text-sm text-gray-600">{!! __("J'ai lu et j'accepte les Conditions Générales de Vente de bat-id.ch") !!}
                <a href="https://bat-id.ch/terms" target="_blank" class="text-batid-bleu hover:underline">{{ __('Conditions Générales de Vente') }}</a>
            </span>
        </label>
        @error('cgv')<p class="text-sm text-red-600">{{ $message }}</p>@enderror

        <button @click="$wire.processPayment(duration)" wire:loading.attr="disabled"
                {{ !$cgvAccepted ? 'disabled' : '' }}
                class="w-full rounded-full py-4 text-lg font-bold text-white transition hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-50"
                style="background: linear-gradient(to right, #3DFF9E 0%, #0050FF 50%, #00004D 100%);"
                >
            <span wire:loading.remove>@if($type && $type->is_free){{ __('Confirmer') }}@else{{ __('Payer') }} CHF <span x-text="fmt(allPrices[duration]?.total)"></span>@endif</span>
            <span wire:loading class="flex items-center justify-center gap-2"><span class="spinner"></span> {{ __('Chargement...') }}</span>
        </button>
    </div>
    @endif

    <!-- Upgrade blocked modal -->
    @if($upgradeBlocked)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,77,0.6); backdrop-filter: blur(8px);">
        <div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-2xl">
            <div class="mb-4 flex justify-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-red-100">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                </div>
            </div>
            <h2 class="mb-2 text-center text-xl font-bold text-batid-marine">{{ __('Upgrade impossible') }}</h2>
            <p class="mb-6 text-center text-sm text-gray-600">{{ $upgradeBlockedMessage }}</p>
            <a href="{{ route('home') }}" class="block w-full rounded-full py-3.5 text-center text-sm font-bold text-white transition hover:opacity-90" style="background: linear-gradient(to right, #3DFF9E 0%, #0050FF 50%, #00004D 100%);">
                {{ __('Choisir un autre abonnement') }}
            </a>
        </div>
    </div>
    @endif
</div>
