<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    public $timestamps = false;

    protected $table = 'trackings';

    protected $fillable = [
        'id_pengiriman',
        'status_tracking',
        'lat',
        'lng',
        'lokasi',
        'catatan',
        'jam_update',
    ];

    protected function casts(): array
    {
        return [
            'lat'        => 'decimal:7',
            'lng'        => 'decimal:7',
            'jam_update' => 'datetime',
        ];
    }

    // ── Relasi ──────────────────────────────────────────────
    public function pengiriman()
    {
        return $this->belongsTo(Pengiriman::class, 'id_pengiriman');
    }
}