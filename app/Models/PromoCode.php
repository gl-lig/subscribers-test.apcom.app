<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromoCode extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'description', 'code', 'discount_pct', 'valid_from', 'valid_until',
        'usage_limit_per_user', 'scope', 'bat_id_specific', 'user_group_id', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'discount_pct' => 'decimal:2',
            'valid_from' => 'datetime',
            'valid_until' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function userGroup(): BelongsTo
    {
        return $this->belongsTo(UserGroup::class);
    }

    public function isValid(?string $batId = null): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->valid_from && $this->valid_from->isFuture()) {
            return false;
        }

        if ($this->valid_until && $this->valid_until->isPast()) {
            return false;
        }

        if ($this->scope === 'specific_user' && $batId !== $this->bat_id_specific) {
            return false;
        }

        if ($this->scope === 'group' && $batId) {
            $inGroup = \DB::table('user_group_members')
                ->where('group_id', $this->user_group_id)
                ->where('bat_id', $batId)
                ->exists();
            if (! $inGroup) {
                return false;
            }
        }

        if ($batId && $this->usage_limit_per_user > 0) {
            $usageCount = Order::where('promo_code', $this->code)
                ->whereHas('subscriber', fn ($q) => $q->where('bat_id', $batId))
                ->count();
            if ($usageCount >= $this->usage_limit_per_user) {
                return false;
            }
        }

        return true;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
