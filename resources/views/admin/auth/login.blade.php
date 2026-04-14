@extends('layouts.guest')

@section('content')
<h2 class="mb-6 text-center text-2xl font-bold text-batid-marine">Administration</h2>

<form method="POST" action="{{ route('admin.login.submit') }}">
    @csrf
    <div class="mb-4">
        <label for="email" class="mb-1 block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-batid-bleu focus:ring-batid-bleu">
        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div class="mb-6">
        <label for="password" class="mb-1 block text-sm font-medium text-gray-700">Mot de passe</label>
        <input type="password" name="password" id="password" required
               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-batid-bleu focus:ring-batid-bleu">
    </div>
    <button type="submit" class="w-full rounded-lg bg-batid-bleu px-4 py-3 text-sm font-semibold text-white transition hover:bg-batid-marine">
        Se connecter
    </button>
</form>
@endsection
