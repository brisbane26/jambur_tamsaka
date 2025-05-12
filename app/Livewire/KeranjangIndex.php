<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Keranjang;

class KeranjangIndex extends Component
{
    public $keranjangs = [];
    public $totalHarga = 0;

    public function mount()
    {
        $this->loadKeranjang();
    }

    public function loadKeranjang()
    {
        $this->keranjangs = Keranjang::where('user_id', auth()->id())
            ->with('paket')
            ->get();

        $this->totalHarga = $this->keranjangs->sum(function ($item) {
            return $item->paket->harga_jual * $item->kuantitas;
        });
    }

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

        $this->loadKeranjang();
    }

    public function render()
    {
        return view('livewire.keranjang-index')
            ->layout('layouts.admin.master');
    }
}
