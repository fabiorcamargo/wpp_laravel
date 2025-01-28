<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WppConnect extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'session',
        'token',
        'status',
];

    public function Messages(): HasMany
    {
        return $this->hasMany(WppMessage::class);
    }

    public function Groups(): HasMany
    {
        return $this->hasMany(WppGroup::class);
    }

    public function Schedule(): HasMany
    {
        return $this->hasMany(WppSchedule::class);
    }

    public function Batch(): HasMany
    {
        return $this->hasMany(WppBatch::class);
    }

    public function QrCode(): HasOne
    {
        return $this->hasOne(WppQr::class);
    }

}

