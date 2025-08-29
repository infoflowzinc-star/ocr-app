<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubAccount extends Model
{
    protected $fillable = [
        'account_id',
        'code',
        'name',
    ];

    /**
     * 所属する勘定科目
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
