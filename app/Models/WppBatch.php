<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WppBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'wpp_connect_id',
        'msg',
        'body',
        'status'
    ];

    public function wpp(): BelongsTo
    {
        return $this->belongsTo(WppConnect::class, 'wpp_connect_id', 'id');
    }
}
