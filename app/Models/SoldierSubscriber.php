<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoldierSubscriber extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'mobile', 'email', 'status',
        'otp', 'otp_expires_at', 'verified_at', 'unsubscribed_at', 'ip_address',
    ];

    protected $casts = [
        'otp_expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    protected $hidden = ['otp'];

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function scopeVerified($q)     { return $q->where('status', 'subscribed'); }
    public function scopeUnverified($q)   { return $q->where('status', 'unverified'); }
    public function scopeUnsubscribed($q) { return $q->where('status', 'unsubscribed'); }
}
