<!DOCTYPE html>
<html><head><meta charset="utf-8"></head>
<body style="margin:0;padding:0;font-family:Arial,sans-serif;background:#f5f5f5;">
<table width="100%" cellpadding="0" cellspacing="0"><tr><td align="center" style="padding:20px;">
<table width="600" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
    <tr><td style="background:#00004D;padding:24px;text-align:center;"><img src="{{ asset('assets/brand/BATID_Logo_blanc.svg') }}" alt="bat-id" style="height:36px;display:inline-block;"></td></tr>
    <tr><td style="padding:32px;">
        <h2 style="color:#00004D;margin:0 0 16px;">Nouvelle commande reçue</h2>
        <table width="100%" cellpadding="8" cellspacing="0" style="font-size:14px;">
            <tr><td style="color:#666;border-bottom:1px solid #eee;">N° Commande</td><td style="border-bottom:1px solid #eee;font-weight:bold;">{{ $order->order_number }}</td></tr>
            <tr><td style="color:#666;border-bottom:1px solid #eee;">bat-ID</td><td style="border-bottom:1px solid #eee;">{{ $order->subscriber->bat_id ?? '-' }}</td></tr>
            <tr><td style="color:#666;border-bottom:1px solid #eee;">Type</td><td style="border-bottom:1px solid #eee;">{{ $order->subscriptionType->translation('fr')?->name ?? '-' }}</td></tr>
            <tr><td style="color:#666;border-bottom:1px solid #eee;">Durée</td><td style="border-bottom:1px solid #eee;">{{ $order->duration_months }} mois</td></tr>
            <tr><td style="color:#666;border-bottom:1px solid #eee;">Montant</td><td style="border-bottom:1px solid #eee;font-weight:bold;color:#00004D;">CHF {{ $order->price_paid }}</td></tr>
            <tr><td style="color:#666;border-bottom:1px solid #eee;">Moyen de paiement</td><td style="border-bottom:1px solid #eee;">{{ ucfirst($order->payment_method ?? '-') }}</td></tr>
            <tr><td style="color:#666;">Date</td><td>{{ $order->concluded_at?->format('d.m.Y H:i') }}</td></tr>
        </table>
    </td></tr>
    <tr><td style="padding:16px 32px;background:#f8f8f8;text-align:center;font-size:12px;color:#999;">bat-id Subscribers — subscribers.bat-i.ch</td></tr>
</table>
</td></tr></table>
</body></html>
