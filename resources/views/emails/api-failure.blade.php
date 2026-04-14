<!DOCTYPE html>
<html><head><meta charset="utf-8"></head>
<body style="margin:0;padding:0;font-family:Arial,sans-serif;background:#f5f5f5;">
<table width="100%" cellpadding="0" cellspacing="0"><tr><td align="center" style="padding:20px;">
<table width="600" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:12px;overflow:hidden;">
    <tr><td style="background:#DC2626;padding:24px;text-align:center;color:#fff;font-size:20px;font-weight:bold;">ALERTE — Échec notification API</td></tr>
    <tr><td style="padding:32px;">
        <p style="color:#333;font-size:14px;">Après 5 tentatives, la notification n'a pas pu être envoyée à bat-id.ch.</p>
        <table width="100%" cellpadding="8" cellspacing="0" style="font-size:14px;margin-top:16px;">
            <tr><td style="color:#666;border-bottom:1px solid #eee;">Commande</td><td style="border-bottom:1px solid #eee;font-weight:bold;">{{ $order->order_number }}</td></tr>
            <tr><td style="color:#666;border-bottom:1px solid #eee;">bat-ID</td><td style="border-bottom:1px solid #eee;">{{ $order->subscriber->bat_id ?? '-' }}</td></tr>
            <tr><td style="color:#666;border-bottom:1px solid #eee;">Événement</td><td style="border-bottom:1px solid #eee;">{{ $event }}</td></tr>
            <tr><td style="color:#666;">Erreur</td><td style="color:#DC2626;">{{ $errorMessage }}</td></tr>
        </table>
        <p style="margin-top:20px;font-size:13px;color:#666;">Connectez-vous au back-office pour rejouer manuellement cette notification.</p>
    </td></tr>
</table>
</td></tr></table>
</body></html>
