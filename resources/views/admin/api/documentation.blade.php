@extends('layouts.admin')
@section('content')
<div class="mx-auto max-w-4xl" x-data="{ tab: '{{ session('register_url') ? 'register' : 'deeplink' }}' }">
    <h1 class="mb-2 text-2xl font-bold text-batid-marine">Documentation API</h1>
    <p class="mb-6 text-sm text-gray-500">Documentation technique pour l'integration depuis l'application mobile bat-id</p>

    {{-- Schema du processus --}}
    <div class="mb-8 rounded-xl bg-white p-8 shadow-sm ring-1 ring-gray-100">
        <h2 class="mb-2 text-lg font-semibold text-batid-marine">Vue d'ensemble du processus</h2>
        <p class="mb-8 text-sm text-gray-500">Les 3 interactions entre bat-id et subscribers</p>

        {{-- Etape 1 : Inscription --}}
        <div class="rounded-xl bg-blue-50 p-5 ring-1 ring-blue-200">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-white text-xs font-bold">1</div>
                <span class="text-sm font-bold text-blue-800">INSCRIPTION</span>
                <span class="rounded bg-blue-200 px-2 py-0.5 text-[10px] font-semibold text-blue-700">API 2 — Onglet Inscription</span>
            </div>
            <div class="flex items-center justify-center gap-6">
                <div class="rounded-xl bg-white px-5 py-4 shadow-sm ring-1 ring-blue-200 text-center min-w-[100px]">
                    <i class="fa-solid fa-mobile-screen text-2xl text-gray-600 mb-2"></i>
                    <p class="text-xs font-bold text-gray-700">Backend</p>
                    <p class="text-xs font-bold text-gray-700">bat-id</p>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <div class="flex items-center gap-2">
                        <div class="h-px w-8 bg-blue-400"></div>
                        <i class="fa-solid fa-arrow-right text-blue-600 text-lg"></i>
                        <div class="h-px w-8 bg-blue-400"></div>
                    </div>
                    <span class="rounded bg-white px-2 py-0.5 text-[10px] font-semibold text-blue-600 ring-1 ring-blue-200">token signe</span>
                </div>
                <div class="rounded-xl bg-white px-5 py-4 shadow-sm ring-1 ring-blue-200 text-center min-w-[100px]">
                    <i class="fa-solid fa-server text-2xl text-batid-bleu mb-2"></i>
                    <p class="text-xs font-bold text-gray-700">Subscribers</p>
                    <p class="text-xs font-bold text-gray-700">bat-i.ch</p>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <div class="flex items-center gap-2">
                        <div class="h-px w-8 bg-blue-400"></div>
                        <i class="fa-solid fa-arrow-right text-blue-600 text-lg"></i>
                        <div class="h-px w-8 bg-blue-400"></div>
                    </div>
                    <span class="rounded bg-white px-2 py-0.5 text-[10px] font-semibold text-green-600 ring-1 ring-green-200">JSON 201</span>
                </div>
                <div class="rounded-xl bg-green-50 px-5 py-4 shadow-sm ring-1 ring-green-200 text-center min-w-[100px]">
                    <i class="fa-solid fa-user-check text-2xl text-green-600 mb-2"></i>
                    <p class="text-xs font-bold text-gray-700">Abonne</p>
                    <p class="text-xs font-bold text-gray-700">cree</p>
                </div>
            </div>
            <p class="mt-4 text-xs text-gray-600 text-center">A chaque creation de compte dans l'app bat-id, le backend appelle notre API pour creer l'abonne correspondant.</p>
        </div>

        {{-- Separateur --}}
        <div class="flex justify-center py-4">
            <div class="flex flex-col items-center">
                <div class="h-6 w-px bg-gray-300"></div>
                <i class="fa-solid fa-chevron-down text-gray-300 text-xs"></i>
            </div>
        </div>

        {{-- Etape 2 : Deeplink --}}
        <div class="rounded-xl bg-green-50 p-5 ring-1 ring-green-200">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-600 text-white text-xs font-bold">2</div>
                <span class="text-sm font-bold text-green-800">COMMANDE VIA DEEPLINK</span>
                <span class="rounded bg-green-200 px-2 py-0.5 text-[10px] font-semibold text-green-700">API 1 — Onglet Deeplink</span>
            </div>
            <div class="flex items-center justify-center gap-5">
                <div class="rounded-xl bg-white px-5 py-4 shadow-sm ring-1 ring-green-200 text-center min-w-[100px]">
                    <i class="fa-solid fa-user text-2xl text-gray-600 mb-2"></i>
                    <p class="text-xs font-bold text-gray-700">Utilisateur</p>
                    <p class="text-xs font-bold text-gray-700">bat-id</p>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <div class="flex items-center gap-1">
                        <div class="h-px w-6 bg-green-400"></div>
                        <i class="fa-solid fa-arrow-right text-green-600"></i>
                        <div class="h-px w-6 bg-green-400"></div>
                    </div>
                    <span class="text-[10px] text-green-600 font-semibold">clic bouton</span>
                </div>
                <div class="rounded-xl bg-white px-5 py-4 shadow-sm ring-1 ring-green-200 text-center min-w-[100px]">
                    <i class="fa-solid fa-cart-shopping text-2xl text-green-600 mb-2"></i>
                    <p class="text-xs font-bold text-gray-700">Panier</p>
                    <p class="text-xs font-bold text-gray-700">subscribers</p>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <div class="flex items-center gap-1">
                        <div class="h-px w-6 bg-green-400"></div>
                        <i class="fa-solid fa-arrow-right text-green-600"></i>
                        <div class="h-px w-6 bg-green-400"></div>
                    </div>
                    <span class="text-[10px] text-green-600 font-semibold">Datatrans</span>
                </div>
                <div class="rounded-xl bg-white px-5 py-4 shadow-sm ring-1 ring-green-200 text-center min-w-[100px]">
                    <i class="fa-solid fa-credit-card text-2xl text-green-600 mb-2"></i>
                    <p class="text-xs font-bold text-gray-700">Paiement</p>
                    <p class="text-xs font-bold text-gray-700">securise</p>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <div class="flex items-center gap-1">
                        <div class="h-px w-6 bg-green-400"></div>
                        <i class="fa-solid fa-arrow-right text-green-600"></i>
                        <div class="h-px w-6 bg-green-400"></div>
                    </div>
                    <span class="text-[10px] text-green-600 font-semibold">valide</span>
                </div>
                <div class="rounded-xl bg-green-100 px-5 py-4 shadow-sm ring-1 ring-green-300 text-center min-w-[100px]">
                    <i class="fa-solid fa-circle-check text-2xl text-green-700 mb-2"></i>
                    <p class="text-xs font-bold text-green-800">Commande</p>
                    <p class="text-xs font-bold text-green-800">confirmee</p>
                </div>
            </div>
            <p class="mt-4 text-xs text-gray-600 text-center">L'utilisateur clique sur un bouton dans l'app bat-id. L'URL signee l'amene directement au panier avec l'abonnement pre-selectionne. Il paie et la commande est validee.</p>
        </div>

        {{-- Separateur --}}
        <div class="flex justify-center py-4">
            <div class="flex flex-col items-center">
                <div class="h-6 w-px bg-gray-300"></div>
                <i class="fa-solid fa-chevron-down text-gray-300 text-xs"></i>
            </div>
        </div>

        {{-- Etape 3 : Webhook --}}
        <div class="rounded-xl bg-amber-50 p-5 ring-1 ring-amber-200">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-amber-600 text-white text-xs font-bold">3</div>
                <span class="text-sm font-bold text-amber-800">NOTIFICATION WEBHOOK</span>
                <span class="rounded bg-amber-200 px-2 py-0.5 text-[10px] font-semibold text-amber-700">API 3 — Onglet Webhook sortant</span>
            </div>
            <div class="flex items-center justify-center gap-6">
                <div class="rounded-xl bg-white px-5 py-4 shadow-sm ring-1 ring-amber-200 text-center min-w-[100px]">
                    <i class="fa-solid fa-server text-2xl text-batid-bleu mb-2"></i>
                    <p class="text-xs font-bold text-gray-700">Subscribers</p>
                    <p class="text-xs font-bold text-gray-700">bat-i.ch</p>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <div class="flex items-center gap-2">
                        <div class="h-px w-8 bg-amber-400"></div>
                        <i class="fa-solid fa-arrow-right text-amber-600 text-lg"></i>
                        <div class="h-px w-8 bg-amber-400"></div>
                    </div>
                    <span class="rounded bg-white px-2 py-0.5 text-[10px] font-semibold text-amber-600 ring-1 ring-amber-200">token signe</span>
                </div>
                <div class="rounded-xl bg-white px-5 py-4 shadow-sm ring-1 ring-amber-200 text-center min-w-[100px]">
                    <i class="fa-solid fa-mobile-screen text-2xl text-gray-600 mb-2"></i>
                    <p class="text-xs font-bold text-gray-700">Backend</p>
                    <p class="text-xs font-bold text-gray-700">bat-id</p>
                </div>
            </div>
            <div class="mt-4 flex justify-center">
                <div class="rounded-lg bg-white px-4 py-2 ring-1 ring-amber-200 text-center">
                    <p class="text-[10px] font-semibold text-gray-500 mb-1">Informations transmises</p>
                    <div class="flex gap-3 text-[10px] text-amber-700">
                        <span><i class="fa-solid fa-file-invoice mr-1"></i>Commande</span>
                        <span><i class="fa-solid fa-list-check mr-1"></i>Features</span>
                        <span><i class="fa-solid fa-file-pdf mr-1"></i>Facture PDF</span>
                        <span><i class="fa-solid fa-calendar mr-1"></i>Dates</span>
                    </div>
                </div>
            </div>
            <p class="mt-4 text-xs text-gray-600 text-center">Des que la commande est validee, subscribers notifie automatiquement bat-id avec toutes les informations de l'abonnement.</p>
        </div>

    </div>

    {{-- Status --}}
    <div class="mb-6 rounded-xl p-4 {{ $secretConfigured ? 'bg-green-50 ring-1 ring-green-200' : 'bg-red-50 ring-1 ring-red-200' }}">
        <div class="flex items-center gap-2">
            @if($secretConfigured)
                <span class="inline-block h-2.5 w-2.5 rounded-full bg-green-500"></span>
                <span class="text-sm font-medium text-green-800">DEEPLINK_SECRET configure — API active</span>
            @else
                <span class="inline-block h-2.5 w-2.5 rounded-full bg-red-500"></span>
                <span class="text-sm font-medium text-red-800">DEEPLINK_SECRET non configure dans .env — API inactive</span>
            @endif
        </div>
    </div>

    {{-- Tabs --}}
    <div class="mb-6 flex border-b border-gray-200">
        <button @click="tab = 'deeplink'" :class="tab === 'deeplink' ? 'border-batid-bleu text-batid-bleu' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="border-b-2 px-6 py-3 text-sm font-semibold transition">
            1. Deeplink
        </button>
        <button @click="tab = 'register'" :class="tab === 'register' ? 'border-batid-bleu text-batid-bleu' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="border-b-2 px-6 py-3 text-sm font-semibold transition">
            2. Inscription
        </button>
        <button @click="tab = 'webhook'" :class="tab === 'webhook' ? 'border-batid-bleu text-batid-bleu' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="border-b-2 px-6 py-3 text-sm font-semibold transition">
            3. Webhook sortant
        </button>
    </div>

    {{-- ========== TAB 1 : DEEPLINK ========== --}}
    <div x-show="tab === 'deeplink'" x-cloak>

        {{-- Principe --}}
        <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="mb-3 text-lg font-semibold text-batid-marine">Principe</h2>
            <p class="mb-3 text-sm text-gray-700">L'application mobile bat-id peut rediriger un utilisateur directement vers le panier d'achat d'abonnement, sans qu'il ait besoin de choisir un abonnement ni de saisir son numero de telephone.</p>
            <p class="text-sm text-gray-700">Le lien contient un <strong>token signe</strong> (HMAC-SHA256) qui inclut le bat-ID de l'utilisateur, son numero de telephone, le type d'abonnement souhaite et la duree. Aucun appel API supplementaire n'est necessaire — les informations du token font foi. Le token expire apres <strong>{{ config('batid.deeplink_ttl', 600) / 60 }} minutes</strong>.</p>
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
  "b": "@iGgUwLLc",     // identifiant bat-id de l'utilisateur
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
    'b' => '@iGgUwLLc',
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
  b: '@iGgUwLLc',
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
  'b': '@iGgUwLLc',
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

    </div>

    {{-- ========== TAB 2 : INSCRIPTION ========== --}}
    <div x-show="tab === 'register'" x-cloak>

        {{-- Principe register --}}
        <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="mb-3 text-lg font-semibold text-batid-marine">Principe</h2>
            <p class="mb-3 text-sm text-gray-700">Le backend bat-id peut creer un abonne dans le systeme subscribers en appelant une URL signee. L'abonne est enregistre avec son bat-ID et son numero de telephone.</p>
            <p class="text-sm text-gray-700">Le systeme verifie qu'aucun abonne avec le meme bat-ID ou le meme numero de telephone n'existe deja. La reponse est un <strong>JSON structure</strong> indiquant le succes ou la raison de l'echec.</p>
        </div>

        {{-- Register test generator --}}
        @if($secretConfigured)
        <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="mb-3 text-lg font-semibold text-batid-marine">Generateur de test</h2>
            <p class="mb-3 text-sm text-gray-500">Chaque clic genere un numero de telephone et un bat-ID aleatoires.</p>
            <form method="GET" action="{{ route('admin.api.test-register-token') }}">
                <button type="submit" class="rounded-lg bg-batid-bleu px-4 py-2 text-sm text-white hover:bg-batid-marine">Generer une URL d'inscription test</button>
            </form>

            @if(session('register_url'))
            <div class="mt-4 space-y-3">
                <div class="flex gap-4 text-sm">
                    <span class="text-gray-500">Telephone :</span><span class="font-mono font-medium">{{ session('register_phone') }}</span>
                    <span class="text-gray-500">bat-ID :</span><span class="font-mono font-medium">{{ session('register_bat_id') }}</span>
                </div>
                <div x-data="{ copied: false }">
                    <p class="mb-1.5 text-xs font-semibold text-gray-500">URL generee</p>
                    <div class="flex items-center gap-2 rounded-lg bg-gray-900 p-3 cursor-pointer group"
                         @click="navigator.clipboard.writeText('{{ session('register_url') }}'); copied = true; setTimeout(() => copied = false, 2000)">
                        <div class="flex-1 min-w-0 overflow-x-auto">
                            <code class="whitespace-nowrap text-sm text-green-400">{{ session('register_url') }}</code>
                        </div>
                        <span x-show="!copied" class="flex-shrink-0 text-gray-400 group-hover:text-white transition">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </span>
                        <span x-show="copied" x-cloak class="flex-shrink-0 text-sm font-medium text-green-400">Copie !</span>
                    </div>
                </div>
                <a href="{{ session('register_url') }}" target="_blank" class="inline-flex items-center gap-1.5 text-sm text-batid-bleu hover:underline">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Tester dans le navigateur (reponse JSON)
                </a>
            </div>
            @endif
        </div>
        @endif

        {{-- URL register --}}
        <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="mb-3 text-lg font-semibold text-batid-marine">URL d'inscription</h2>
            <div class="rounded-lg bg-gray-900 p-4">
                <code class="text-sm text-green-400">GET {{ $baseUrl }}/api/register?token={TOKEN}</code>
            </div>
        </div>

        {{-- Token register --}}
        <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="mb-3 text-lg font-semibold text-batid-marine">Construction du token</h2>
            <p class="mb-3 text-sm text-gray-700">Meme principe que le deeplink : base64url + HMAC-SHA256 avec la meme cle secrete partagee.</p>

            <h3 class="mb-2 text-sm font-semibold text-gray-700">Payload JSON</h3>
            <div class="rounded-lg bg-gray-900 p-4 mb-4">
