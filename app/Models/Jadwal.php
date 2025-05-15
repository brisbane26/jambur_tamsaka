<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwals';
    
    protected $fillable = [
        'user_id',
        'tanggal',
        'nama_acara',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pesanan()
    {
        return $this->hasOne(Pesanan::class);
    }
}