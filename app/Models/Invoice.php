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
    ];

    protected function casts(): array
    {
        return [
            'harga'            => 'decimal:2',
            'biaya_pengiriman' => 'decimal:2',
            'total'            => 'decimal:2',
            'paid_at'          => 'datetime',
            'status_bayar'     => 'string',
        ];
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
        $latest = self::latest()->first();
        $number = $latest ? ((int) substr($latest->kode_invoice, -4)) + 1 : 1;
        return 'INV-' . date('Y') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}