<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPesanan extends Model
{
    use HasFactory;

    protected $table = 'detail_pesanans';
    
    protected $fillable = [
        'pesanan_id',
        'paket_id',
        'kuantitas',
        'harga',
        'catatan',
    ];

    /**
     * Get the pesanan that owns the detail pesanan.
     */
    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class);
    }

    /**
     * Get the paket that owns the detail pesanan.
     */
    public function paket(): BelongsTo
    {
        return $this->belongsTo(Paket::class);
    }
}