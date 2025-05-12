<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Keranjang extends Model
{
    use HasFactory;

    protected $table = 'keranjangs';
    
    protected $fillable = [
        'user_id',
        'paket_id',
        'kuantitas',
    ];

    /**
     * Get the user that owns the keranjang.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the paket that owns the keranjang.
     */
    public function paket(): BelongsTo
    {
        return $this->belongsTo(Paket::class);
    }

    public function getTotalHargaAttribute()
    {
        return $this->paket->harga_jual * $this->kuantitas;
    }
}