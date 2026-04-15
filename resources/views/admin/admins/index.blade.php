@extends('layouts.admin')
@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-batid-marine">Administrateurs</h1>
    <a href="{{ route('admin.admins.create') }}" class="rounded-lg bg-batid-bleu px-4 py-2 text-sm font-medium text-white hover:bg-batid-marine">Nouvel admin</a>
</div>
<div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50"><tr>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Nom</th>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Email</th>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Rôle</th>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Statut</th>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Dernière connexion</th>
            <th class="px-4 py-3"></th>
        </tr></thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($admins as $admin)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm font-medium">{{ $admin->full_name }}</td>
                <td class="px-4 py-3 text-sm text-gray-600">{{ $admin->email }}</td>
                <td class="px-4 py-3">
                    @if($admin->role === 'super_admin')<span class="rounded-full bg-purple-100 px-2 py-0.5 text-xs font-medium text-purple-800">Super Admin</span>
                    @elseif($admin->role === 'api_user')<span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-800">API User</span>
                    @else<span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">Admin</span>@endif
                </td>
                <td class="px-4 py-3">
                    @if($admin->status === 'active')<span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Actif</span>
                    @else<span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">Inactif</span>@endif
                </td>
                <td class="px-4 py-3 text-sm text-gray-500">{{ $admin->last_login_at?->format('d.m.Y H:i') ?? 'Jamais' }}</td>
                <td class="px-4 py-3 text-right"><a href="{{ route('admin.admins.edit', $admin) }}" class="text-batid-bleu hover:text-batid-marine" title="Modifier"><i class="fa-solid fa-pen"></i></a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
