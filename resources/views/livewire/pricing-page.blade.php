<div>
    <!-- Hero -->
    <section class="py-16 text-center text-white" style="background: linear-gradient(to bottom, #00004D 0%, #0050FF 40%, #1B9FD2 65%, #2DD3B6 85%, #3DFF9E 100%);">
        <div class="mx-auto max-w-4xl px-4">
            <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl">{{ __('Nos abonnements') }}</h1>
            <p class="mt-4 text-lg text-white/60">{{ __("Choisissez l'abonnement qui correspond à vos besoins") }}</p>
        </div>
    </section>

    <!-- Duration selector -->
    <div class="mx-auto -mt-6 max-w-lg px-4">
        <div class="flex gap-2 rounded-2xl bg-white p-2 shadow-lg ring-1 ring-gray-200">
            @foreach([12, 24, 36] as $d)
            <button wire:click="selectDuration({{ $d }})"
                    class="relative flex-1 rounded-xl py-3 text-sm font-semibold transition {{ $selectedDuration === $d ? 'bg-batid-marine text-white shadow-md' : 'text-gray-500 hover:bg-gray-50 hover:text-batid-marine' }}">
                {{ $d }} {{ __('mois') }}
                @if(($maxDiscounts[$d] ?? 0) > 0)
                <span class="absolute -top-2 {{ $d === 36 ? '-right-1' : '-right-2' }} rounded-md bg-batid-vert px-1.5 py-0.5 text-[10px] font-bold leading-none text-batid-marine shadow-sm">-{{ $maxDiscounts[$d] }}%</span>
                @endif
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
                $baseMonthly = (float) $type->price_chf / 12;
                $totalPrice = $type->priceForDuration($selectedDuration);
                $monthlyPrice = $totalPrice / $selectedDuration;
                $hasDiscount = $type->discountForDuration($selectedDuration) > 0;
                $discountPct = $type->discountForDuration($selectedDuration);
            @endphp
            <div wire:key="plan-{{ $type->id }}-{{ $selectedDuration }}" class="flex flex-col rounded-2xl bg-white p-8 shadow-sm ring-1 ring-gray-200 transition hover:shadow-lg hover:ring-batid-bleu/30">
                <h3 class="text-xl font-bold text-batid-marine">{{ $trans?->name ?? 'N/A' }}</h3>
                <p class="mt-2 text-sm text-gray-500">{{ $trans?->description ?? '' }}</p>

                <div class="mt-6">
                    <span class="text-4xl font-extrabold text-batid-marine">CHF {{ number_format($monthlyPrice, 2) }}</span>
                    <span class="text-sm text-gray-500">/ {{ __('mois') }}</span>
                </div>

                @if($selectedDuration > 12)
                <p class="mt-1 text-xs text-gray-400 line-through">CHF {{ number_format($baseMonthly, 2) }} / {{ __('mois') }}</p>
                @endif
                <p class="mt-1 text-xs text-gray-400">{{ __('Total') }}: CHF {{ number_format($totalPrice, 2) }} / {{ $selectedDuration }} {{ __('mois') }}</p>

                <div class="mt-8 flex-1">
                    <x-subscription-features :type="$type" />
                </div>

                <button wire:click="selectPlan({{ $type->id }})"
                        class="mt-8 w-full rounded-full py-3.5 text-sm font-bold text-white transition hover:opacity-90"
                        style="background: linear-gradient(to right, #3DFF9E 0%, #0050FF 50%, #00004D 100%);">
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
