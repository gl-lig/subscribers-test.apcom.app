<div>
    <!-- Hero -->
    <section class="py-16 text-center text-white" style="background: linear-gradient(to bottom, #00004D 0%, #0050FF 40%, #1B9FD2 65%, #2DD3B6 85%, #3DFF9E 100%);">
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
                    class="relative flex-1 rounded-lg py-2.5 text-sm font-semibold transition {{ $selectedDuration === $d ? 'bg-batid-marine text-white shadow' : 'text-gray-600 hover:text-batid-marine' }}">
                {{ __("$d mois") }}
                @if(($maxDiscounts[$d] ?? 0) > 0)
                <sup class="ml-0.5 inline-block rounded-full px-1.5 py-0.5 text-[9px] font-bold leading-none {{ $selectedDuration === $d ? 'bg-white text-batid-marine' : 'bg-batid-vert text-batid-marine' }}" style="vertical-align:super;">-{{ $maxDiscounts[$d] }}%</sup>
                @endif
            </button>
            @endforeach
        </div>
    </div>

    <!-- Pricing cards slider -->
    <section class="py-16" x-data="{
        current: 0,
        total: {{ count($types) }},
        cardWidth: 0,
        gap: 32,
        settling: false,
        init() {
            this.measure();
            window.addEventListener('resize', () => this.measure());
        },
        measure() {
            const container = this.$refs.container;
            if (!container) return;
            const w = container.offsetWidth;
            if (window.innerWidth < 768) {
                this.cardWidth = w * 0.82;
            } else if (window.innerWidth < 1024) {
                this.cardWidth = (w - this.gap) / 2;
            } else {
                this.cardWidth = (w - this.gap * 2) / 3;
            }
        },
        visibleCards() {
            if (window.innerWidth >= 1024) return 3;
            if (window.innerWidth >= 768) return 2;
            return 1;
        },
        maxIndex() {
            return Math.max(0, this.total - this.visibleCards());
        },
        next() {
            if (this.current < this.maxIndex()) {
                this.current++;
                this.settle();
            }
        },
        prev() {
            if (this.current > 0) {
                this.current--;
                this.settle();
            }
        },
        settle() {
            this.settling = true;
            setTimeout(() => { this.settling = false; }, 500);
        },
        offset() {
            return -(this.current * (this.cardWidth + this.gap));
        }
    }" x-resize="measure()">
        <div class="relative mx-auto max-w-7xl px-4" x-ref="container">

            {{-- Flèche gauche --}}
            <button @click="prev()"
                    class="absolute -left-3 top-1/2 z-10 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full shadow-lg transition sm:-left-4 md:-left-5"
                    :class="current > 0 ? 'bg-batid-marine text-white hover:opacity-90' : 'bg-gray-100 text-gray-300 cursor-default'"
                    :disabled="current === 0"
                    style="backdrop-filter: blur(4px);">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </button>

            {{-- Track --}}
            <div class="overflow-hidden px-2 sm:px-0">
                <div x-ref="track"
                     class="flex gap-8 transition-transform duration-500 ease-out"
                     :class="settling ? 'animate-[settle_0.3s_ease-out_0.4s]' : ''"
                     :style="'transform: translateX(' + offset() + 'px)'">
                    @foreach($types as $type)
                    @php
                        $trans = $type->translation($locale);
                        $baseMonthly = (float) $type->price_chf / 12;
                        $totalPrice = $type->priceForDuration($selectedDuration);
                        $monthlyPrice = $totalPrice / $selectedDuration;
                    @endphp
                    <div wire:key="plan-{{ $type->id }}-{{ $selectedDuration }}"
                         class="flex-shrink-0 flex flex-col rounded-2xl bg-white p-8 shadow-sm ring-1 ring-gray-200 transition hover:shadow-lg hover:ring-batid-bleu/30"
                         :style="'width:' + cardWidth + 'px'"
                        <h3 class="text-xl font-bold text-batid-marine">{{ $trans?->name ?? 'N/A' }}</h3>
                        <p class="mt-2 text-sm text-gray-500">{{ $trans?->description ?? '' }}</p>

                        <div class="mt-6">
                            @if($type->is_free)
                            <span class="text-4xl font-extrabold text-batid-vert">{{ __('Gratuit') }}</span>
                            @else
                            <span class="text-4xl font-extrabold text-batid-marine">CHF {{ number_format($monthlyPrice, 2) }}</span>
                            <span class="text-sm text-gray-500">/ {{ __('mois') }}</span>
                            @endif
                        </div>

                        @if(!$type->is_free)
                            @if($selectedDuration > 12)
                            <p class="mt-1 text-xs text-gray-400 line-through">CHF {{ number_format($baseMonthly, 2) }} / {{ __('mois') }}</p>
                            @endif
                            <p class="mt-1 text-xs text-gray-400">{{ __('Total') }}: CHF {{ number_format($totalPrice, 2) }} / {{ $selectedDuration }} {{ __('mois') }}</p>
                        @endif

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
            </div>

            {{-- Flèche droite --}}
            <button @click="next()"
                    class="absolute -right-3 top-1/2 z-10 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full shadow-lg transition sm:-right-4 md:-right-5"
                    :class="current < maxIndex() ? 'bg-batid-marine text-white hover:opacity-90' : 'bg-gray-100 text-gray-300 cursor-default'"
                    :disabled="current >= maxIndex()"
                    style="backdrop-filter: blur(4px);">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </button>

            {{-- Dots indicateur --}}
            <div class="mt-6 flex items-center justify-center gap-2" x-show="maxIndex() > 0">
                <template x-for="i in total" :key="i">
                    <button @click="current = i - 1; settle()"
                            class="h-2 rounded-full transition-all duration-300"
                            :class="current === i - 1 ? 'w-6 bg-batid-marine' : 'w-2 bg-gray-300'"></button>
                </template>
            </div>
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
