@extends('layouts.admin')
@section('content')
<div x-data="translationManager()" x-cloak>

    {{-- HEADER --}}
    <div class="mb-4">
        <a href="{{ route('admin.languages.index') }}" class="text-sm text-batid-bleu hover:underline">&larr; Retour aux langues</a>
    </div>
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-batid-marine">Traductions — {{ ['fr' => 'Français', 'de' => 'Allemand', 'it' => 'Italien', 'en' => 'Anglais'][$locale] ?? strtoupper($locale) }}</h1>
            <p class="mt-1 text-sm text-gray-500">
                <span x-text="stats.total"></span> chaînes ·
                <span class="text-green-600" x-text="stats.translated + ' traduites'"></span> ·
                <span class="text-amber-600" x-show="stats.missing > 0" x-text="stats.missing + ' manquantes'"></span>
            </p>
        </div>
    </div>

    {{-- TOOLBAR --}}
    <div class="mb-6 flex flex-wrap items-center gap-3">
        {{-- Search --}}
        <div class="relative flex-1" style="min-width: 260px; max-width: 420px;">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" x-model.debounce.200ms="search" placeholder="Rechercher une chaîne..."
                   class="w-full rounded-lg border-gray-300 py-2.5 pl-10 pr-10 text-sm focus:border-batid-bleu focus:ring-batid-bleu">
            <button x-show="search.length > 0" @click="search = ''" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Missing only toggle --}}
        <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm transition"
               :class="missingOnly ? 'border-amber-300 bg-amber-50 text-amber-700' : 'text-gray-600'">
            <input type="checkbox" x-model="missingOnly" class="rounded border-gray-300 text-amber-500 focus:ring-amber-400">
            Manquantes uniquement
        </label>

        {{-- Collapse / Expand long texts --}}
        <button @click="allCollapsed = !allCollapsed" class="rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-600 transition hover:border-gray-300">
            <i class="fa-solid" :class="allCollapsed ? 'fa-expand' : 'fa-compress'"></i>
            <span x-text="allCollapsed ? 'Déplier textes longs' : 'Replier textes longs'"></span>
        </button>
    </div>

    {{-- CATEGORY FILTER PILLS --}}
    <div class="mb-6 flex flex-wrap gap-2">
        <button @click="activeCategory = null"
                class="rounded-full px-4 py-1.5 text-sm font-medium transition"
                :class="activeCategory === null ? 'bg-batid-marine text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
            Toutes <span class="ml-1 opacity-60" x-text="'(' + stats.total + ')'"></span>
        </button>
        @foreach($categoryMeta as $catKey => $cat)
        <button @click="activeCategory = activeCategory === '{{ $catKey }}' ? null : '{{ $catKey }}'"
                class="rounded-full px-4 py-1.5 text-sm font-medium transition"
                :class="activeCategory === '{{ $catKey }}' ? 'bg-batid-marine text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
            <i class="{{ $cat['icon'] }} mr-1 text-xs"></i>
            {{ $cat['label'] }}
            <span class="ml-1 opacity-60" x-text="'(' + categoryCount('{{ $catKey }}') + ')'"></span>
        </button>
        @endforeach
    </div>

    {{-- TRANSLATION GROUPS --}}
    @foreach($categoryMeta as $catKey => $cat)
    <div x-show="(activeCategory === null || activeCategory === '{{ $catKey }}') && categoryVisible('{{ $catKey }}')" class="mb-8">
        {{-- Category header --}}
        <div class="mb-3 flex items-center gap-3">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-batid-marine/10 text-batid-marine">
                <i class="{{ $cat['icon'] }} text-sm"></i>
            </div>
            <h2 class="text-lg font-bold text-batid-marine">{{ $cat['label'] }}</h2>
            <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-500" x-text="categoryVisibleCount('{{ $catKey }}') + ' / ' + categoryCount('{{ $catKey }}')"></span>
            {{-- Category progress bar --}}
            <div class="ml-auto flex items-center gap-2">
                <div class="h-1.5 w-24 overflow-hidden rounded-full bg-gray-200">
                    <div class="h-full rounded-full transition-all duration-300"
                         :class="categoryProgress('{{ $catKey }}') === 100 ? 'bg-green-500' : 'bg-batid-bleu'"
                         :style="'width:' + categoryProgress('{{ $catKey }}') + '%'"></div>
                </div>
                <span class="text-xs text-gray-400" x-text="categoryProgress('{{ $catKey }}') + '%'"></span>
            </div>
        </div>

        {{-- Translation rows --}}
        <div class="space-y-2">
            @foreach($items as $index => $item)
            @if($item['category'] === $catKey)
            <div x-show="isVisible({{ $index }})"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="rounded-lg bg-white p-4 shadow-sm ring-1 ring-gray-100 transition hover:ring-gray-200"
                 :class="items[{{ $index }}].translation === '' ? 'ring-amber-200 bg-amber-50/30' : ''">

                {{-- Context badges --}}
                <div class="mb-2 flex flex-wrap items-center gap-1.5">
                    @foreach($item['types'] as $type)
                        @if($type === 'fo')
                        <span class="inline-flex items-center rounded px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide bg-blue-100 text-blue-700">Site public</span>
                        @elseif($type === 'bo')
                        <span class="inline-flex items-center rounded px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide bg-purple-100 text-purple-700">Admin</span>
                        @elseif($type === 'pdf')
                        <span class="inline-flex items-center rounded px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide bg-orange-100 text-orange-700">PDF</span>
                        @endif
                    @endforeach
                    @foreach($item['locations'] as $loc)
                    <span class="inline-flex items-center rounded bg-gray-100 px-1.5 py-0.5 text-[10px] text-gray-500">{{ $loc }}</span>
                    @endforeach
                    @if(count($item['locations']) === 0)
                    <span class="inline-flex items-center rounded bg-red-50 px-1.5 py-0.5 text-[10px] text-red-400">Non référencée</span>
                    @endif
                </div>

                {{-- Source + Translation --}}
                <div class="flex gap-4">
                    {{-- French source --}}
                    <div class="w-1/2">
                        <p class="mb-1 text-[10px] font-semibold uppercase tracking-wide text-gray-400">FR (source)</p>
                        @if($item['isLong'])
                        <div x-data="{ expanded: !allCollapsed }" x-effect="expanded = !allCollapsed">
                            <p class="text-sm leading-relaxed text-gray-700" x-show="expanded">{{ $item['source'] }}</p>
                            <p class="text-sm leading-relaxed text-gray-700" x-show="!expanded">{{ Str::limit($item['source'], 90) }}</p>
                            <button @click="expanded = !expanded" class="mt-1 text-xs text-batid-bleu hover:underline" x-text="expanded ? 'Réduire' : 'Voir tout'"></button>
                        </div>
                        @else
                        <p class="text-sm text-gray-700">{{ $item['source'] }}</p>
                        @endif
                    </div>

                    {{-- Translation --}}
                    <div class="w-1/2">
                        <p class="mb-1 text-[10px] font-semibold uppercase tracking-wide text-gray-400">{{ strtoupper($locale) }}</p>
                        @if($item['isLong'])
                        <textarea
                            rows="3"
                            data-index="{{ $index }}"
                            x-model="items[{{ $index }}].translation"
                            @blur="saveTranslation({{ $index }})"
                            class="w-full rounded border-gray-300 text-sm leading-relaxed transition focus:border-batid-bleu focus:ring-batid-bleu"
                            :class="items[{{ $index }}].translation === '' ? 'border-amber-300 bg-amber-50' : (items[{{ $index }}].saved ? 'border-green-300 bg-green-50' : '')"
                        ></textarea>
                        @else
                        <input type="text"
                            data-index="{{ $index }}"
                            x-model="items[{{ $index }}].translation"
                            @blur="saveTranslation({{ $index }})"
                            class="w-full rounded border-gray-300 text-sm transition focus:border-batid-bleu focus:ring-batid-bleu"
                            :class="items[{{ $index }}].translation === '' ? 'border-amber-300 bg-amber-50' : (items[{{ $index }}].saved ? 'border-green-300 bg-green-50' : '')">
                        @endif
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
    @endforeach

    {{-- Empty state --}}
    <div x-show="visibleCount() === 0" class="py-16 text-center">
        <i class="fa-regular fa-face-meh mb-3 text-4xl text-gray-300"></i>
        <p class="text-sm text-gray-400">Aucune chaîne ne correspond à votre recherche.</p>
    </div>

