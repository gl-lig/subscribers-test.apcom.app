<!DOCTYPE html>
<html><head><meta charset="utf-8">
<style>
    @page { margin: 0; }
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 11px;
        color: #1a1a2e;
        margin: 0;
        padding: 0;
    }

    /* Header band */
    .header-band {
        background: #00004D;
        padding: 32px 50px 28px;
    }
    .header-table { width: 100%; }
    .header-table td { vertical-align: middle; }
    .header-logo img { height: 32px; }
    .header-right { text-align: right; color: rgba(255,255,255,0.55); font-size: 10px; line-height: 1.6; }
    .header-right strong { color: #ffffff; font-size: 11px; letter-spacing: 0.5px; }

    /* Content */
    .content { padding: 40px 50px 30px; }

    /* Title row */
    .doc-title {
        font-size: 22px;
        font-weight: bold;
        color: #00004D;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    .doc-number { font-size: 12px; color: #666; margin-bottom: 30px; }

    /* Address blocks */
    .addr-table { width: 100%; margin-bottom: 35px; }
    .addr-table td { vertical-align: top; width: 50%; }
    .addr-label {
        font-size: 9px;
        font-weight: bold;
        color: #00004D;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        border-bottom: 1px solid #00004D;
        padding-bottom: 5px;
        margin-bottom: 8px;
        display: block;
    }
    .addr-body { font-size: 11px; line-height: 1.7; color: #333; margin-top: 8px; }

    /* Details */
    .details-table { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
    .details-table td {
        padding: 7px 12px;
        font-size: 11px;
        border-bottom: 1px solid #f0f0f0;
    }
    .details-table td:first-child { color: #888; width: 140px; }
    .details-table td:last-child { text-align: right; font-weight: 500; }

    /* Separator */
    .sep { border: none; border-top: 1px solid #e0e0e0; margin: 5px 0 25px; }

    /* Prices table */
    .prices { width: 100%; border-collapse: collapse; }
    .prices th {
        background: #00004D;
        color: #fff;
        text-align: left;
        padding: 10px 14px;
        font-size: 9px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .prices th:last-child { text-align: right; }
    .prices td { padding: 10px 14px; border-bottom: 1px solid #f0f0f0; font-size: 11px; }
    .prices td:last-child { text-align: right; }
    .prices tr.discount td { color: #16a34a; }
    .prices tr.subtotal td { border-top: 1px solid #ddd; font-weight: 500; background: #fafafa; }
    .prices tr.total td {
        border-top: 2px solid #00004D;
        font-weight: bold;
        font-size: 13px;
        padding-top: 12px;
        padding-bottom: 12px;
    }
    .prices tr.tva td { font-size: 10px; color: #666; border-bottom: none; }

    /* Payment info */
    .payment-info {
        margin-top: 20px;
        padding: 14px 16px;
        background: #f8f9fa;
        border: 1px solid #e8e8e8;
        border-radius: 4px;
        font-size: 10px;
        color: #666;
    }
    .payment-info table { width: 100%; }
    .payment-info td { padding: 2px 0; }
    .payment-info td:last-child { text-align: right; }

    /* Footer */
    .footer-band {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #f8f9fa;
        border-top: 1px solid #e0e0e0;
        padding: 16px 50px;
        font-size: 9px;
        color: #888;
        line-height: 1.6;
    }
    .footer-band table { width: 100%; }
    .footer-band td { vertical-align: top; }
    .footer-brand { font-weight: bold; color: #00004D; font-size: 10px; }
</style>
</head>
<body>
    {{-- HEADER BAND --}}
    <div class="header-band">
        <table class="header-table"><tr>
            <td class="header-logo"><img src="{{ public_path('assets/brand/BATID_Logo_blanc.svg') }}" alt="bat-id"></td>
            <td class="header-right">
                <strong>Bat-i SA</strong><br>
                Rue de l'Hôpital 1, 1920 Martigny<br>
                CHE-491.808.618 TVA
            </td>
        </tr></table>
    </div>

    {{-- CONTENT --}}
    <div class="content">

        {{-- Title --}}
        <div class="doc-title">Facture</div>
        <div class="doc-number">{{ $order->order_number }} &mdash; {{ $order->concluded_at?->format('d.m.Y') ?? now()->format('d.m.Y') }}</div>

        {{-- Addresses --}}
        <table class="addr-table"><tr>
            <td>
                <span class="addr-label">Émetteur</span>
                <div class="addr-body">
                    <strong>Bat-i SA</strong><br>
                    Rue de l'Hôpital 1<br>
                    1920 Martigny<br>
                    Suisse<br>
                    CHE-491.808.618 TVA
                </div>
            </td>
            <td>
                <span class="addr-label">Abonné</span>
                <div class="addr-body">
                    <strong>bat-ID : {{ $subscriber->bat_id }}</strong><br>
                    Tél. : {{ $subscriber->phone }}
                </div>
            </td>
        </tr></table>

        {{-- Subscription details --}}
        <table class="details-table">
            <tr><td>Abonnement</td><td>{{ $typeName }}</td></tr>
            <tr><td>Durée</td><td>{{ $order->duration_months }} mois</td></tr>
            <tr><td>Période</td><td>{{ $order->starts_at->format('d.m.Y') }} — {{ $order->expires_at->format('d.m.Y') }}</td></tr>
        </table>

        <hr class="sep">

        {{-- Prices table --}}
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
                    <td>Prix catalogue TTC — {{ $typeName }}, {{ $order->duration_months }} mois</td>
                    <td>{{ number_format($order->price_catalogue, 2) }}</td>
                </tr>
                @if($order->discount_duration_pct > 0)
                <tr class="discount">
                    <td>Rabais durée {{ $order->duration_months }} mois ({{ number_format($order->discount_duration_pct, 0) }}%)</td>
                    <td>- {{ number_format($order->price_catalogue * $order->discount_duration_pct / 100, 2) }}</td>
                </tr>
                @endif
                @if($order->discount_promo_pct > 0)
                @php
                    $afterDuration = $order->price_catalogue - ($order->price_catalogue * $order->discount_duration_pct / 100);
                    $promoAmount = $afterDuration * $order->discount_promo_pct / 100;
                @endphp
                <tr class="discount">
                    <td>Code promo {{ $order->promo_code }} ({{ number_format($order->discount_promo_pct, 0) }}%)</td>
                    <td>- {{ number_format($promoAmount, 2) }}</td>
                </tr>
                @endif
                @if($order->prorata_deducted > 0)
                <tr class="discount">
                    <td>Prorata résiduel abonnement précédent</td>
                    <td>- {{ number_format($order->prorata_deducted, 2) }}</td>
                </tr>
                @endif
                <tr class="subtotal">
                    <td>Sous-total HT</td>
                    <td>{{ number_format($totalHT, 2) }}</td>
                </tr>
                <tr class="tva">
                    <td>TVA {{ number_format($tvaRate, 1) }}%</td>
                    <td>{{ number_format($tvaMontant, 2) }}</td>
                </tr>
                <tr class="total">
                    <td>Total TTC</td>
                    <td>CHF {{ number_format($totalTTC, 2) }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Payment info --}}
        <div class="payment-info">
            <table><tr>
                <td>Moyen de paiement : {{ ucfirst($order->payment_method ?? '—') }}</td>
                <td>Réf. transaction : {{ $order->datatrans_transaction_id ?? '—' }}</td>
            </tr></table>
        </div>

    </div>

    {{-- FOOTER --}}
    <div class="footer-band">
        <table><tr>
            <td>
                <span class="footer-brand">Bat-i SA</span><br>
                Rue de l'Hôpital 1 &bull; 1920 Martigny &bull; Suisse
            </td>
            <td style="text-align:center;">
                CHE-491.808.618 TVA<br>
                Taux TVA : {{ number_format($tvaRate, 1) }}%
            </td>
            <td style="text-align:right;">
                bat-id.ch<br>
                subscribers.bat-i.ch
            </td>
        </tr></table>
    </div>
</body></html>
