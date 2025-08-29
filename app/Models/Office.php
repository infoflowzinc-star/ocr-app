<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Office extends Model
{
    protected $fillable = [
        'name',
        'code',
    ];

    /**
     * 事務所に属するユーザー（管理者・一般）
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * 事務所に属するクライアント（顧問先）
     */
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }
}

