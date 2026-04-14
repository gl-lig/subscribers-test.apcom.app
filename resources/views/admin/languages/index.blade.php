@extends('layouts.admin')
@section('content')
<h1 class="mb-6 text-2xl font-bold text-batid-marine">Langues & Traductions</h1>
<div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50"><tr>
            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Langue</th>
            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Code</th>
            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Progression</th>
            <th class="px-6 py-3"></th>
        </tr></thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($languages as $lang)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium">{{ ['fr' => 'Français', 'de' => 'Allemand', 'it' => 'Italien', 'en' => 'Anglais'][$lang['locale']] ?? $lang['locale'] }}</td>
                <td class="px-6 py-4 text-sm font-mono">{{ $lang['locale'] }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="h-2 w-32 overflow-hidden rounded-full bg-gray-200">
                            <div class="h-full rounded-full {{ $lang['percentage'] === 100 ? 'bg-green-500' : 'bg-batid-bleu' }}" style="width: {{ $lang['percentage'] }}%"></div>
                        </div>
                        <span class="text-sm text-gray-600">{{ $lang['percentage'] }}% ({{ $lang['translated'] }}/{{ $lang['total'] }})</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.languages.translations', $lang['locale']) }}" class="text-sm text-batid-bleu hover:underline">Traduire</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
