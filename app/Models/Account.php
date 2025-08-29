<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    protected $fillable = [
        'client_id',
        'code',
        'name',
        'type',
    ];

    /**
     * 所属するクライアント
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * 勘定科目に属する補助科目
     */
    public function subAccounts(): HasMany
    {
        return $this->hasMany(SubAccount::class);
    }
}