<pre class="text-sm text-green-400">{
  "a": "register",       // action (obligatoire, valeur fixe)
  "p": "+41791234567",   // numero de telephone (format international)
  "b": "@iGgUwLLc",     // identifiant bat-id de l'utilisateur
  "ts": 1713200000       // timestamp Unix (secondes)
}</pre>
            </div>
            <p class="text-sm text-gray-500">Le champ <code class="rounded bg-gray-100 px-1">"a": "register"</code> distingue ce token d'un token deeplink. Un token deeplink ne peut pas etre utilise pour inscrire un abonne et vice-versa.</p>
        </div>

        {{-- Register code examples --}}
        <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="mb-3 text-lg font-semibold text-batid-marine">Exemples d'implementation</h2>

            <h3 class="mb-2 mt-4 text-sm font-semibold text-gray-700">PHP</h3>
            <div class="rounded-lg bg-gray-900 p-4 mb-4">
<pre class="text-sm text-green-400">$secret = 'VOTRE_CLE_SECRETE_PARTAGEE';

$payload = json_encode([
    'a' => 'register',
    'p' => '+41791234567',
    'b' => '@iGgUwLLc',
    'ts' => time(),
]);

$encoded = rtrim(strtr(base64_encode($payload), '+/', '-_'), '=');
$signature = hash_hmac('sha256', $encoded, $secret);
$token = $encoded . '.' . $signature;

