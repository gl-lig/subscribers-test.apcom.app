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
    <header class="bg-batid-marine">
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
    <footer class="mt-20 border-t border-gray-100 bg-white py-10">
        <div class="mx-auto max-w-2xl px-6">

            {{-- Suisse --}}
            <div class="flex items-center justify-center gap-3">
                <svg style="width:28px;height:28px;flex-shrink:0;" viewBox="0 0 32 32" fill="none">
                    <path d="M16 1L3 6v9c0 8.5 5.5 16.4 13 18 7.5-1.6 13-9.5 13-18V6L16 1z" fill="#D52B1E"/>
                    <rect x="13.5" y="8" width="5" height="16" rx="1" fill="white"/>
                    <rect x="8" y="13.5" width="16" height="5" rx="1" fill="white"/>
                </svg>
                <p class="text-xs leading-snug text-gray-500">{{ __('Développement, serveurs et stockage de données entièrement sécurisés en Suisse') }} <strong class="text-gray-700">(100% CH)</strong></p>
            </div>

            {{-- Liens + copyright --}}
            <div class="mt-6 flex flex-col items-center gap-3">
                <div class="flex items-center gap-4 text-xs text-gray-400">
                    <a href="https://bat-id.ch/terms" target="_blank" class="transition hover:text-batid-bleu">{{ __('Conditions générales') }}</a>
                    <span>·</span>
                    <a href="https://bat-id.ch/privacy" target="_blank" class="transition hover:text-batid-bleu">{{ __('Protection des données') }}</a>
                </div>
                <p class="text-[11px] text-gray-300">&copy; {{ date('Y') }} bat-id.ch</p>
            </div>

        </div>
    </footer>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.1/build/js/intlTelInput.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.1/build/js/utils.js"></script>
</body>
</html>
