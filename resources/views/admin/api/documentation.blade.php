@extends('layouts.admin')
@section('content')
<div class="mx-auto max-w-4xl">
    <h1 class="mb-2 text-2xl font-bold text-batid-marine">Documentation API — Deeplink</h1>
    <p class="mb-6 text-sm text-gray-500">Documentation technique pour l'integration depuis l'application mobile bat-id</p>

    {{-- Status --}}
    <div class="mb-6 rounded-xl p-4 {{ $secretConfigured ? 'bg-green-50 ring-1 ring-green-200' : 'bg-red-50 ring-1 ring-red-200' }}">
        <div class="flex items-center gap-2">
            @if($secretConfigured)
                <span class="inline-block h-2.5 w-2.5 rounded-full bg-green-500"></span>
                <span class="text-sm font-medium text-green-800">DEEPLINK_SECRET configure — deeplink actif</span>
            @else
                <span class="inline-block h-2.5 w-2.5 rounded-full bg-red-500"></span>
                <span class="text-sm font-medium text-red-800">DEEPLINK_SECRET non configure dans .env — deeplink inactif</span>
            @endif
        </div>
    </div>

    {{-- Principe --}}
    <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h2 class="mb-3 text-lg font-semibold text-batid-marine">Principe</h2>
        <p class="mb-3 text-sm text-gray-700">L'application mobile bat-id peut rediriger un utilisateur directement vers le panier d'achat d'abonnement, sans qu'il ait besoin de choisir un abonnement ni de saisir son numero de telephone.</p>
        <p class="text-sm text-gray-700">Le lien contient un <strong>token signe</strong> (HMAC-SHA256) qui inclut le bat-ID de l'utilisateur, son numero de telephone, le type d'abonnement souhaite et la duree. Aucun appel API supplementaire n'est necessaire — les informations du token font foi. Le token expire apres <strong>{{ config('batid.deeplink_ttl', 600) / 60 }} minutes</strong>.</p>
    </div>

    {{-- URL --}}
    <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h2 class="mb-3 text-lg font-semibold text-batid-marine">URL du deeplink</h2>
        <div class="rounded-lg bg-gray-900 p-4">
            <code class="text-sm text-green-400">GET {{ $baseUrl }}/deeplink?token={TOKEN}</code>
        </div>
    </div>

    {{-- Token --}}
    <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h2 class="mb-3 text-lg font-semibold text-batid-marine">Construction du token</h2>
        <p class="mb-3 text-sm text-gray-700">Le token se compose de deux parties separees par un point :</p>
        <div class="rounded-lg bg-gray-900 p-4 mb-4">
            <code class="text-sm text-green-400">base64url(payload).hmac_sha256(base64url(payload), secret)</code>
        </div>

        <h3 class="mb-2 text-sm font-semibold text-gray-700">Payload JSON</h3>
        <div class="rounded-lg bg-gray-900 p-4 mb-4">
