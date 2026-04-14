<!DOCTYPE html>
<html><head><meta charset="utf-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; margin: 0; padding: 40px; }
    .header { display: flex; justify-content: space-between; margin-bottom: 40px; border-bottom: 3px solid #00004D; padding-bottom: 20px; }
    .logo { font-size: 28px; font-weight: bold; color: #00004D; }
    .logo span { color: #0050FF; }
    .title { font-size: 24px; font-weight: bold; color: #00004D; text-align: right; }
    .info-grid { display: table; width: 100%; margin-bottom: 30px; }
    .info-col { display: table-cell; width: 50%; vertical-align: top; }
    .label { color: #666; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px; }
    .value { font-size: 13px; margin-bottom: 10px; }
    table.prices { width: 100%; border-collapse: collapse; margin-top: 20px; }
    table.prices th { background: #00004D; color: #fff; text-align: left; padding: 10px; font-size: 11px; }
    table.prices td { padding: 10px; border-bottom: 1px solid #eee; }
    table.prices tr.total td { border-top: 2px solid #00004D; font-weight: bold; font-size: 14px; }
    .footer { margin-top: 60px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 15px; }
</style>
</head>
<body>
    <table width="100%"><tr>
        <td><div class="logo">bat<span>-id</span></div></td>
        <td style="text-align:right"><div class="title">FACTURE</div><div style="color:#666;font-size:11px;">{{ $order->order_number }}</div></td>
    </tr></table>
    <div style="border-bottom:3px solid #00004D;margin:15px 0 30px;"></div>

    <table width="100%"><tr>
        <td width="50%" style="vertical-align:top;">
            <div class="label">Abonné</div>
            <div class="value"><strong>bat-ID:</strong> {{ $subscriber->bat_id }}</div>
            <div class="value"><strong>Téléphone:</strong> {{ $subscriber->phone }}</div>
        </td>
        <td width="50%" style="vertical-align:top;text-align:right;">
            <div class="label">Date</div>
            <div class="value">{{ $order->concluded_at?->format('d.m.Y') ?? now()->format('d.m.Y') }}</div>
            <div class="label">Abonnement</div>
            <div class="value">{{ $typeName }}</div>
        </td>
    </tr></table>

    <table width="100%"><tr><td>
        <div class="label" style="margin-top:20px;">Détails</div>
        <table width="100%" style="margin-top:5px;font-size:12px;">
            <tr><td style="padding:5px 0;color:#666;">Durée</td><td style="text-align:right;">{{ $order->duration_months }} mois</td></tr>
            <tr><td style="padding:5px 0;color:#666;">Début</td><td style="text-align:right;">{{ $order->starts_at->format('d.m.Y') }}</td></tr>
            <tr><td style="padding:5px 0;color:#666;">Fin</td><td style="text-align:right;">{{ $order->expires_at->format('d.m.Y') }}</td></tr>
        </table>
    </td></tr></table>

    <table class="prices">
        <thead><tr><th>Description</th><th style="text-align:right;">Montant CHF</th></tr></thead>
        <tbody>
            <tr><td>Prix catalogue TTC</td><td style="text-align:right;">{{ number_format($order->price_catalogue, 2) }}</td></tr>
            @if($order->discount_duration_pct > 0)
            <tr><td>Rabais durée ({{ $order->discount_duration_pct }}%)</td><td style="text-align:right;color:green;">- {{ number_format($order->price_catalogue * $order->discount_duration_pct / 100, 2) }}</td></tr>
            @endif
            @if($order->discount_promo_pct > 0)
            <tr><td>Code promo {{ $order->promo_code }} ({{ $order->discount_promo_pct }}%)</td><td style="text-align:right;color:green;">remise</td></tr>
            @endif
            @if($order->prorata_deducted > 0)
            <tr><td>Prorata résiduel déduit</td><td style="text-align:right;color:green;">- {{ number_format($order->prorata_deducted, 2) }}</td></tr>
            @endif
            <tr class="total"><td>Total payé</td><td style="text-align:right;">CHF {{ number_format($order->price_paid, 2) }}</td></tr>
        </tbody>
    </table>

    <table width="100%" style="margin-top:25px;font-size:11px;"><tr>
        <td style="color:#666;">Moyen de paiement: {{ ucfirst($order->payment_method ?? '-') }}</td>
        <td style="text-align:right;color:#666;">Transaction: {{ $order->datatrans_transaction_id ?? '-' }}</td>
    </tr></table>

    <div class="footer">bat-id.ch — Gestion de biens immobiliers — Suisse</div>
</body></html>
