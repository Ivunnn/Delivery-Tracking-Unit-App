<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    use HasFactory;

    protected $table = 'pengiriman';

    protected $fillable = [
        'id_order',
        'id_driver',
        'kode_pengiriman',
        'tanggal_kirim',
        'estimasi_tiba',
        'tujuan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kirim' => 'date',
            'estimasi_tiba' => 'date',
            'status'        => 'string',
        ];
    }

    // ── Relasi ──────────────────────────────────────────────
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'id_driver');
    }

    public function trackings()
    {
        return $this->hasMany(Tracking::class, 'id_pengiriman')->latest('jam_update');
    }

    public function trackingTerakhir()
    {
        return $this->hasOne(Tracking::class, 'id_pengiriman')->latestOfMany('jam_update');
    }

    public function buktiPengiriman()
    {
        return $this->hasOne(BuktiPengiriman::class, 'id_pengiriman');
    }

    // ── Helper ──────────────────────────────────────────────
    public static function generateKode(): string
    {
        $latestCode = self::latest()->value('kode_pengiriman');
        $number = $latestCode ? ((int) substr($latestCode, -4)) + 1 : 1;
        return 'DTU-' . date('Y') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'menunggu'         => ['label' => 'Menunggu',          'color' => 'default'],
            'berangkat'        => ['label' => 'Berangkat',         'color' => 'info'],
            'dalam_perjalanan' => ['label' => 'Dalam Perjalanan',  'color' => 'warning'],
            'tiba'             => ['label' => 'Tiba di Lokasi',    'color' => 'success'],
            'selesai'          => ['label' => 'Selesai',           'color' => 'success'],
            default            => ['label' => '-',                 'color' => 'default'],
        };
    }
}