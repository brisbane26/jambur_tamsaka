<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Keranjang;

class KeranjangIndex extends Component
{
    public $totalHarga = 0;

    // Menambah atau mengurangi kuantitas barang
    public function updateQuantity($id, $operation)
    {
        $keranjang = Keranjang::find($id);

        if ($operation === 'increment') {
            $keranjang->kuantitas++;
        } elseif ($operation === 'decrement') {
            $keranjang->kuantitas = max(0, $keranjang->kuantitas - 1);
        }

        if ($keranjang->kuantitas === 0) {
            $keranjang->delete();
        } else {
            $keranjang->save();
        }

        $this->calculateTotalHarga(); // Memperbarui total harga
    }

    // Menghitung total harga semua barang di keranjang
    public function calculateTotalHarga()
    {
        $this->totalHarga = Keranjang::where('user_id', auth()->id())
            ->with('paket')
            ->get()
            ->sum(function ($item) {
                return $item->paket->harga_jual * $item->kuantitas;
            });
    }

    // Render data keranjang
    public function render()
    {
        $keranjangs = Keranjang::where('user_id', auth()->id())
            ->with('paket')
            ->get(); // Mengambil semua item

        $this->calculateTotalHarga(); // Memperbarui total harga

        return view('livewire.keranjang-index', [
            'keranjangs' => $keranjangs,
        ])->layout('layouts.admin.master');
    }
}