</div>

<script>
function translationManager() {
    return {
        items: @json($items),
        search: '',
        activeCategory: null,
        missingOnly: false,
        allCollapsed: true,
        csrfToken: '{{ csrf_token() }}',
        saveUrl: '{{ route("admin.languages.translations.update") }}',
        locale: '{{ $locale }}',

        get stats() {
            const total = this.items.length;
            const translated = this.items.filter(i => i.translation !== '').length;
            return { total, translated, missing: total - translated };
        },

        isVisible(index) {
            const item = this.items[index];
            if (this.missingOnly && item.translation !== '') return false;
            if (this.activeCategory && item.category !== this.activeCategory) return false;
            if (this.search.length > 1) {
                const q = this.search.toLowerCase();
                const haystack = (item.source + ' ' + item.translation + ' ' + item.locations.join(' ')).toLowerCase();
                return haystack.includes(q);
            }
            return true;
        },

        visibleCount() {
            return this.items.filter((_, i) => this.isVisible(i)).length;
        },

        categoryCount(cat) {
            return this.items.filter(i => i.category === cat).length;
        },

        categoryVisibleCount(cat) {
            return this.items.filter((item, i) => item.category === cat && this.isVisible(i)).length;
        },

        categoryVisible(cat) {
            return this.items.some((item, i) => item.category === cat && this.isVisible(i));
        },

        categoryProgress(cat) {
            const catItems = this.items.filter(i => i.category === cat);
            if (catItems.length === 0) return 100;
            const done = catItems.filter(i => i.translation !== '').length;
            return Math.round((done / catItems.length) * 100);
        },

        async saveTranslation(index) {
            const item = this.items[index];
            try {
                await fetch(this.saveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                    body: JSON.stringify({
                        locale: this.locale,
                        key: item.key,
                        value: item.translation,
                    }),
                });
                item.saved = true;
                setTimeout(() => { item.saved = false; }, 1500);
            } catch (e) {
                console.error('Save failed', e);
            }
        },
    };
}
</script>
@endsection
