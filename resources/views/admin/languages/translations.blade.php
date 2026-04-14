@extends('layouts.admin')
@section('content')
<div class="mb-6"><a href="{{ route('admin.languages.index') }}" class="text-sm text-batid-bleu hover:underline">&larr; Retour</a></div>
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-batid-marine">Traductions — {{ strtoupper($locale) }}</h1>
    <label class="flex items-center gap-2 text-sm" x-data="{ missing: false }">
        <input type="checkbox" x-model="missing" @change="document.querySelectorAll('[data-translated]').forEach(el => { el.style.display = missing && el.dataset.translated === '1' ? 'none' : '' })" class="rounded border-gray-300 text-batid-bleu">
        Manquants uniquement
    </label>
</div>
<div class="space-y-2">
    @foreach($items as $key => $item)
    <div class="flex gap-4 rounded-lg bg-white p-4 shadow-sm ring-1 ring-gray-100" data-translated="{{ !empty($item['translation']) ? '1' : '0' }}">
        <div class="w-1/2">
            <p class="text-xs text-gray-400">FR (source)</p>
            <p class="text-sm text-gray-700">{{ $item['source'] }}</p>
        </div>
        <div class="w-1/2">
            <p class="text-xs text-gray-400">{{ strtoupper($locale) }}</p>
            <input type="text" value="{{ $item['translation'] }}" data-key="{{ $key }}" data-locale="{{ $locale }}"
                   class="w-full rounded border-gray-300 text-sm transition focus:border-batid-bleu focus:ring-batid-bleu {{ empty($item['translation']) ? 'border-yellow-300 bg-yellow-50' : '' }}"
                   onblur="fetch('{{ route('admin.languages.translations.update') }}', {method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({locale:this.dataset.locale,key:this.dataset.key,value:this.value})}).then(()=>{this.classList.remove('border-yellow-300','bg-yellow-50');this.classList.add('border-green-300','bg-green-50');setTimeout(()=>{this.classList.remove('border-green-300','bg-green-50')},1500)})">
        </div>
    </div>
    @endforeach
</div>
@endsection
