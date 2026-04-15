<!DOCTYPE html>
<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>
<body style="margin:0;padding:0;font-family:'Helvetica Neue',Arial,sans-serif;background:#f0f2f5;-webkit-font-smoothing:antialiased;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f2f5;"><tr><td align="center" style="padding:40px 16px;">
<table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.06);">

    {{-- Header --}}
    <tr><td style="background:#00004D;padding:28px 40px;">
        <table width="100%" cellpadding="0" cellspacing="0"><tr>
            <td><img src="{{ asset('assets/brand/BATID_Logo_blanc.svg') }}" alt="bat-id" style="height:32px;display:block;"></td>
            <td style="text-align:right;color:rgba(255,255,255,0.5);font-size:11px;letter-spacing:0.5px;">NOTIFICATION</td>
        </tr></table>
    </td></tr>

    {{-- Badge nouvelle commande --}}
    <tr><td style="padding:32px 40px 0;">
        <table cellpadding="0" cellspacing="0"><tr>
            <td style="background:#ecfdf5;border:1px solid #bbf7d0;border-radius:20px;padding:6px 16px;">
                <span style="color:#16a34a;font-size:12px;font-weight:700;letter-spacing:0.5px;">&#9679; NOUVELLE COMMANDE</span>
            </td>
        </tr></table>
    </td></tr>

    {{-- Salutation --}}
    <tr><td style="padding:24px 40px 8px;">
        <p style="margin:0;font-size:16px;color:#1a1a2e;font-weight:600;">Bonjour,</p>
    </td></tr>
    <tr><td style="padding:0 40px 28px;">
        <p style="margin:0;font-size:14px;color:#555;line-height:1.6;">Une nouvelle commande vient d'etre validee sur <strong style="color:#00004D;">bat-id Subscribers</strong>. Voici le detail :</p>
    </td></tr>

    {{-- Bloc abonne --}}
    <tr><td style="padding:0 40px 20px;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fb;border-radius:12px;border:1px solid #e8eaed;overflow:hidden;">
            <tr><td style="padding:14px 20px 10px;">
                <p style="margin:0;font-size:10px;font-weight:700;color:#00004D;text-transform:uppercase;letter-spacing:1.5px;">Abonne</p>
            </td></tr>
            <tr><td style="padding:0 20px 16px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="font-size:13px;">
                    <tr>
                        <td style="padding:6px 0;color:#888;width:140px;">bat-ID</td>
                        <td style="padding:6px 0;font-weight:600;color:#1a1a2e;">{{ $order->subscriber->bat_id ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#888;">Telephone</td>
                        <td style="padding:6px 0;color:#1a1a2e;">{{ $order->subscriber->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#888;">Inscrit le</td>
                        <td style="padding:6px 0;color:#1a1a2e;">{{ $order->subscriber->created_at?->format('d.m.Y') ?? '-' }}</td>
                    </tr>
                </table>
            </td></tr>
        </table>
    </td></tr>

    {{-- Bloc commande --}}
    <tr><td style="padding:0 40px 20px;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fb;border-radius:12px;border:1px solid #e8eaed;overflow:hidden;">
            <tr><td style="padding:14px 20px 10px;">
                <p style="margin:0;font-size:10px;font-weight:700;color:#00004D;text-transform:uppercase;letter-spacing:1.5px;">Commande</p>
            </td></tr>
            <tr><td style="padding:0 20px 16px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="font-size:13px;">
                    <tr>
                        <td style="padding:6px 0;color:#888;width:140px;">N° Commande</td>
                        <td style="padding:6px 0;font-weight:700;color:#00004D;">{{ $order->order_number }}</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#888;">Abonnement</td>
                        <td style="padding:6px 0;font-weight:600;color:#1a1a2e;">{{ $order->subscriptionType?->translation('fr')?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#888;">Duree</td>
                        <td style="padding:6px 0;color:#1a1a2e;">{{ $order->duration_months }} mois</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#888;">Debut</td>
                        <td style="padding:6px 0;color:#1a1a2e;">{{ $order->starts_at?->format('d.m.Y') ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#888;">Expiration</td>
                        <td style="padding:6px 0;color:#1a1a2e;">{{ $order->expires_at?->format('d.m.Y') ?? '-' }}</td>
                    </tr>
                </table>
            </td></tr>
        </table>
    </td></tr>

    {{-- Bloc paiement --}}
    <tr><td style="padding:0 40px 28px;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#00004D;border-radius:12px;overflow:hidden;">
            <tr><td style="padding:14px 20px 10px;">
                <p style="margin:0;font-size:10px;font-weight:700;color:rgba(255,255,255,0.5);text-transform:uppercase;letter-spacing:1.5px;">Paiement</p>
            </td></tr>
            <tr><td style="padding:0 20px 16px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="font-size:13px;">
                    <tr>
                        <td style="padding:6px 0;color:rgba(255,255,255,0.6);width:140px;">Prix catalogue</td>
                        <td style="padding:6px 0;color:#fff;">CHF {{ number_format($order->price_catalogue, 2) }}</td>
                    </tr>
                    @if($order->discount_duration_pct > 0)
                    <tr>
                        <td style="padding:6px 0;color:rgba(255,255,255,0.6);">Rabais duree</td>
                        <td style="padding:6px 0;color:#4ade80;">-{{ $order->discount_duration_pct }}%</td>
                    </tr>
                    @endif
                    @if($order->discount_promo_pct > 0)
                    <tr>
                        <td style="padding:6px 0;color:rgba(255,255,255,0.6);">Code promo ({{ $order->promo_code }})</td>
                        <td style="padding:6px 0;color:#4ade80;">-{{ $order->discount_promo_pct }}%</td>
                    </tr>
                    @endif
                    @if($order->prorata_deducted > 0)
                    <tr>
                        <td style="padding:6px 0;color:rgba(255,255,255,0.6);">Prorata deduit</td>
                        <td style="padding:6px 0;color:#4ade80;">-CHF {{ number_format($order->prorata_deducted, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="2" style="padding:8px 0 0;"><div style="border-top:1px solid rgba(255,255,255,0.15);"></div></td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0 4px;color:rgba(255,255,255,0.8);font-weight:700;">Montant paye</td>
                        <td style="padding:8px 0 4px;color:#fff;font-weight:700;font-size:18px;">CHF {{ number_format($order->price_paid, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="padding:2px 0;color:rgba(255,255,255,0.4);font-size:11px;">Moyen de paiement</td>
                        <td style="padding:2px 0;color:rgba(255,255,255,0.6);font-size:11px;">{{ ucfirst($order->payment_method ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td style="padding:2px 0;color:rgba(255,255,255,0.4);font-size:11px;">Date de paiement</td>
                        <td style="padding:2px 0;color:rgba(255,255,255,0.6);font-size:11px;">{{ $order->concluded_at?->format('d.m.Y H:i') ?? '-' }}</td>
                    </tr>
                </table>
            </td></tr>
        </table>
    </td></tr>

    {{-- Bouton voir commande --}}
    <tr><td style="padding:0 40px 32px;" align="center">
        <a href="{{ config('app.url') }}/admin/orders/{{ $order->id }}" style="display:inline-block;background:#0051FF;color:#fff;text-decoration:none;padding:12px 32px;border-radius:8px;font-size:13px;font-weight:600;letter-spacing:0.3px;">Voir la commande dans le back-office</a>
    </td></tr>

    {{-- Footer --}}
    <tr><td style="padding:20px 40px;background:#f8f9fb;border-top:1px solid #e8eaed;">
        <table width="100%" cellpadding="0" cellspacing="0"><tr>
            <td style="font-size:11px;color:#999;line-height:1.5;">
                <strong style="color:#00004D;">bat-id Subscribers</strong><br>
                {{ config('app.url') }}
            </td>
            <td style="text-align:right;font-size:11px;color:#bbb;">
                Bat-i SA<br>
                Rue de l'Hopital 1, 1920 Martigny
            </td>
        </tr></table>
    </td></tr>

</table>
</td></tr></table>
</body></html>
