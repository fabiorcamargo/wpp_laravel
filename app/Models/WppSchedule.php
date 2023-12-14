<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WppSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'wpp_connect_id',
        'wpp_group_id',
        'name',
        'date',
        'time',
        'repeat',
        'period',
        'active',
        'body'
    ];

    public function wpp(): HasMany
    {
        return $this->hasMany(WppConnect::class, 'id', 'wpp_connect_id');
    }

    public function group(): HasMany
    {
        return $this->hasMany(WppGroup::class, 'id', 'wpp_group_id');
    }

}
