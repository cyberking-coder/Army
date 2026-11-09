<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResultRegistration extends Model
{
    protected $fillable = ['exam_id', 'name', 'mobile', 'email', 'roll_no', 'zone', 'submitted_at'];
    protected $casts = ['submitted_at' => 'datetime'];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }
}