// Appel API
$response = file_get_contents('{{ $baseUrl }}/api/register?token=' . $token);
$result = json_decode($response, true);

if ($result['status'] === 'success') {
    echo "Abonné créé : " . $result['subscriber']['bat_id'];
} else {
    echo "Erreur : " . $result['message'];
}</pre>
            </div>

            <h3 class="mb-2 text-sm font-semibold text-gray-700">JavaScript / Node.js</h3>
            <div class="rounded-lg bg-gray-900 p-4 mb-4">
<pre class="text-sm text-green-400">const crypto = require('crypto');

const secret = 'VOTRE_CLE_SECRETE_PARTAGEE';

const payload = JSON.stringify({
  a: 'register',
  p: '+41791234567',
  b: '@iGgUwLLc',
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

const url = `{{ $baseUrl }}/api/register?token=${encoded}.${signature}`;
const res = await fetch(url);
const result = await res.json();
console.log(result);</pre>
            </div>
        </div>

        {{-- Responses --}}
        <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="mb-3 text-lg font-semibold text-batid-marine">Reponses JSON</h2>

            <h3 class="mb-2 text-sm font-semibold text-green-700">Succes — HTTP 201</h3>
            <div class="rounded-lg bg-gray-900 p-4 mb-4">
<pre class="text-sm text-green-400">{
  "status": "success",
  "message": "Abonné créé avec succès.",
  "subscriber": {
    "id": 42,
    "bat_id": "@iGgUwLLc",
    "phone": "+41791234567",
    "created_at": "2026-04-15T14:30:00+02:00"
  }
}</pre>
            </div>

            <h3 class="mb-2 text-sm font-semibold text-red-700">Conflit bat-ID — HTTP 409</h3>
            <div class="rounded-lg bg-gray-900 p-4 mb-4">
<pre class="text-sm text-red-400">{
  "status": "error",
  "code": "bat_id_exists",
  "message": "Un abonné avec ce bat-ID existe déjà.",
  "bat_id": "@iGgUwLLc"
}</pre>
            </div>

            <h3 class="mb-2 text-sm font-semibold text-red-700">Conflit telephone — HTTP 409</h3>
            <div class="rounded-lg bg-gray-900 p-4 mb-4">
<pre class="text-sm text-red-400">{
  "status": "error",
  "code": "phone_exists",
  "message": "Un abonné avec ce numéro de téléphone existe déjà.",
  "phone": "+41791234567"
}</pre>
            </div>

            <h3 class="mb-2 text-sm font-semibold text-red-700">Token invalide — HTTP 401</h3>
            <div class="rounded-lg bg-gray-900 p-4 mb-4">
<pre class="text-sm text-red-400">{
  "status": "error",
  "code": "invalid_token",
  "message": "Token invalide, expiré ou signature incorrecte."
}</pre>
            </div>

            <h3 class="mb-2 text-sm font-semibold text-red-700">Token manquant — HTTP 400</h3>
            <div class="rounded-lg bg-gray-900 p-4 mb-4">
<pre class="text-sm text-red-400">{
  "status": "error",
  "code": "missing_token",
  "message": "Le paramètre token est requis."
}</pre>
            </div>
        </div>

        {{-- Register security --}}
        <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="mb-3 text-lg font-semibold text-batid-marine">Securite</h2>
            <ul class="ml-4 list-disc space-y-1 text-sm text-gray-700">
                <li>Meme cle secrete et meme algorithme que le deeplink</li>
                <li>Le champ <code class="rounded bg-gray-100 px-1">"a": "register"</code> empeche la reutilisation d'un token deeplink comme token d'inscription</li>
                <li>Verification des doublons sur bat-ID ET telephone (y compris les abonnes supprimes)</li>
                <li>Token a usage unique implicite : le deuxieme appel echouera avec <code class="rounded bg-gray-100 px-1">bat_id_exists</code></li>
            </ul>
        </div>

    </div>

    {{-- ========== TAB 3 : WEBHOOK SORTANT ========== --}}
    <div x-show="tab === 'webhook'" x-cloak>

        {{-- Principe webhook --}}
        <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="mb-3 text-lg font-semibold text-batid-marine">Principe</h2>
            <p class="mb-3 text-sm text-gray-700">A chaque evenement important (commande validee, upgrade, expiration imminente, expiration), le systeme subscribers envoie une <strong>notification automatique</strong> vers le backend bat-id via une URL tokenisee signee.</p>
            <p class="mb-3 text-sm text-gray-700">Le token contient toutes les informations de la commande. Le developpeur bat-id doit implementer un <strong>endpoint de reception</strong> qui valide le token et retourne une reponse JSON.</p>
            <p class="text-sm text-gray-700">Le token utilise la <strong>meme cle secrete</strong> et le <strong>meme algorithme</strong> (HMAC-SHA256) que les API Deeplink et Inscription.</p>
        </div>

        {{-- Config status --}}
        <div class="mb-6 rounded-xl p-4 {{ config('batid.webhook_url') ? 'bg-green-50 ring-1 ring-green-200' : 'bg-yellow-50 ring-1 ring-yellow-200' }}">
            <div class="flex items-center gap-2">
                @if(config('batid.webhook_url'))
                    <span class="inline-block h-2.5 w-2.5 rounded-full bg-green-500"></span>
                    <span class="text-sm font-medium text-green-800">Webhook configure : <code class="font-mono">{{ config('batid.webhook_url') }}</code></span>
                @else
                    <span class="inline-block h-2.5 w-2.5 rounded-full bg-yellow-500"></span>
                    <span class="text-sm font-medium text-yellow-800">BAT_ID_WEBHOOK_URL non configure dans .env</span>
                @endif
            </div>
        </div>

        {{-- URL webhook --}}
        <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="mb-3 text-lg font-semibold text-batid-marine">Endpoint a implementer cote bat-id</h2>
            <div class="rounded-lg bg-gray-900 p-4 mb-4">
                <code class="text-sm text-green-400">GET https://demo.bat-id.ch/api/subscribers/webhook?token={TOKEN}</code>
            </div>
            <p class="text-sm text-gray-500">Le systeme subscribers appelle cette URL automatiquement a chaque evenement. Le developpeur bat-id doit creer cet endpoint.</p>
        </div>

        {{-- Events --}}
        <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="mb-3 text-lg font-semibold text-batid-marine">Evenements envoyes</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50"><tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-gray-500">Evenement (valeur "e")</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-gray-500">Declencheur</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="px-4 py-2 text-sm font-mono font-bold text-green-700">subscription_activated</td>
                            <td class="px-4 py-2 text-sm text-gray-700">Nouvelle commande validee (paiement confirme)</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 text-sm font-mono font-bold text-blue-700">subscription_upgraded</td>
                            <td class="px-4 py-2 text-sm text-gray-700">Upgrade d'abonnement validee (paiement confirme)</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 text-sm font-mono font-bold text-yellow-700">subscription_expiring_soon</td>
                            <td class="px-4 py-2 text-sm text-gray-700">Abonnement expire dans 30 jours (cron quotidien)</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 text-sm font-mono font-bold text-red-700">subscription_expired</td>
                            <td class="px-4 py-2 text-sm text-gray-700">Abonnement expire (cron quotidien)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Token payload --}}
        <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="mb-3 text-lg font-semibold text-batid-marine">Contenu du token</h2>
            <p class="mb-3 text-sm text-gray-700">Le token est signe avec la meme cle secrete et le meme algorithme que les autres API. Le champ <code class="rounded bg-gray-100 px-1">"a": "webhook"</code> identifie ce type de token.</p>

            <h3 class="mb-2 text-sm font-semibold text-green-700">subscription_activated / subscription_upgraded</h3>
            <div class="rounded-lg bg-gray-900 p-4 mb-4">
