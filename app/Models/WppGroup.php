<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WppGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'wpp_connect_id',
        'group_id',
        'name',
        'creation'
    ];

    public function wpp(): BelongsTo
    {
        return $this->belongsTo(WppConnect::class, 'wpp_connect_id', 'id');
    }

    public function Schedule(): HasMany
    {
        return $this->hasMany(WppSchedule::class);
    }
}
