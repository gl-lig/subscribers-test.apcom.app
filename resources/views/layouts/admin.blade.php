<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — {{ config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/brand/BATID_Monogramme_degrade_neg_icoNAT.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('head')
</head>
<body class="min-h-screen bg-gray-100 font-sans antialiased" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-50 w-64 transform bg-batid-marine text-white transition-transform lg:relative lg:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="flex h-16 items-center justify-center border-b border-white/10 px-4">
                <img src="{{ asset('assets/brand/BATID_Logo_blanc.svg') }}" alt="bat-id" class="h-8">
            </div>
            @php $isApiUser = auth()->guard('admin')->user()?->isApiUser(); @endphp
            <nav class="mt-4 space-y-1 px-3">
                @if(!$isApiUser)
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-[15px] transition hover:bg-white/10 {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-batid-vert' : 'text-white/80' }}">
                    <i class="fa-solid fa-microchip w-5 text-center"></i> Systeme
                </a>
                <a href="{{ route('admin.subscribers.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-[15px] transition hover:bg-white/10 {{ request()->routeIs('admin.subscribers.*') ? 'bg-white/10 text-batid-vert' : 'text-white/80' }}">
                    <i class="fa-solid fa-users w-5 text-center"></i> Abonnés
                </a>
                <div x-data="{ open: {{ request()->routeIs('admin.subscription-types.*', 'admin.orders.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-[15px] text-white/80 transition hover:bg-white/10">
                        <span class="flex items-center gap-3"><i class="fa-solid fa-box w-5 text-center"></i> Abonnements</span>
                        <svg class="h-4 w-4 transition" :class="open && 'rotate-90'" fill="currentColor" viewBox="0 0 20 20"><path d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"/></svg>
                    </button>
                    <div x-show="open" class="ml-6 space-y-1">
                        <a href="{{ route('admin.subscription-types.index') }}" class="block rounded px-3 py-2 text-[14px] text-white/60 hover:text-white">Types</a>
                        <a href="{{ route('admin.orders.index') }}" class="block rounded px-3 py-2 text-[14px] text-white/60 hover:text-white">Commandes</a>
                    </div>
                </div>
                <div x-data="{ open: {{ request()->routeIs('admin.promo-codes.*', 'admin.user-groups.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-[15px] text-white/80 transition hover:bg-white/10">
                        <span class="flex items-center gap-3"><i class="fa-solid fa-ticket w-5 text-center"></i> Promotions</span>
                        <svg class="h-4 w-4 transition" :class="open && 'rotate-90'" fill="currentColor" viewBox="0 0 20 20"><path d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"/></svg>
                    </button>
                    <div x-show="open" class="ml-6 space-y-1">
                        <a href="{{ route('admin.promo-codes.index') }}" class="block rounded px-3 py-2 text-[14px] text-white/60 hover:text-white">Codes promo</a>
                        <a href="{{ route('admin.user-groups.index') }}" class="block rounded px-3 py-2 text-[14px] text-white/60 hover:text-white">Groupes</a>
                    </div>
                </div>
                <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-[15px] transition hover:bg-white/10 {{ request()->routeIs('admin.payments.*') ? 'bg-white/10 text-batid-vert' : 'text-white/80' }}">
                    <i class="fa-solid fa-credit-card w-5 text-center"></i> Paiements
                </a>
                <a href="{{ route('admin.languages.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-[15px] transition hover:bg-white/10 {{ request()->routeIs('admin.languages.*') ? 'bg-white/10 text-batid-vert' : 'text-white/80' }}">
                    <i class="fa-solid fa-globe w-5 text-center"></i> Langues
                </a>
                <div x-data="{ open: {{ request()->routeIs('admin.admins.*', 'admin.settings.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-[15px] text-white/80 transition hover:bg-white/10">
                        <span class="flex items-center gap-3"><i class="fa-solid fa-gear w-5 text-center"></i> Configuration</span>
                        <svg class="h-4 w-4 transition" :class="open && 'rotate-90'" fill="currentColor" viewBox="0 0 20 20"><path d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"/></svg>
                    </button>
                    <div x-show="open" class="ml-6 space-y-1">
                        <a href="{{ route('admin.admins.index') }}" class="block rounded px-3 py-2 text-[14px] text-white/60 hover:text-white">Administrateurs</a>
                        <a href="{{ route('admin.settings.index') }}" class="block rounded px-3 py-2 text-[14px] text-white/60 hover:text-white">Paramètres</a>
                    </div>
                </div>
                @endif
                <div x-data="{ open: {{ request()->routeIs('admin.logs.*', 'admin.api.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-[15px] text-white/80 transition hover:bg-white/10">
                        <span class="flex items-center gap-3"><i class="fa-solid fa-code w-5 text-center"></i> {{ $isApiUser ? 'API' : 'Logs & API' }}</span>
                        <svg class="h-4 w-4 transition" :class="open && 'rotate-90'" fill="currentColor" viewBox="0 0 20 20"><path d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"/></svg>
                    </button>
                    <div x-show="open" class="ml-6 space-y-1">
                        @if(!$isApiUser)
                        <a href="{{ route('admin.logs.activity') }}" class="block rounded px-3 py-2 text-[14px] text-white/60 hover:text-white">Activité admin</a>
                        @endif
                        <a href="{{ route('admin.logs.api') }}" class="block rounded px-3 py-2 text-[14px] {{ request()->routeIs('admin.logs.api') ? 'text-batid-vert' : 'text-white/60' }} hover:text-white">Journal API</a>
                        <a href="{{ route('admin.api.documentation') }}" class="block rounded px-3 py-2 text-[14px] {{ request()->routeIs('admin.api.documentation') ? 'text-batid-vert' : 'text-white/60' }} hover:text-white">Documentation API</a>
                    </div>
                </div>
            </nav>
        </aside>

        <!-- Main content -->
        <div class="flex flex-1 flex-col">
            <header class="flex h-16 items-center justify-between border-b bg-white px-6">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 lg:hidden">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div></div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.profile.index') }}" class="text-sm text-gray-600 hover:text-batid-bleu" title="Mon profil">
                        <i class="fa-solid fa-user-circle mr-1"></i>{{ auth()->guard('admin')->user()->full_name ?? '' }}
                    </a>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800" title="Déconnexion"><i class="fa-solid fa-right-from-bracket"></i></button>
                    </form>
                </div>
            </header>
            <main class="flex-1 p-6">
                @if(session('success'))
                    <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700">
                        @foreach($errors->all() as $error) <p>{{ $error }}</p> @endforeach
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Sidebar overlay -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/50 lg:hidden"></div>
    @livewireScripts
    @stack('scripts')
</body>
</html>
