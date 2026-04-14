@props(['type', 'compact' => false])

@php
    $s = $compact ? 'text-xs' : 'text-sm';
    $ico = $compact ? 'text-sm' : 'text-base';
    $sp = $compact ? 'space-y-2' : 'space-y-3';
    $logoH = $compact ? 'h-4 w-4' : 'h-5 w-5';
    $icoColor = 'text-gray-300';
    $icoOff = 'text-gray-200';
@endphp

<ul class="{{ $sp }}">
    <li class="flex items-center gap-3 {{ $s }}">
        <i class="fa-regular fa-map {{ $ico }} w-5 text-center {{ $icoColor }}"></i>
        <span>{{ __('Parcelles') }} <strong class="text-batid-marine">{{ $type->parcelles_unlimited ? __('Illimité') : $type->parcelles_count }}</strong></span>
    </li>

    <li class="flex items-center gap-3 {{ $s }}">
        <i class="fa-regular fa-bell {{ $ico }} w-5 text-center {{ $icoColor }}"></i>
        <span>{{ __('Alertes') }} <strong class="text-batid-marine">{{ $type->alertes_count }}</strong></span>
    </li>

    <li class="flex items-center gap-3 {{ $s }}">
        <i class="fa-regular fa-hard-drive {{ $ico }} w-5 text-center {{ $icoColor }}"></i>
        <span>{{ __('Stockage') }} <strong class="text-batid-marine">{{ $type->stockage_unlimited ? __('Illimité') : $type->stockage_go . ' Go' }}</strong></span>
    </li>

    <li class="{{ $s }}">
        <div class="flex items-center gap-3 {{ $s }}">
            <i class="fa-regular fa-cloud {{ $ico }} w-5 text-center {{ $type->cloud_externe ? $icoColor : $icoOff }}"></i>
            <span class="{{ $type->cloud_externe ? '' : 'text-gray-400' }}">{{ __('Cloud externe') }}</span>
        </div>
        @if($type->cloud_externe)
        <div class="ml-8 mt-1.5 flex items-center gap-2.5">
            <img src="https://www.infomaniak.com/favicon.ico" alt="kDrive" style="width:14px;height:14px;" class="flex-shrink-0 rounded-sm object-contain" title="kDrive">
            <svg style="width:14px;height:14px;" class="flex-shrink-0" viewBox="0 0 87.3 78"><path d="M6.6 66.85 3.3 61.35 29.1 17.1h26.5z" fill="#0066DA"/><path d="M29.1 17.1l26.5 0 28.8 49.75H57.3z" fill="#00AC47"/><path d="M31.2 66.85l-24.6 0L29.1 17.1l26.5 0z" fill="#0066DA"/><path d="M84.4 66.85H31.2L55.6 17.1h0z" fill="#00AC47"/><path d="M6.6 66.85H31.2L29.2 78z" fill="#2684FC"/><path d="M31.2 66.85l53.2 0L58.2 78H29.2z" fill="#FFBA00"/></svg>
            <svg style="width:14px;height:14px;" class="flex-shrink-0" viewBox="0 0 24 18"><path d="M14.22 4.5a5.9 5.9 0 00-4.6 2.17 4.46 4.46 0 00-2.52-.67 4.52 4.52 0 00-4.26 3.06A3.58 3.58 0 000 12.54 3.46 3.46 0 003.46 16h5.27l6.36-6.36a4.84 4.84 0 01-.87-5.14z" fill="#0364B8"/><path d="M9.41 7.22a5.88 5.88 0 014.81-2.72 5.88 5.88 0 014.88 2.59A4.36 4.36 0 0124 11.26 4.36 4.36 0 0119.64 16H8.73l6.36-6.36a4.84 4.84 0 00-5.68-2.42z" fill="#0078D4"/><path d="M9.41 7.22l-.79.43a4.84 4.84 0 006.47 6.99L8.73 16H3.46A3.46 3.46 0 010 12.54a3.58 3.58 0 012.84-3.48A4.51 4.51 0 019.41 7.22z" fill="#1490DF"/><path d="M15.09 9.65a4.84 4.84 0 01-.47 5L8.73 16h10.91A4.36 4.36 0 0024 11.26a4.36 4.36 0 00-4.67-4.17 5.88 5.88 0 00-4.24 2.56z" fill="#28A8EA"/></svg>
            <svg style="width:14px;height:14px;" class="flex-shrink-0" viewBox="0 0 43.35 40.4"><path d="M12.87 0L0 8.1l8.8 7.06 12.87-8.4zM0 22.22l12.87 8.1 8.8-7.06-12.87-8.4zM21.67 23.26l8.8 7.06 12.88-8.1-8.81-7.06zM43.35 8.1L30.47 0l-8.8 7.06 12.87 8.1zM21.72 25.16l-8.85 6.98-4.02-2.63v2.95l12.87 7.73 12.87-7.73v-2.95l-4.02 2.63z" fill="#0061FF"/></svg>
            <svg style="width:14px;height:14px;" class="flex-shrink-0" viewBox="0 0 36 36"><rect width="36" height="36" rx="8" fill="#6D4AFF"/><path d="M9 10h14.5L18 18l5.5 8H9l5.5-8z" fill="white"/></svg>
        </div>
        @endif
    </li>

    <li class="flex items-center gap-3 {{ $s }}">
        <i class="fa-regular fa-shield-halved {{ $ico }} w-5 text-center {{ $type->lot_sauvegarde ? $icoColor : $icoOff }}"></i>
        <span class="{{ $type->lot_sauvegarde ? '' : 'text-gray-400' }}">{{ __('Lot de sauvegarde') }}</span>
    </li>

    @if($type->workspace_enabled)
    <li class="flex items-center gap-3 {{ $s }}">
        <i class="fa-regular fa-user-group {{ $ico }} w-5 text-center {{ $icoColor }}"></i>
        <span>{{ __('Workspace') }} <strong class="text-batid-marine">{{ $type->workspace_unlimited ? __('Illimité') : $type->workspace_count }}</strong></span>
    </li>
    @else
    <li class="flex items-center gap-3 {{ $s }}">
        <i class="fa-regular fa-user-group {{ $ico }} w-5 text-center {{ $icoOff }}"></i>
        <span class="text-gray-400">{{ __('Workspace') }}</span>
    </li>
    @endif
</ul>
