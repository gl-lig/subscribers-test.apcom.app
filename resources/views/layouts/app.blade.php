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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-white font-sans text-batid-marine antialiased">
    <!-- Header -->
    <header class="bg-batid-marine">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('assets/brand/BATID_Logo_Fond-marine_1.svg') }}" alt="bat-id" class="h-10">
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
    <footer class="mt-20 bg-batid-marine py-8 text-white/70">
        <div class="mx-auto max-w-7xl px-4 text-center text-sm">
            <p>&copy; {{ date('Y') }} bat-id.ch &mdash; {{ __('Tous droits réservés') }}</p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