<pre class="text-sm text-green-400">{
  "a": "webhook",
  "e": "subscription_activated",
  "ts": 1713200000,
  "b": "@iGgUwLLc",
  "invoice_url": "https://subscribers-test.apcom.app/invoice/abc-def-...",
  "subscription": {
    "order_id": "CMD-000042",
    "type": "Premium",
    "status": "active",
    "started_at": "2026-04-15T00:00:00+02:00",
    "expires_at": "2027-04-15T00:00:00+02:00",
    "duration_months": 12,
    "features": {
      "parcelles": 50,
      "parcelles_unlimited": false,
      "alertes": 100,
      "stockage_go": 10,
      "stockage_unlimited": false,
      "cloud_externe": true,
      "lot_sauvegarde": true,
      "workspace": true,
      "workspace_quantity": 5,
      "workspace_unlimited": false
    }
  }
}</pre>
            </div>

            <h3 class="mb-2 text-sm font-semibold text-yellow-700">subscription_expiring_soon</h3>
            <div class="rounded-lg bg-gray-900 p-4 mb-4">
<pre class="text-sm text-green-400">{
  "a": "webhook",
  "e": "subscription_expiring_soon",
  "ts": 1713200000,
  "b": "@iGgUwLLc",
  "subscription": {
    "order_id": "CMD-000042",
    "type": "Premium",
    "expires_at": "2027-04-15T00:00:00+02:00",
    "days_remaining": 28
  }
}</pre>
            </div>

            <h3 class="mb-2 text-sm font-semibold text-red-700">subscription_expired</h3>
            <div class="rounded-lg bg-gray-900 p-4 mb-4">
