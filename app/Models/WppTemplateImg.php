<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WppTemplateImg extends Model
{
    use HasFactory;

    protected $fillable = [
        'wpp_template_id',
        'url'
    ];
}
