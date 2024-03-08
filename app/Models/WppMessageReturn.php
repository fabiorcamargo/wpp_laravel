<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WppMessageReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'body'
    ];
}
