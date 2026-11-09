<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Exam extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'exam_date', 'is_active'];
    protected $casts = ['exam_date' => 'date', 'is_active' => 'boolean'];

    protected static function booted(): void
    {
        static::saving(function (Exam $exam) {
            if (empty($exam->slug)) $exam->slug = Str::slug($exam->name);
        });
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(ResultRegistration::class);
    }
}
