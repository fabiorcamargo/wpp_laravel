<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WppMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'wppid',
        'wpp_connect_id',
        'phone',
        'from',
        'to',
        'type',
        'body',
        'group',
        't',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'body' => 'array', // Permite armazenar e recuperar arrays no banco de dados
    ];

    public function wpp(): BelongsTo
    {
        return $this->belongsTo(WppConnect::class, 'wpp_connect_id', 'id');
    }
}