<pre class="text-sm text-green-400">{
  "a": "webhook",
  "e": "subscription_expired",
  "ts": 1713200000,
  "b": "@iGgUwLLc",
  "subscription": {
    "order_id": "CMD-000042",
    "type": "Premium",
    "expires_at": "2027-04-15T00:00:00+02:00",
    "days_remaining": 0
  }
}</pre>
            </div>
        </div>

        {{-- Implementation cote bat-id --}}
        <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="mb-3 text-lg font-semibold text-batid-marine">Implementation cote bat-id</h2>
            <p class="mb-3 text-sm text-gray-700">Le developpeur bat-id doit implementer un endpoint GET qui :</p>
            <ol class="ml-4 list-decimal space-y-1 text-sm text-gray-700 mb-4">
                <li>Recupere le parametre <code class="rounded bg-gray-100 px-1">token</code> de l'URL</li>
                <li>Separe le token en deux parties : <code class="rounded bg-gray-100 px-1">base64url</code> et <code class="rounded bg-gray-100 px-1">signature</code> (separees par un point)</li>
                <li>Verifie la signature HMAC-SHA256 avec la cle secrete partagee</li>
                <li>Decode le payload base64url en JSON</li>
                <li>Verifie que <code class="rounded bg-gray-100 px-1">"a" === "webhook"</code></li>
                <li>Verifie l'expiration via le champ <code class="rounded bg-gray-100 px-1">ts</code> (10 minutes par defaut)</li>
                <li>Traite l'evenement selon le champ <code class="rounded bg-gray-100 px-1">e</code></li>
                <li>Retourne une reponse JSON</li>
            </ol>

            <h3 class="mb-2 text-sm font-semibold text-gray-700">Exemple PHP (endpoint bat-id)</h3>
            <div class="rounded-lg bg-gray-900 p-4 mb-4">
