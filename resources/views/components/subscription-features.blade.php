@props(['type', 'compact' => false])

@php
    $iconSize = $compact ? 'text-sm' : 'text-base';
    $textSize = $compact ? 'text-xs' : 'text-sm';
    $gap = $compact ? 'gap-2' : 'gap-3';
    $spacing = $compact ? 'space-y-2' : 'space-y-3';
@endphp

<ul class="{{ $spacing }}">
    {{-- Parcelles --}}
    <li class="flex items-center {{ $gap }} {{ $textSize }}">
        <i class="fa-solid fa-map {{ $iconSize }} w-5 text-center text-batid-bleu"></i>
        <span>{{ __('Parcelles') }}: <strong>{{ $type->parcelles_unlimited ? '∞' : $type->parcelles_count }}</strong></span>
    </li>

    {{-- Alertes --}}
    <li class="flex items-center {{ $gap }} {{ $textSize }}">
        <i class="fa-solid fa-bell {{ $iconSize }} w-5 text-center text-batid-bleu"></i>
        <span>{{ __('Alertes') }}: <strong>{{ $type->alertes_count }}</strong></span>
    </li>

    {{-- Stockage --}}
    <li class="flex items-center {{ $gap }} {{ $textSize }}">
        <i class="fa-solid fa-hard-drive {{ $iconSize }} w-5 text-center text-batid-bleu"></i>
        <span>{{ __('Stockage') }}: <strong>{{ $type->stockage_unlimited ? '∞' : $type->stockage_go . ' Go' }}</strong></span>
    </li>

    {{-- Cloud externe --}}
    <li class="flex items-start {{ $gap }} {{ $textSize }}">
        <i class="fa-solid fa-cloud {{ $iconSize }} w-5 text-center {{ $type->cloud_externe ? 'text-batid-bleu' : 'text-gray-300' }}" style="margin-top: 2px;"></i>
        <div>
            <span class="{{ $type->cloud_externe ? '' : 'text-gray-400' }}">{{ __('Cloud externe') }}</span>
            @if($type->cloud_externe)
            <div class="mt-1.5 flex flex-wrap items-center gap-2">
                {{-- kDrive (Infomaniak) --}}
                <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-medium text-gray-600" title="kDrive">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#0050FF"/><path d="M7 8h4l3 4-3 4H7l3-4L7 8z" fill="white"/><path d="M13 8h4l-3 4 3 4h-4l-3-4 3-4z" fill="#3DFF9E"/></svg>
                    kDrive
                </span>
                {{-- Google Drive --}}
                <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-medium text-gray-600" title="Google Drive">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24"><path d="M4.433 22l2.929-5H22l-2.929 5H4.433z" fill="#4285F4"/><path d="M14.571 7l-7.143 0L2 17l2.929 0 5.214-10H14.571z" fill="#0F9D58"/><path d="M9.571 7L14.571 17 22 17 17 7H9.571z" fill="#F4B400"/></svg>
                    Drive
                </span>
                {{-- OneDrive --}}
                <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-medium text-gray-600" title="OneDrive">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24"><path d="M10.5 8.5c1.7-1.4 4.2-1.4 5.8 0 .2.1.3.3.5.4 1.8-.5 3.7.1 4.8 1.5.8 1 1.1 2.3.8 3.5H2.6c-.8-1.5-.5-3.4.8-4.5 1-1 2.5-1.3 3.8-.8.7-1.2 1.9-2 3.3-2.1z" fill="#0078D4"/></svg>
                    OneDrive
                </span>
                {{-- Dropbox --}}
                <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-medium text-gray-600" title="Dropbox">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24"><path d="M12 6.8L7 10l5 3.2L7 16.4 2 13.2l5-3.2L2 6.8 7 3.6l5 3.2zm0 0L17 3.6l5 3.2-5 3.2 5 3.2-5 3.2-5-3.2z" fill="#0061FF"/></svg>
                    Dropbox
                </span>
                {{-- Proton Drive --}}
                <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-medium text-gray-600" title="Proton Drive">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24"><rect width="24" height="24" rx="4" fill="#6D4AFF"/><path d="M7 7h10l-3 5 3 5H7l3-5-3-5z" fill="white"/></svg>
                    Proton
                </span>
            </div>
            @endif
        </div>
    </li>

    {{-- Lot de sauvegarde --}}
    <li class="flex items-center {{ $gap }} {{ $textSize }}">
        <i class="fa-solid fa-box-archive {{ $iconSize }} w-5 text-center {{ $type->lot_sauvegarde ? 'text-batid-bleu' : 'text-gray-300' }}"></i>
        <span class="{{ $type->lot_sauvegarde ? '' : 'text-gray-400' }}">{{ __('Lot de sauvegarde') }}</span>
    </li>

    {{-- Workspace --}}
    @if($type->workspace_enabled)
    <li class="flex items-center {{ $gap }} {{ $textSize }}">
        <i class="fa-solid fa-users {{ $iconSize }} w-5 text-center text-batid-bleu"></i>
        <span>{{ __('Workspace') }}: <strong>{{ $type->workspace_unlimited ? '∞' : $type->workspace_count }}</strong></span>
    </li>
    @else
    <li class="flex items-center {{ $gap }} {{ $textSize }}">
        <i class="fa-solid fa-users {{ $iconSize }} w-5 text-center text-gray-300"></i>
        <span class="text-gray-400">{{ __('Workspace') }}</span>
    </li>
    @endif
</ul>
