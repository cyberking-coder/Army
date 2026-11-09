<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsline extends Model
{
    protected $table = 'newsline';
    protected $fillable = ['headline', 'link_url', 'starts_at', 'ends_at', 'is_active', 'sort'];
    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}