<pre class="text-sm text-green-400">// GET /api/subscribers/webhook?token={TOKEN}

$secret = 'VOTRE_CLE_SECRETE_PARTAGEE';
$token = $_GET['token'] ?? '';
$parts = explode('.', $token);

if (count($parts) !== 2) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Token manquant']);
    exit;
}

[$encoded, $signature] = $parts;

// Verifier la signature
$expected = hash_hmac('sha256', $encoded, $secret);
if (!hash_equals($expected, $signature)) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Signature invalide']);
    exit;
}

// Decoder le payload
$json = base64_decode(strtr($encoded, '-_', '+/'));
$data = json_decode($json, true);

// Verifier l'action et l'expiration
if ($data['a'] !== 'webhook' || (time() - $data['ts']) > 600) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Token invalide ou expire']);
    exit;
}

// Traiter l'evenement
$event = $data['e'];
$batId = $data['b'];

switch ($event) {
    case 'subscription_activated':
        // Activer les fonctionnalites pour cet utilisateur
        break;
    case 'subscription_upgraded':
        // Mettre a jour les fonctionnalites
        break;
    case 'subscription_expiring_soon':
        // Notifier l'utilisateur
        break;
    case 'subscription_expired':
        // Desactiver les fonctionnalites
        break;
}

http_response_code(200);
echo json_encode(['status' => 'success', 'message' => 'Webhook traite']);</pre>
            </div>
        </div>

        {{-- Reponse attendue --}}
        <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="mb-3 text-lg font-semibold text-batid-marine">Reponse JSON attendue</h2>
            <p class="mb-3 text-sm text-gray-700">Le systeme subscribers attend une reponse JSON avec un code HTTP 2xx pour considerer la notification comme reussie.</p>

            <h3 class="mb-2 text-sm font-semibold text-green-700">Succes — HTTP 200</h3>
            <div class="rounded-lg bg-gray-900 p-4 mb-4">
