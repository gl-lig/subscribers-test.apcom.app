<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use SoftDeletes;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'role', 'status',
        'notify_new_order', 'two_factor_secret', 'two_factor_confirmed_at', 'last_login_at',
    ];

    protected $hidden = ['password', 'two_factor_secret'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'notify_new_order' => 'boolean',
            'two_factor_confirmed_at' => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }

    public function isSuper(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function hasTwoFactor(): bool
    {
        return ! is_null($this->two_factor_confirmed_at);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeNotifiable($query)
    {
        return $query->active()->where('notify_new_order', true);
    }

    public function activityLogs()
    {
        return $this->hasMany(AdminActivityLog::class);
    }
}
