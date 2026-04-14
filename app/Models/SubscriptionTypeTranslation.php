<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionTypeTranslation extends Model
{
    protected $fillable = ['subscription_type_id', 'locale', 'name', 'description'];

    public function subscriptionType(): BelongsTo
    {
        return $this->belongsTo(SubscriptionType::class);
    }
}
