<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'status', 'sort_order', 'parcelles_count', 'parcelles_unlimited',
        'alertes_count', 'stockage_go', 'stockage_unlimited', 'cloud_externe',
        'lot_sauvegarde', 'veille_robotisee', 'veille_count', 'veille_unlimited', 'workspace_enabled', 'workspace_count', 'workspace_unlimited',
        'price_chf', 'is_free', 'discount_24_months', 'discount_36_months',
    ];

    protected function casts(): array
    {
        return [
            'parcelles_unlimited' => 'boolean',
            'stockage_unlimited' => 'boolean',
            'cloud_externe' => 'boolean',
            'lot_sauvegarde' => 'boolean',
            'veille_robotisee' => 'boolean',
            'veille_unlimited' => 'boolean',
            'workspace_enabled' => 'boolean',
            'workspace_unlimited' => 'boolean',
            'price_chf' => 'decimal:2',
            'is_free' => 'boolean',
            'discount_24_months' => 'decimal:2',
            'discount_36_months' => 'decimal:2',
        ];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(SubscriptionTypeTranslation::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function translation(string $locale = 'fr'): ?SubscriptionTypeTranslation
    {
        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'fr');
    }

    public function scopeOnline($query)
    {
        return $query->where('status', 'online');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function discountForDuration(int $months): float
    {
        if ($months === 36 && $this->discount_36_months > 0) {
            return (float) $this->discount_36_months;
        }
        if ($months === 24 && $this->discount_24_months > 0) {
            return (float) $this->discount_24_months;
        }
        return 0;
    }

    public function priceForDuration(int $months): float
    {
        $annual = (float) $this->price_chf;
        $total = $annual * ($months / 12);

        $discount = $this->discountForDuration($months);
        if ($discount > 0) {
            $total -= $total * ($discount / 100);
        }

        return round($total, 2);
    }
}
