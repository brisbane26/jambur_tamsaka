<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans';
    
    protected $fillable = [
        'metode_bayar',
        'status',
    ];

    /**
     * Get the pesanan for the pembayaran.
     */
    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class);
    }
}