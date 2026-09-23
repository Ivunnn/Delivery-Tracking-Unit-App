<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'id_customer',
        'id_unit',
        'kode_order',
        'catatan',
        'status',
        'alasan_tolak',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'status'      => 'string',
        ];
    }

    // ── Relasi ──────────────────────────────────────────────
    public function customer()
    {
        return $this->belongsTo(User::class, 'id_customer');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'id_unit');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'id_order');
    }

    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class, 'id_order');
    }

    // ── Scope ───────────────────────────────────────────────
    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }

    // ── Helper ──────────────────────────────────────────────
    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'menunggu'  => ['label' => 'Menunggu',  'color' => 'warning'],
            'disetujui' => ['label' => 'Disetujui', 'color' => 'success'],
            'ditolak'   => ['label' => 'Ditolak',   'color' => 'error'],
            'selesai'   => ['label' => 'Selesai',   'color' => 'default'],
            default     => ['label' => '-',         'color' => 'default'],
        };
    }

    // Generate kode order otomatis
    public static function generateKode(): string
    {
        $latestCode = self::latest()->value('kode_order');
        $number = $latestCode ? ((int) substr($latestCode, -4)) + 1 : 1;
        return 'ORD-' . date('Y') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}