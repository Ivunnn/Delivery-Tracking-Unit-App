<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'id_order',
        'kode_invoice',
        'harga',
        'biaya_pengiriman',
        'total',
        'status_bayar',
        'paid_at',
        'bukti_bayar',
        'tgl_upload_bukti',
        'status_verifikasi',
        'catatan_tolak',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
            'biaya_pengiriman' => 'decimal:2',
            'total' => 'decimal:2',
            'paid_at' => 'datetime',
            'tgl_upload_bukti' => 'datetime',
            'status_bayar' => 'string',
            'status_verifikasi' => 'string',
        ];
    }

    public function getStatusVerifikasiBadgeAttribute(): array
    {
        return match ($this->status_verifikasi) {
            'belum_upload' => ['label' => 'Belum Upload', 'color' => 'default'],
            'menunggu_verifikasi' => ['label' => 'Menunggu Verifikasi', 'color' => 'warning'],
            'diterima' => ['label' => 'Bukti Diterima', 'color' => 'success'],
            'ditolak' => ['label' => 'Bukti Ditolak', 'color' => 'error'],
            default => ['label' => '-', 'color' => 'default'],
        };
    }
    // ── Relasi ──────────────────────────────────────────────
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');
    }

    // ── Helper ──────────────────────────────────────────────
    public function getTotalFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    public static function generateKode(): string
    {
        $latestCode = self::latest()->value('kode_invoice');
        $number = $latestCode ? ((int) substr($latestCode, -4)) + 1 : 1;
        return 'INV-' . date('Y') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}