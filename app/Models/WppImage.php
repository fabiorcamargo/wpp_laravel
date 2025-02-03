<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WppImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'wpp_template_id',
        'url'
    ];
}
