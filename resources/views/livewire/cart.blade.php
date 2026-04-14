<div class="mx-auto max-w-3xl px-4 py-12">
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

    <!-- Subscription details -->
    @if($type)
    @php $trans = $type->translation($locale); @endphp
    <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
        <h2 class="text-xl font-bold text-batid-marine">{{ $trans?->name ?? '' }}</h2>
        <p class="mt-1 text-sm text-gray-500">{{ $trans?->description ?? '' }}</p>

        <!-- Duration selector -->
        <div class="mt-6">
            <label class="mb-2 block text-sm font-medium text-gray-700">{{ __('Durée') }}</label>
            <div class="flex gap-2">
                @foreach([12, 24, 36] as $d)
                <button wire:key="cart-dur-{{ $d }}" wire:click="changeDuration({{ $d }})"
                        class="flex-1 rounded-lg py-2.5 text-sm font-semibold transition {{ $duration === $d ? 'bg-batid-marine text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    {{ $d }} {{ __('mois') }}
                </button>
                @endforeach
            </div>
        </div>

        <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
            <div><span class="text-gray-500">{{ __('Date de début') }}</span><p class="font-medium">{{ now()->format('d.m.Y') }}</p></div>
            <div><span class="text-gray-500">{{ __('Date de fin') }}</span><p class="font-medium">{{ now()->addMonths($duration)->format('d.m.Y') }}</p></div>
        </div>

        <div class="mt-5 border-t pt-5">
            <x-subscription-features :type="$type" :compact="true" />
        </div>
    </div>

    <!-- Price breakdown -->
    <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
        <div class="space-y-3 text-sm">
            <div class="flex justify-between"><span class="text-gray-600">{{ __('Prix catalogue TTC') }}</span><span>CHF {{ number_format($prices['price_catalogue'] ?? 0, 2) }}</span></div>
            @if(($prices['discount_duration_pct'] ?? 0) > 0)
            <div class="flex justify-between text-green-600"><span>{{ __('Rabais') }} {{ $prices['discount_duration_pct'] }}%</span><span>- CHF {{ number_format($prices['discount_duration_amount'] ?? 0, 2) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-600">{{ __('Sous-total') }}</span><span>CHF {{ number_format($prices['subtotal_after_duration'] ?? 0, 2) }}</span></div>
            @endif
            @if($promoValid)
            <div class="flex justify-between text-green-600"><span>{{ __('Code promo') }} {{ $promoCode }} (-{{ $promoDiscount }}%)</span><span>- CHF {{ number_format($prices['discount_promo_amount'] ?? 0, 2) }}</span></div>
            @endif
            @if(($prices['prorata'] ?? 0) > 0)
            <div class="flex justify-between text-green-600"><span>{{ __('Prorata résiduel') }}</span><span>- CHF {{ number_format($prices['prorata'], 2) }}</span></div>
            @endif
            <div class="flex justify-between border-t pt-3 text-lg font-bold text-batid-marine">
                <span>{{ __('Total à payer TTC') }}</span><span>CHF {{ number_format($prices['total'] ?? 0, 2) }}</span>
            </div>
        </div>
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

        <button wire:click="processPayment" wire:loading.attr="disabled"
                {{ !$cgvAccepted ? 'disabled' : '' }}
                class="w-full rounded-xl bg-batid-marine py-4 text-lg font-bold text-batid-vert transition hover:bg-batid-bleu hover:text-white disabled:cursor-not-allowed disabled:opacity-50">
            <span wire:loading.remove>{{ __('Payer') }} CHF {{ number_format($prices['total'] ?? 0, 2) }}</span>
            <span wire:loading class="flex items-center justify-center gap-2"><span class="spinner"></span> {{ __('Chargement...') }}</span>
        </button>
    </div>
    @endif
</div>
