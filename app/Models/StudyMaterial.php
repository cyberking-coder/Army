<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StudyMaterial extends Model
{
    protected $table = 'study_material';
    protected $fillable = ['title', 'slug', 'description', 'file_path', 'subject', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    protected static function booted(): void
    {
        static::saving(function (StudyMaterial $m) {
            if (empty($m->slug)) $m->slug = Str::slug($m->title);
        });
    }
}
