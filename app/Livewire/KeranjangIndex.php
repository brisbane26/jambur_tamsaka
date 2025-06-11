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
        $keranjang = Keranjang::find($keranjangId);
        
        // Handle khusus untuk Catering
        if (str_contains($keranjang->paket->kategori->nama_kategori, 'Catering')) {
            $newQuantity = max(1, min(2000, (int)$newQuantity)); // Minimal 50, maksimal 2000
        } 
        // Kategori fixed lainnya
        elseif ($this->isFixedQuantityCategory($keranjang->paket->kategori->nama_kategori)) {
            $newQuantity = 1;
        } 
        // Kategori normal
        else {
            $newQuantity = max(1, min(2000, (int)$newQuantity));
        }
        
        

        Keranjang::where('id', $keranjangId)
            ->update(['kuantitas' => $newQuantity]);
        
        $this->loadKeranjang();
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
        $keranjang = Keranjang::with('paket.kategori')->find($keranjangId);
        
        if (!$keranjang) return;

        // Handle khusus untuk Catering
        if (str_contains($keranjang->paket->kategori->nama_kategori, 'Catering')) {
            if ($action === 'increment') {
                $keranjang->kuantitas = min(2000, $keranjang->kuantitas + 1);
            } else {
                $keranjang->kuantitas = max(1, $keranjang->kuantitas - 1);
            }
        } 
        // Kategori fixed lainnya
        elseif ($this->isFixedQuantityCategory($keranjang->paket->kategori->nama_kategori)) {
            return; // Tidak bisa diubah sama sekali
        } 
        // Kategori normal
        else {
            if ($action === 'increment') {
                $keranjang->kuantitas = min(2000, $keranjang->kuantitas + 1);
            } else {
                $keranjang->kuantitas = max(1, $keranjang->kuantitas - 1);
            }
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

    public function confirmDelete($id)
{
    $keranjang = Keranjang::find($id);

    if ($keranjang) {
        $keranjang->delete();

        $this->dispatch('showToast', 
            type: 'success',
            message: 'Item berhasil dihapus dari keranjang.'
        );

        $this->loadKeranjang();
    } else {
        $this->dispatch('showToast', 
            type: 'error',
            message: 'Item tidak ditemukan atau sudah dihapus.'
        );
    }
}

    // Di dalam KeranjangIndex Livewire component
    private function isFixedQuantityCategory($kategori)
    {
        $fixedCategories = ['Salon', 'Musik', 'Dekorasi', 'Gedung', 'Dokumentasi', 'Lainnya'];
        return in_array($kategori, $fixedCategories);
    }

}