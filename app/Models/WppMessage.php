<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        't',
    ];
}
