@extends('layouts.admin')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-batid-marine">Groupes d'utilisateurs</h1>
    <a href="{{ route('admin.user-groups.create') }}" class="rounded-lg bg-batid-bleu px-4 py-2 text-sm font-medium text-white hover:bg-batid-marine">Nouveau groupe</a>
</div>

<div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Nom</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Description</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Membres</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($groups as $group)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium text-batid-marine">{{ $group->name }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($group->description, 60) }}</td>
                <td class="px-6 py-4 text-sm">{{ $group->member_count }}</td>
                <td class="px-6 py-4 text-right space-x-2">
                    <a href="{{ route('admin.user-groups.edit', $group) }}" class="text-sm text-batid-bleu hover:underline">Modifier</a>
                    <form method="POST" action="{{ route('admin.user-groups.destroy', $group) }}" class="inline" onsubmit="return confirm('Supprimer ?')">@csrf @method('DELETE')<button class="text-sm text-red-600 hover:underline">Supprimer</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-6 py-8 text-center text-sm text-gray-400">Aucun groupe.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
