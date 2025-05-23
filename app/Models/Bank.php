<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_bank',
        'nomor_akun',
        'nama_pemilik',
        'logo',
        'deskripsi',
    ];

    /**
     * Get the pesanan for the bank.
     */
    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class);
    }
}