<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $table = 'drivers';

    protected $fillable = [
        'id_user',
        'no_ktp',
        'no_sim',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'string',
        ];
    }

    // ── Relasi ──────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function pengiriman()
    {
        return $this->hasMany(Pengiriman::class, 'id_driver');
    }

    // ── Scope ───────────────────────────────────────────────
    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }

    // ── Helper ──────────────────────────────────────────────
    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'tersedia' => 'success',
            'bertugas' => 'warning',
            default    => 'default',
        };
    }
}