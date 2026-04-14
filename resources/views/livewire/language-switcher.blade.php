<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" class="flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-medium text-white/80 transition hover:text-white">
        {{ strtoupper($currentLocale) }}
        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
    </button>
    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-1 w-32 rounded-lg bg-white py-1 shadow-lg ring-1 ring-gray-200">
        @foreach(['fr' => 'Français', 'de' => 'Deutsch', 'it' => 'Italiano', 'en' => 'English'] as $code => $label)
        <button wire:click="switchLocale('{{ $code }}')" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 {{ $currentLocale === $code ? 'font-semibold text-batid-bleu' : '' }}">
            {{ $label }}
        </button>
        @endforeach
    </div>
</div>
