@extends('layouts.guest')

@section('content')
<h2 class="mb-2 text-center text-2xl font-bold text-batid-marine">Vérification 2FA</h2>
<p class="mb-6 text-center text-sm text-gray-500">Entrez le code à 6 chiffres de votre application d'authentification.</p>

<form method="POST" action="{{ route('admin.2fa.verify') }}">
    @csrf
    <div class="mb-6">
        <input type="text" name="code" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required autofocus placeholder="000000"
               class="w-full rounded-lg border-gray-300 text-center text-2xl tracking-[0.5em] shadow-sm focus:border-batid-bleu focus:ring-batid-bleu">
        @error('code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <button type="submit" class="w-full rounded-lg bg-batid-bleu px-4 py-3 text-sm font-semibold text-white transition hover:bg-batid-marine">
        Vérifier
    </button>
</form>

<form method="POST" action="{{ route('admin.logout') }}" class="mt-4 text-center">
    @csrf
    <button type="submit" class="text-sm text-gray-500 hover:text-red-600">Déconnexion</button>
</form>
@endsection
