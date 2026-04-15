@extends('layouts.admin')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-batid-marine">Systeme</h1>
        <p class="text-sm text-gray-500">Surveillance des tests automatises — {{ $total }} tests</p>
    </div>
    <div class="flex items-center gap-4">
        @if($lastRun)
            <span class="text-xs text-gray-400">Dernier lancement : {{ \Carbon\Carbon::parse($lastRun)->format('d.m.Y H:i') }}</span>
        @endif
        <form method="POST" action="{{ route('admin.dashboard.run-tests') }}">
            @csrf
            <button type="submit" class="rounded-lg bg-batid-bleu px-4 py-2 text-sm font-medium text-white hover:bg-batid-marine transition">
                <i class="fa-solid fa-play mr-1.5"></i>Lancer les tests
            </button>
        </form>
    </div>
</div>

{{-- Resume global --}}
<div class="mb-8 grid grid-cols-4 gap-4">
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100 text-center">
        <div class="text-3xl font-bold text-batid-marine">{{ $total }}</div>
        <div class="mt-1 text-xs font-medium text-gray-500">Tests total</div>
    </div>
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100 text-center">
        <div class="text-3xl font-bold text-green-600">{{ $totalPassed }}</div>
        <div class="mt-1 text-xs font-medium text-gray-500">Passes</div>
    </div>
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100 text-center {{ $totalFailed > 0 ? 'ring-red-200 bg-red-50' : '' }}">
        <div class="text-3xl font-bold {{ $totalFailed > 0 ? 'text-red-600' : 'text-gray-300' }}">{{ $totalFailed }}</div>
        <div class="mt-1 text-xs font-medium text-gray-500">Echoues</div>
    </div>
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100 text-center">
        <div class="text-3xl font-bold {{ $totalPending > 0 ? 'text-yellow-500' : 'text-gray-300' }}">{{ $totalPending }}</div>
        <div class="mt-1 text-xs font-medium text-gray-500">En attente</div>
    </div>
</div>

@if($total === 0)
    <div class="rounded-xl bg-yellow-50 p-8 ring-1 ring-yellow-200 text-center">
        <i class="fa-solid fa-flask text-4xl text-yellow-400 mb-3"></i>
        <p class="text-sm font-medium text-yellow-800">Aucun resultat de test disponible.</p>
        <p class="mt-1 text-xs text-yellow-600">Cliquez sur "Lancer les tests" pour executer la suite PHPUnit.</p>
    </div>
@else
    {{-- Tableaux par groupe --}}
    <div class="space-y-6">
        @foreach($results as $group => $tests)
            @php
                $meta = $groupLabels[$group] ?? ['label' => $group, 'icon' => 'fa-vial'];
                $groupPassed = $tests->where('status', 'passed')->count();
                $groupTotal = $tests->count();
                $allPassed = $groupPassed === $groupTotal;
            @endphp
            <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-100 overflow-hidden">
                {{-- Header du groupe --}}
                <div class="border-b border-gray-100 px-5 py-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-7 w-7 items-center justify-center rounded-md {{ $allPassed ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                <i class="fa-solid {{ $meta['icon'] }} text-xs"></i>
                            </div>
                            <h2 class="text-sm font-bold text-batid-marine">{{ $meta['label'] }}</h2>
                        </div>
                        <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $allPassed ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $groupPassed }}/{{ $groupTotal }}
                        </span>
                    </div>
                    @if(!empty($meta['desc']))
                    <p class="mt-1.5 text-xs text-gray-500 leading-relaxed ml-[38px]">{{ $meta['desc'] }}</p>
                    @endif
                </div>

                {{-- Lignes compactes --}}
                <div class="divide-y divide-gray-50">
                    @foreach($tests as $test)
                    <div class="flex items-center gap-2 px-5 py-1.5 text-xs {{ $test->status === 'failed' ? 'bg-red-50/40' : 'hover:bg-gray-50/50' }} transition">
                        @if($test->status === 'passed')
                            <span class="inline-block h-2 w-2 flex-shrink-0 rounded-full bg-green-500"></span>
                        @elseif($test->status === 'failed')
                            <span class="inline-block h-2 w-2 flex-shrink-0 rounded-full bg-red-500"></span>
                        @else
                            <span class="inline-block h-2 w-2 flex-shrink-0 rounded-full bg-yellow-400"></span>
                        @endif
                        <span class="text-gray-500 flex-1 truncate">{{ $test->comment ?? str_replace('test_', '', $test->test_name) }}</span>
                        @if($test->status === 'failed' && $test->error_message)
                            <span class="text-[10px] text-red-500 font-mono truncate max-w-[250px]" title="{{ $test->error_message }}">{{ \Illuminate\Support\Str::limit($test->error_message, 60) }}</span>
                        @endif
                        @if($test->last_run_at)
                            <span class="text-[10px] text-gray-400 flex-shrink-0 w-24 text-right">{{ $test->last_run_at->format('d.m.Y H:i') }}</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
