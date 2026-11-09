<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['name', 'rating', 'comment', 'is_published', 'submitted_at'];
    protected $casts = ['is_published' => 'boolean', 'submitted_at' => 'datetime'];
}
