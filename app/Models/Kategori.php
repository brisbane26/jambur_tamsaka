<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategoris';
    
    protected $fillable = [
        'nama_kategori',
    ];

    /**
     * Get the paket for the kategori.
     */
    public function paket(): HasMany
    {
        return $this->hasMany(Paket::class);
    }
}