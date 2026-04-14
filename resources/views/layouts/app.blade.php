<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'bat-id Subscribers') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/brand/BATID_Monogramme_degrade_neg_icoNAT.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.1/build/css/intlTelInput.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-white font-sans text-batid-marine antialiased">
    <!-- Header -->
    <header style="background: linear-gradient(to top right, #3DFF9E 0%, #38F3A4 6%, #2DD3B6 17%, #1B9FD2 32%, #0050FF 52%, #00004D 84%);">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('assets/brand/BATID_Logo_blanc.svg') }}" alt="bat-id" class="h-7">
                </a>
                <div class="flex items-center gap-4">
                    <livewire:language-switcher />
                </div>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="mt-20 bg-batid-marine pb-8 pt-10 text-white/70">
        <div class="mx-auto max-w-7xl px-4">

            {{-- Swiss badge --}}
            <div class="flex flex-col items-center gap-3 text-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white/10">
                    <svg class="h-7 w-7" viewBox="0 0 32 32" fill="none">
                        <path d="M16 1L3 6v9c0 8.5 5.5 16.4 13 18 7.5-1.6 13-9.5 13-18V6L16 1z" fill="#D52B1E"/>
                        <rect x="13.5" y="8" width="5" height="16" rx="1" fill="white"/>
                        <rect x="8" y="13.5" width="16" height="5" rx="1" fill="white"/>
                    </svg>
                </div>
                <p class="max-w-md text-sm leading-relaxed text-white/60">{{ __('Développement, serveurs et stockage de données entièrement sécurisés en Suisse') }} <span class="font-semibold text-white/80">(100% CH)</span></p>
            </div>

            {{-- Separator --}}
            <div class="mx-auto my-8 h-px max-w-xs bg-white/10"></div>

            {{-- Logo + Links --}}
            <div class="flex flex-col items-center gap-6 text-center">
                <img src="{{ asset('assets/brand/BATID_Monogramme_blanc.svg') }}" alt="bat-id" class="h-8 opacity-40">

                <div class="flex items-center gap-6 text-xs">
                    <a href="https://bat-id.ch/terms" target="_blank" class="text-white/50 transition hover:text-white">{{ __('Conditions générales') }}</a>
                    <span class="text-white/20">|</span>
                    <a href="https://bat-id.ch/privacy" target="_blank" class="text-white/50 transition hover:text-white">{{ __('Protection des données') }}</a>
                </div>

                <p class="text-xs text-white/40">&copy; {{ date('Y') }} bat-id.ch &mdash; {{ __('Tous droits réservés') }}</p>
            </div>

        </div>
    </footer>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.1/build/js/intlTelInput.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.1/build/js/utils.js"></script>
</body>
</html>
