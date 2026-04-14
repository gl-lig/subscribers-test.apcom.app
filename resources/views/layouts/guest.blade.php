<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — {{ config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/brand/BATID_Monogramme_degrade_neg_icoNAT.svg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center font-sans antialiased" style="background: linear-gradient(to top right, #3DFF9E 0%, #38F3A4 6%, #2DD3B6 17%, #1B9FD2 32%, #0050FF 52%, #00004D 84%);">
    <div class="w-full max-w-md">
        <div class="mb-8 text-center">
            <img src="{{ asset('assets/brand/BATID_Logo_blanc.svg') }}" alt="bat-id" class="mx-auto h-12">
        </div>
        <div class="rounded-2xl bg-white p-8 shadow-2xl">
            @yield('content')
        </div>
    </div>
</body>
</html>
