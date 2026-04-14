<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscriber extends Model
{
    use SoftDeletes;

    protected $fillable = ['bat_id', 'phone'];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function activeOrder()
    {
        return $this->orders()
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->latest('starts_at')
            ->first();
    }
}
