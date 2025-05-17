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

    protected $fillable = [
        'user_id', 
        'jadwal_id', 
        'nama_acara',
        'status', 
        'total_harga',
        'bukti_transaksi', 
        'alasan_tolak'
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function detailPesanan(): HasMany
    {
        return $this->hasMany(DetailPesanan::class);
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function getTotalHargaAttribute()
    {
        return $this->detailPesanan->sum(function($item) {
            return $item->harga * $item->kuantitas;
        });
    }

    // Scope untuk admin
    public function scopeForAdmin($query)
    {
        return $query->with(['user', 'jadwal', 'detailPesanan.paket']);
    }

    // Scope untuk customer
    public function scopeForCustomer($query, $userId)
    {
        return $query->where('user_id', $userId)
            ->with(['jadwal', 'detailPesanan.paket']);
    }
}