<pre class="text-sm text-green-400">{
  "status": "success",
  "message": "Webhook traité avec succès."
}</pre>
            </div>

            <h3 class="mb-2 text-sm font-semibold text-red-700">Erreur — HTTP 4xx/5xx</h3>
            <div class="rounded-lg bg-gray-900 p-4 mb-4">
<pre class="text-sm text-red-400">{
  "status": "error",
  "message": "Description de l'erreur."
}</pre>
            </div>

            <div class="mt-4 rounded-lg bg-yellow-50 p-4 ring-1 ring-yellow-200">
                <p class="text-sm text-yellow-800"><strong>Retentatives automatiques</strong> — En cas d'echec (timeout, erreur HTTP, erreur reseau), le systeme reessaie automatiquement jusqu'a 5 fois avec des delais croissants : 1 min, 5 min, 15 min, 1 h, 24 h.</p>
            </div>
        </div>

        {{-- Securite webhook --}}
        <div class="mb-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="mb-3 text-lg font-semibold text-batid-marine">Securite</h2>
            <ul class="ml-4 list-disc space-y-1 text-sm text-gray-700">
                <li>Meme cle secrete et meme algorithme que les API Deeplink et Inscription</li>
                <li>Le champ <code class="rounded bg-gray-100 px-1">"a": "webhook"</code> empeche la reutilisation d'un token deeplink ou register</li>
                <li>Le champ <code class="rounded bg-gray-100 px-1">ts</code> permet de rejeter les tokens expires</li>
                <li>L'endpoint bat-id doit toujours verifier la signature avant de traiter l'evenement</li>
                <li>Les evenements sont journalises dans le BO (Journal API) avec possibilite de rejouer</li>
            </ul>
        </div>

    </div>

</div>
@endsection
