<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $table = 'units';

    protected $fillable = [
        'no_rangka',
        'tipe_motor',
        'warna',
        'tahun',
        'harga',
        'status',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'harga'  => 'decimal:2',
            'status' => 'string',
        ];
    }

    // ── Relasi ──────────────────────────────────────────────
    public function pengiriman()
    {
        return $this->hasMany(Pengiriman::class, 'id_unit');
    }

    // ── Scope ───────────────────────────────────────────────
    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }

    // ── Helper ──────────────────────────────────────────────
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'tersedia' => 'success',
            'dipesan'  => 'warning',
            'dikirim'  => 'info',
            'terjual'  => 'error',
            default    => 'default',
        };
    }

    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}