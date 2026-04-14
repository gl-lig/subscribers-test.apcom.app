<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class UserGroup extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description'];

    public function promoCodes(): HasMany
    {
        return $this->hasMany(PromoCode::class);
    }

    public function members()
    {
        return DB::table('user_group_members')->where('group_id', $this->id);
    }

    public function memberCount(): int
    {
        return $this->members()->count();
    }

    public function addMember(string $batId): void
    {
        DB::table('user_group_members')->insertOrIgnore([
            'group_id' => $this->id,
            'bat_id' => $batId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function removeMember(string $batId): void
    {
        DB::table('user_group_members')
            ->where('group_id', $this->id)
            ->where('bat_id', $batId)
            ->delete();
    }
}
