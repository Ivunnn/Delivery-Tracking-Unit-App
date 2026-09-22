<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuktiPengiriman extends Model
{
    public $timestamps = false;

    protected $table = 'bukti_pengiriman';

    protected $fillable = [
        'id_pengiriman',
        'foto_bukti',
        'keterangan',
        'waktu_upload',
    ];

    protected function casts(): array
    {
        return [
            'waktu_upload' => 'datetime',
        ];
    }

    // ── Relasi ──────────────────────────────────────────────
    public function pengiriman()
    {
        return $this->belongsTo(Pengiriman::class, 'id_pengiriman');
    }
}