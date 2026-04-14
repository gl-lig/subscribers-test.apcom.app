@extends('layouts.admin')
@section('content')
<h1 class="mb-6 text-2xl font-bold text-batid-marine">Paramètres</h1>
<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
    @csrf
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <div class="grid gap-4 sm:grid-cols-2">
            <div><label class="mb-1 block text-sm font-medium text-gray-700">URL des CGV</label><input type="url" name="cgv_url" value="{{ $settings['cgv_url'] }}" class="w-full rounded-lg border-gray-300 text-sm"></div>
            <div><label class="mb-1 block text-sm font-medium text-gray-700">Délai notification expiration (jours)</label><input type="number" name="expiry_notification_days" value="{{ $settings['expiry_notification_days'] }}" class="w-full rounded-lg border-gray-300 text-sm"></div>
            <div><label class="mb-1 block text-sm font-medium text-gray-700">Timeout session admin (minutes)</label><input type="number" name="admin_session_timeout" value="{{ $settings['admin_session_timeout'] }}" class="w-full rounded-lg border-gray-300 text-sm"></div>
            <div><label class="mb-1 block text-sm font-medium text-gray-700">Email expéditeur</label><input type="email" name="mail_from_address" value="{{ $settings['mail_from_address'] }}" class="w-full rounded-lg border-gray-300 text-sm"></div>
            <div class="sm:col-span-2"><label class="flex items-center gap-2 text-sm"><input type="checkbox" name="maintenance_mode" value="1" {{ $settings['maintenance_mode'] === 'true' ? 'checked' : '' }} class="rounded border-gray-300 text-batid-bleu"> Mode maintenance</label></div>
        </div>
    </div>
    <button type="submit" class="rounded-lg bg-batid-bleu px-6 py-3 text-sm font-semibold text-white hover:bg-batid-marine">Sauvegarder</button>
</form>
@endsection
