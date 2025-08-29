<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'office_id',
        'name',
        'industry',
        'tax_code',
    ];

    /**
     * 所属する事務所
     */
    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * クライアントに属する勘定科目
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    /**
     * クライアントに属する仕訳テンプレート
     */
    public function journalTemplates(): HasMany
    {
        return $this->hasMany(JournalTemplate::class);
    }

    /**
     * クライアントに属するログインユーザー
     */
    public function clientUsers(): HasMany
    {
        return $this->hasMany(ClientUser::class);
    }
}
