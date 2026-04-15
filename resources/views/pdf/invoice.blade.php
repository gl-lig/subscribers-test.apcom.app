<!DOCTYPE html>
<html><head><meta charset="utf-8">
<style>
    @page { margin: 40px 50px 80px; }
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 10px;
        color: #222;
        line-height: 1.5;
        margin: 0;
        padding: 0;
    }

    /* Header */
    .header { margin-bottom: 40px; }
    .header table { width: 100%; }
    .header td { vertical-align: top; }
    .logo img { height: 28px; }
    .company-info { text-align: right; font-size: 9px; color: #555; line-height: 1.7; }
    .company-info strong { color: #222; font-size: 10px; }

    /* Document title */
    .doc-title {
        font-size: 20px;
        font-weight: 700;
        color: #000;
        letter-spacing: 0.5px;
        margin-bottom: 3px;
    }
    .doc-meta { font-size: 10px; color: #666; margin-bottom: 35px; }

    /* Address blocks */
    .addresses { width: 100%; margin-bottom: 30px; }
    .addresses td { vertical-align: top; width: 50%; padding-right: 30px; }
    .addr-label {
        font-size: 8px;
        font-weight: 700;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        margin-bottom: 6px;
        display: block;
    }
    .addr-body { font-size: 10px; line-height: 1.8; color: #333; }

    /* Details */
    .details { width: 100%; margin-bottom: 25px; border-collapse: collapse; }
    .details td {
        padding: 6px 0;
        font-size: 10px;
        border-bottom: 1px solid #eee;
    }
    .details td:first-child { color: #888; width: 130px; }

    /* Separator */
    .sep { border: none; border-top: 1px solid #ddd; margin: 0 0 20px; }

    /* Prices */
    .prices { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
    .prices th {
        background: #f5f5f5;
        text-align: left;
        padding: 8px 10px;
        font-size: 8px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #666;
        border-bottom: 1px solid #ddd;
    }
    .prices th:last-child { text-align: right; }
    .prices td { padding: 8px 10px; border-bottom: 1px solid #f0f0f0; font-size: 10px; }
    .prices td:last-child { text-align: right; }
    .prices tr.discount td { color: #16a34a; }
    .prices tr.total td {
        border-top: 2px solid #222;
        border-bottom: none;
        font-weight: 700;
        font-size: 12px;
        padding-top: 10px;
    }

    /* TVA box */
    .tva-box {
        margin-top: 15px;
        padding: 10px 12px;
        background: #f9f9f9;
        border: 1px solid #eee;
        font-size: 9px;
        color: #555;
        line-height: 1.7;
    }
    .tva-box table { width: 100%; border-collapse: collapse; }
    .tva-box td { padding: 2px 0; }
    .tva-box td:last-child { text-align: right; }
    .tva-box .tva-label { font-weight: 700; color: #333; }

    /* Payment */
    .payment {
        margin-top: 20px;
        font-size: 9px;
        color: #888;
    }
    .payment table { width: 100%; }
    .payment td { padding: 2px 0; }
    .payment td:last-child { text-align: right; }

    /* Footer */
    .footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 12px 50px;
        border-top: 1px solid #ddd;
        font-size: 8px;
        color: #999;
        line-height: 1.6;
    }
    .footer table { width: 100%; }
    .footer td { vertical-align: top; }
</style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <table><tr>
            <td class="logo"><img src="{{ public_path('assets/brand/BATID_Logo_bleu.svg') }}" alt="bat-id"></td>
            <td class="company-info">
                <strong>Bat-i SA</strong><br>
                Rue de l'Hopital 1<br>
                1920 Martigny, Suisse<br>
                CHE-491.808.618 MWST
            </td>
        </tr></table>
    </div>

    {{-- TITLE --}}
    <div class="doc-title">Facture</div>
    <div class="doc-meta">
        N&deg; {{ $order->order_number }} &mdash; {{ $order->concluded_at?->format('d.m.Y') ?? now()->format('d.m.Y') }}
    </div>

    {{-- ADDRESSES --}}
    <table class="addresses"><tr>
        <td>
            <span class="addr-label">Emetteur</span>
            <div class="addr-body">
                <strong>Bat-i SA</strong><br>
                Rue de l'Hopital 1<br>
                1920 Martigny<br>
                Suisse<br>
                <br>
                IDE : CHE-491.808.618<br>
                N&deg; TVA : CHE-491.808.618 MWST<br>
                RC : CH-621.3.010.679-3
            </div>
        </td>
        <td>
            <span class="addr-label">Abonne</span>
            <div class="addr-body">
                bat-ID : <strong>{{ $subscriber->bat_id }}</strong><br>
                Tel. : {{ $subscriber->phone }}
            </div>
        </td>
    </tr></table>

    {{-- SUBSCRIPTION DETAILS --}}
    <table class="details">
        <tr><td>Abonnement</td><td>{{ $typeName }}</td></tr>
        <tr><td>Duree</td><td>{{ $order->duration_months > 0 ? $order->duration_months . ' mois' : 'Illimitee' }}</td></tr>
        <tr><td>Periode</td><td>{{ $order->starts_at->format('d.m.Y') }} — {{ $order->expires_at ? $order->expires_at->format('d.m.Y') : 'Illimite' }}</td></tr>
    </table>

    @if($replacesOrder)
    <div style="margin-bottom:20px;padding:10px 12px;background:#fff8f0;border:1px solid #f0d9b5;font-size:9px;color:#7c5a2a;line-height:1.7;">
        <strong>Cette commande remplace :</strong> {{ $replacesOrder->order_number }} — {{ $replacesOrder->subscriptionType?->translation($locale)?->name ?? '-' }} (du {{ $replacesOrder->starts_at->format('d.m.Y') }}{{ $replacesOrder->expires_at ? ' au ' . $replacesOrder->expires_at->format('d.m.Y') : '' }})
    </div>
    @endif

    <hr class="sep">

    {{-- PRICES --}}
    @php
        $tvaRate = 8.1;
        $totalTTC = (float) $order->price_paid;
        $totalHT = round($totalTTC / (1 + $tvaRate / 100), 2);
        $tvaMontant = round($totalTTC - $totalHT, 2);
    @endphp

    <table class="prices">
        <thead><tr><th>Description</th><th>Montant CHF</th></tr></thead>
        <tbody>
            <tr>
                <td>{{ $typeName }} — {{ $order->duration_months > 0 ? $order->duration_months . ' mois' : 'Illimite' }}</td>
                <td>{{ number_format($order->price_catalogue, 2) }}</td>
            </tr>
            @if($order->discount_duration_pct > 0)
            <tr class="discount">
                <td>Rabais duree {{ $order->duration_months }} mois (-{{ number_format($order->discount_duration_pct, 0) }}%)</td>
                <td>-{{ number_format($order->price_catalogue * $order->discount_duration_pct / 100, 2) }}</td>
            </tr>
            @endif
            @if($order->discount_promo_pct > 0)
            @php
                $afterDuration = $order->price_catalogue - ($order->price_catalogue * $order->discount_duration_pct / 100);
                $promoAmount = $afterDuration * $order->discount_promo_pct / 100;
            @endphp
            <tr class="discount">
                <td>Code promo {{ $order->promo_code }} (-{{ number_format($order->discount_promo_pct, 0) }}%)</td>
                <td>-{{ number_format($promoAmount, 2) }}</td>
            </tr>
            @endif
            @if($order->prorata_deducted > 0)
            <tr class="discount">
                <td>Prorata residuel abonnement precedent</td>
                <td>-{{ number_format($order->prorata_deducted, 2) }}</td>
            </tr>
            @endif
            <tr class="total">
                <td>Total TTC</td>
                <td>CHF {{ number_format($totalTTC, 2) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- TVA BREAKDOWN --}}
    <div class="tva-box">
        <table>
            <tr>
                <td class="tva-label">Decomposition TVA</td>
                <td></td>
            </tr>
            <tr>
                <td>Montant hors taxe (HT)</td>
                <td>CHF {{ number_format($totalHT, 2) }}</td>
            </tr>
            <tr>
                <td>TVA {{ number_format($tvaRate, 1) }}% incluse</td>
                <td>CHF {{ number_format($tvaMontant, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Total TTC</strong></td>
                <td><strong>CHF {{ number_format($totalTTC, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    {{-- PAYMENT --}}
    <div class="payment">
        <table><tr>
            <td>Moyen de paiement : {{ ucfirst($order->payment_method ?? '—') }}</td>
            <td>Ref. transaction : {{ $order->datatrans_transaction_id ?? '—' }}</td>
        </tr></table>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        <table><tr>
            <td>
                <strong style="color:#666;">Bat-i SA</strong><br>
                Rue de l'Hopital 1, 1920 Martigny, Suisse
            </td>
            <td style="text-align:center;">
                IDE : CHE-491.808.618<br>
                N&deg; TVA : CHE-491.808.618 MWST
            </td>
            <td style="text-align:right;">
                bat-id.ch<br>
                subscribers.bat-i.ch
            </td>
        </tr></table>
    </div>

</body></html>