<pre class="text-sm text-green-400">{
  "p": "+41791234567",   // numero de telephone (format international)
  "b": "BAT-ID-0001",   // identifiant bat-id de l'utilisateur
  "t": 2,                // ID du type d'abonnement (voir tableau ci-dessous)
  "d": 12,               // duree en mois : 12, 24 ou 36 (defaut: 12)
  "ts": 1713200000       // timestamp Unix (secondes)
}</pre>
        </div>

        <h3 class="mb-2 text-sm font-semibold text-gray-700">Algorithme</h3>
        <ol class="ml-4 list-decimal space-y-1 text-sm text-gray-700">
            <li>Construire le payload JSON</li>
            <li>Encoder en <strong>base64url</strong> (base64 avec <code class="rounded bg-gray-100 px-1">+/</code> remplaces par <code class="rounded bg-gray-100 px-1">-_</code>, sans padding <code class="rounded bg-gray-100 px-1">=</code>)</li>
            <li>Calculer le HMAC-SHA256 de la chaine base64url avec la <strong>cle secrete partagee</strong></li>
            <li>Concatener : <code class="rounded bg-gray-100 px-1">base64url.signature_hex</code></li>
        </ol>
    </div>

    {{-- Types disponibles --}}
    <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h2 class="mb-3 text-lg font-semibold text-batid-marine">Types d'abonnement disponibles</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50"><tr>
                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-gray-500">ID (valeur "t")</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-gray-500">Nom</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-gray-500">Prix CHF/an</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-gray-500">Statut</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($types as $type)
                    <tr>
                        <td class="px-4 py-2 text-sm font-mono font-bold text-batid-bleu">{{ $type->id }}</td>
                        <td class="px-4 py-2 text-sm">{{ $type->translation('fr')?->name ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm">{{ number_format($type->price_chf, 2) }}</td>
                        <td class="px-4 py-2"><span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">En ligne</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Code examples --}}
    <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h2 class="mb-3 text-lg font-semibold text-batid-marine">Exemples d'implementation</h2>

        <h3 class="mb-2 mt-4 text-sm font-semibold text-gray-700">PHP</h3>
        <div class="rounded-lg bg-gray-900 p-4 mb-4">
<pre class="text-sm text-green-400">$secret = 'VOTRE_CLE_SECRETE_PARTAGEE';

$payload = json_encode([
    'p' => '+41791234567',
    'b' => 'BAT-ID-0001',
    't' => 2,
    'd' => 12,
    'ts' => time(),
]);

$encoded = rtrim(strtr(base64_encode($payload), '+/', '-_'), '=');
$signature = hash_hmac('sha256', $encoded, $secret);
$token = $encoded . '.' . $signature;

$url = '{{ $baseUrl }}/deeplink?token=' . $token;</pre>
        </div>

        <h3 class="mb-2 text-sm font-semibold text-gray-700">JavaScript / Node.js</h3>
        <div class="rounded-lg bg-gray-900 p-4 mb-4">
<pre class="text-sm text-green-400">const crypto = require('crypto');

const secret = 'VOTRE_CLE_SECRETE_PARTAGEE';

const payload = JSON.stringify({
  p: '+41791234567',
  b: 'BAT-ID-0001',
  t: 2,
  d: 12,
  ts: Math.floor(Date.now() / 1000)
});

const encoded = Buffer.from(payload)
  .toString('base64')
  .replace(/\+/g, '-')
  .replace(/\//g, '_')
  .replace(/=+$/, '');

const signature = crypto
  .createHmac('sha256', secret)
  .update(encoded)
  .digest('hex');

const url = `{{ $baseUrl }}/deeplink?token=${encoded}.${signature}`;</pre>
        </div>

        <h3 class="mb-2 text-sm font-semibold text-gray-700">Dart / Flutter</h3>
        <div class="rounded-lg bg-gray-900 p-4 mb-4">
<pre class="text-sm text-green-400">import 'dart:convert';
import 'package:crypto/crypto.dart';

final secret = 'VOTRE_CLE_SECRETE_PARTAGEE';

final payload = jsonEncode({
  'p': '+41791234567',
  'b': 'BAT-ID-0001',
  't': 2,
  'd': 12,
  'ts': DateTime.now().millisecondsSinceEpoch ~/ 1000,
});

final encoded = base64Url.encode(utf8.encode(payload)).replaceAll('=', '');
final hmac = Hmac(sha256, utf8.encode(secret));
final signature = hmac.convert(utf8.encode(encoded)).toString();

final url = '{{ $baseUrl }}/deeplink?token=$encoded.$signature';</pre>
        </div>
    </div>

    {{-- Comportement --}}
    <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h2 class="mb-3 text-lg font-semibold text-batid-marine">Comportement</h2>
        <div class="space-y-3 text-sm text-gray-700">
            <div class="flex gap-3">
                <span class="mt-0.5 inline-block h-5 w-5 flex-shrink-0 rounded-full bg-green-100 text-center text-xs leading-5 text-green-800">1</span>
                <p><strong>Token valide</strong> — L'utilisateur arrive directement sur le panier avec l'abonnement pre-selectionne. Il n'a plus qu'a confirmer et payer.</p>
            </div>
            <div class="flex gap-3">
                <span class="mt-0.5 inline-block h-5 w-5 flex-shrink-0 rounded-full bg-yellow-100 text-center text-xs leading-5 text-yellow-800">2</span>
                <p><strong>Upgrade impossible</strong> — Si l'utilisateur a deja un abonnement de valeur egale ou superieure, le systeme affiche le message d'erreur standard et lui propose de choisir un autre abonnement.</p>
            </div>
            <div class="flex gap-3">
                <span class="mt-0.5 inline-block h-5 w-5 flex-shrink-0 rounded-full bg-red-100 text-center text-xs leading-5 text-red-800">3</span>
                <p><strong>Token invalide ou expire</strong> — L'utilisateur est redirige vers la page d'accueil et suit le parcours standard (choix d'abonnement + saisie telephone).</p>
            </div>
        </div>
    </div>

    {{-- Securite --}}
    <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h2 class="mb-3 text-lg font-semibold text-batid-marine">Securite</h2>
        <ul class="ml-4 list-disc space-y-1 text-sm text-gray-700">
            <li>Le token est signe avec HMAC-SHA256 — toute modification invalide la signature</li>
            <li>Le token expire apres {{ config('batid.deeplink_ttl', 600) / 60 }} minutes (configurable)</li>
            <li>Le bat-ID et le telephone proviennent du token signe — aucun appel API intermediaire</li>
            <li>La cle secrete doit etre transmise de maniere securisee et ne jamais etre exposee cote client</li>
            <li>Le token doit etre genere cote serveur (backend bat-id), jamais dans l'application mobile directement</li>
        </ul>
    </div>

    {{-- Test token generator --}}
    @if($secretConfigured)
    <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h2 class="mb-3 text-lg font-semibold text-batid-marine">Generateur de test</h2>
        <form method="GET" action="{{ route('admin.api.test-token') }}" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="mb-1 block text-xs text-gray-500">Telephone</label>
                <input type="text" name="phone" value="{{ config('batid.mock_phone') }}" class="rounded-lg border-gray-300 text-sm" />
            </div>
            <div>
                <label class="mb-1 block text-xs text-gray-500">bat-ID</label>
                <input type="text" name="bat_id" value="{{ config('batid.mock_batid') }}" class="rounded-lg border-gray-300 text-sm" />
            </div>
            <div>
                <label class="mb-1 block text-xs text-gray-500">Type ID</label>
                <select name="type_id" class="rounded-lg border-gray-300 text-sm">
                    @foreach($types as $type)
                    <option value="{{ $type->id }}">{{ $type->id }} — {{ $type->translation('fr')?->name ?? '-' }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs text-gray-500">Duree (mois)</label>
                <select name="duration" class="rounded-lg border-gray-300 text-sm">
                    <option value="12">12</option>
                    <option value="24">24</option>
                    <option value="36">36</option>
                </select>
            </div>
            <button type="submit" class="rounded-lg bg-batid-bleu px-4 py-2 text-sm text-white hover:bg-batid-marine">Generer un token test</button>
        </form>

        @if(session('test_url'))
        <div class="mt-4 space-y-3">
            <div x-data="{ copied: false }">
                <p class="mb-1.5 text-xs font-semibold text-gray-500">URL generee</p>
                <div class="flex items-center gap-2 rounded-lg bg-gray-900 p-3 cursor-pointer group"
                     @click="navigator.clipboard.writeText('{{ session('test_url') }}'); copied = true; setTimeout(() => copied = false, 2000)">
                    <div class="flex-1 min-w-0 overflow-x-auto">
                        <code class="whitespace-nowrap text-sm text-green-400">{{ session('test_url') }}</code>
                    </div>
                    <span x-show="!copied" class="flex-shrink-0 text-gray-400 group-hover:text-white transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </span>
                    <span x-show="copied" x-cloak class="flex-shrink-0 text-sm font-medium text-green-400">Copie !</span>
                </div>
            </div>
            <a href="{{ session('test_url') }}" target="_blank" class="inline-flex items-center gap-1.5 text-sm text-batid-bleu hover:underline">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Ouvrir dans un nouvel onglet
            </a>
        </div>
        @endif
    </div>
    @endif
</div>
@endsection
