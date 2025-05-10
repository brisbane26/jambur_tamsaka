<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanans';
    
    protected $fillable = [
        'user_id',
        'pembayaran_id',
        'bank_id',
        'status',
        'bukti_transaksi',
        'alasan_tolak',
    ];

    /**
     * Get the user that owns the pesanan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the pembayaran that owns the pesanan.
     */
    public function pembayaran(): BelongsTo
    {
        return $this->belongsTo(Pembayaran::class);
    }

    /**
     * Get the bank that owns the pesanan.
     */
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * Get the detail pesanan for the pesanan.
     */
    public function detailPesanan(): HasMany
    {
        return $this->hasMany(DetailPesanan::class);
    }

    /**
     * Get the invoice associated with the pesanan.
     */
    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }
}