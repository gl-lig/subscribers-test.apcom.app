<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number', 'subscriber_id', 'subscription_type_id', 'duration_months',
        'price_catalogue', 'discount_duration_pct', 'promo_code', 'discount_promo_pct',
        'price_paid', 'prorata_deducted', 'status', 'concluded_at', 'starts_at', 'expires_at',
        'datatrans_transaction_id', 'payment_method', 'invoice_token', 'invoice_url',
        'cgv_accepted_at', 'cgv_version', 'expiry_notified_at',
        'replaced_by_order_id', 'replacement_note',
    ];

    protected function casts(): array
    {
        return [
            'price_catalogue' => 'decimal:2',
            'discount_duration_pct' => 'decimal:2',
            'discount_promo_pct' => 'decimal:2',
            'price_paid' => 'decimal:2',
            'prorata_deducted' => 'decimal:2',
            'concluded_at' => 'datetime',
            'starts_at' => 'date',
            'expires_at' => 'date',
            'cgv_accepted_at' => 'datetime',
            'expiry_notified_at' => 'datetime',
        ];
    }

    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(Subscriber::class);
    }

    public function subscriptionType(): BelongsTo
    {
        return $this->belongsTo(SubscriptionType::class);
    }

    public function metadata(): HasOne
    {
        return $this->hasOne(OrderMetadata::class);
    }

    public function paymentLogs(): HasMany
    {
        return $this->hasMany(PaymentLog::class);
    }

    public function replacedByOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'replaced_by_order_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->expires_at->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired' || $this->expires_at->isPast();
    }

    public function daysRemaining(): int
    {
        return max(0, now()->diffInDays($this->expires_at, false));
    }

    public function calculateProrata(): float
    {
        if (! $this->isActive()) {
            return 0;
        }

        $totalDays = $this->starts_at->diffInDays($this->expires_at);
        $remainingDays = now()->diffInDays($this->expires_at, false);

        if ($totalDays <= 0 || $remainingDays <= 0) {
            return 0;
        }

        return round(((float) $this->price_paid / $totalDays) * $remainingDays, 2);
    }

    public static function generateOrderNumber(): string
    {
        $last = static::withTrashed()->orderByDesc('id')->first();
        $next = $last ? ((int) substr($last->order_number, 4)) + 1 : 1;

        return 'CMD-' . str_pad($next, 6, '0', STR_PAD_LEFT);
    }
}
