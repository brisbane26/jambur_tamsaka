<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Keranjang;

class KeranjangIndex extends Component
{
    public $keranjangs = [];
    public $totalHarga = 0;

    public function updateQuantityLive($keranjangId, $newQuantity)
    {
        $newQuantity = max(1, min(1000, (int)$newQuantity));
        
        Keranjang::where('id', $keranjangId)
            ->update(['kuantitas' => $newQuantity]);
        
        $this->loadKeranjang(); // Reload data dari database
    }
    
    public function mount()
    {
        $this->loadKeranjang();
    }

public function loadKeranjang()
{
    $this->keranjangs = Keranjang::where('user_id', auth()->id())
        ->with(['paket', 'paket.kategori']) // Pastikan kategori dimuat
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
        // Ambil ulang data dari database (kuantitas terbaru setelah input manual)
        $keranjang = Keranjang::find($keranjangId);

        if (!$keranjang) return;

        // Gunakan nilai dari database, bukan dari cache Livewire
        $currentQty = $keranjang->kuantitas;

        if ($action === 'increment') {
            $keranjang->kuantitas = min(1000, $currentQty + 1);
        } elseif ($action === 'decrement') {
            $keranjang->kuantitas = max(1, $currentQty - 1);
        }

        $keranjang->save();
        $this->loadKeranjang();
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