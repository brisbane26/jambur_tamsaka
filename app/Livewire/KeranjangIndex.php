<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Keranjang;

class KeranjangIndex extends Component
{
    public $keranjangs = [];
    public $totalHarga = 0;

    protected $listeners = ['quantityUpdated' => 'handleQuantityUpdate'];

    public function mount()
    {
        $this->loadKeranjang();
    }

    public function loadKeranjang()
    {
        $this->keranjangs = Keranjang::where('user_id', auth()->id())
            ->with('paket')
            ->get();

        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->totalHarga = $this->keranjangs->sum(function ($item) {
            return $item->paket->harga_jual * $item->kuantitas;
        });
    }

public function updateQuantity($keranjangId, $action)
{
    $keranjang = Keranjang::find($keranjangId);
    
    if ($action === 'increment') {
        $keranjang->kuantitas++;
    } elseif ($action === 'decrement' && $keranjang->kuantitas > 1) {
        $keranjang->kuantitas--;
    }
    
    $keranjang->save();
    
    $this->dispatch('cartUpdated'); 
}

    

    public function handleQuantityUpdate($id, $newQuantity)
    {
        $newQuantity = max(1, min(1000, (int)$newQuantity)); // Pastikan antara 1-1000
        
        $keranjang = Keranjang::find($id);
        if ($keranjang) {
            $keranjang->kuantitas = $newQuantity;
            $keranjang->save();
            $this->loadKeranjang();
        }
    }

    public function removeItem($id)
    {
        $keranjang = Keranjang::find($id);
        if ($keranjang) {
            $keranjang->delete();
            $this->loadKeranjang();
        }
    }

    public function render()
    {
        return view('livewire.keranjang-index')
            ->layout('layouts.admin.master');
    }
}