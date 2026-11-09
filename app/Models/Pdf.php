<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pdf extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'file_path',
        'file_size_kb', 'category', 'downloads_count', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    protected static function booted(): void
    {
        static::saving(function (Pdf $pdf) {
            if (empty($pdf->slug)) $pdf->slug = Str::slug($pdf->title);
        });
    }
}
