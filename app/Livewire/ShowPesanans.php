<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;

class ShowPesanans extends Component
{
    use WithPagination;

    public $status = '';

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Pesanan::with(['user', 'jadwal'])
            ->when(!Auth::user()->hasRole('admin'), function($query) {
                $query->where('user_id', Auth::id());
            })
            ->when($this->status !== '', function($query) {
                $query->where('status', $this->status);
            })
            ->whereNotIn('status', ['dibatalkan', 'ditolak'])
            ->latest();

        return view('livewire.show-pesanans', [
            'pesanans' => $query->paginate(10)
        ]);
    }
    public $showCancelModal = false;
    public $selectedPesananId = null;

    public function confirmCancel($id)
    {
        $this->selectedPesananId = $id;
        $this->showCancelModal = true;
    }

    public function cancelPesanan()
{
    $pesanan = Pesanan::find($this->selectedPesananId);

    if ($pesanan && in_array($pesanan->status, ['menunggu', 'disetujui'])) {
        $pesanan->status = 'dibatalkan';
        $pesanan->save();

        // Menggunakan dispatch event Livewire
        $this->dispatch('showToast', 
            type: 'success',
            message: 'Pesanan berhasil dibatalkan'
        );
    } else {
        $this->dispatch('showToast', 
            type: 'error',
            message: 'Pesanan tidak dapat dibatalkan'
        );
    }

    $this->showCancelModal = false;
    $this->selectedPesananId = null;
}

}