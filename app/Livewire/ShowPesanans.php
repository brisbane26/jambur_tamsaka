<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ShowPesanans extends Component
{
    use WithPagination;

    public $status = '';
    public $showCancelModal = false;
    public $selectedPesananId = null;

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
            ->whereNotIn('status', ['dibatalkan', 'ditolak', 'selesai'])
            ->orderBy('id', 'asc');

        return view('livewire.show-pesanans', [
            'pesanans' => $query->paginate(10)
        ]);
    }

    public function confirmCancel($id)
    {
        $this->selectedPesananId = $id;
        $this->showCancelModal = true;
    }

    public function cancelPesanan()
    {
        $pesanan = Pesanan::find($this->selectedPesananId);
        $user = Auth::user();

        // Periksa apakah pesanan ditemukan dan dimiliki oleh user yang login (jika bukan admin)
        // serta statusnya memungkinkan untuk dibatalkan.
        if (!$pesanan || ($user->hasRole('customer') && $pesanan->user_id != $user->id) || !in_array($pesanan->status, ['menunggu', 'disetujui'])) {
            $this->dispatch('showToast', 
                type: 'error',
                message: 'Pesanan tidak dapat dibatalkan. Pastikan Anda memiliki akses dan statusnya valid.'
            );
            $this->showCancelModal = false;
            $this->selectedPesananId = null;
            return;
        }

        // Logika pembatalan untuk admin (bisa batalkan kapan saja)
        if ($user->hasRole('admin')) {
            $pesanan->status = 'dibatalkan';
            $pesanan->save();

            $this->dispatch('showToast', 
                type: 'success',
                message: 'Pesanan berhasil dibatalkan oleh Admin.'
            );
        } 
        // Logika pembatalan untuk customer
        else {
            $pesanan->load('jadwal');
            
            if (!$pesanan->jadwal) {
                $this->dispatch('showToast', 
                    type: 'error',
                    message: 'Informasi jadwal untuk pesanan ini tidak ditemukan.'
                );
                $this->showCancelModal = false;
                $this->selectedPesananId = null;
                return;
            }

            $tanggalAcara = Carbon::parse($pesanan->jadwal->tanggal)->startOfDay(); // Pastikan waktu di awal hari
            $hariIni = Carbon::today()->startOfDay(); // Pastikan waktu di awal hari

            // Hitung selisih hari. Metode diffInDays() secara default sudah menghasilkan positif
            // ketika tanggal objek (tanggalAcara) di masa depan dibandingkan dengan argumen ($hariIni).
            $selisihHari = $hariIni->diffInDays($tanggalAcara); 
            // Contoh: Carbon::today('2025-05-27')->diffInDays(Carbon::parse('2025-06-03')) akan menghasilkan 7.

            // Kita perlu memastikan tanggal acara memang di masa depan (tidak hari ini atau di masa lalu)
            // dan selisihnya minimal 3 hari.
            if ($tanggalAcara->isAfter($hariIni->addDays(2))) { // Acara harus setelah 2 hari dari sekarang, yaitu H-3
                $pesanan->status = 'dibatalkan';
                $pesanan->save();

                $this->dispatch('showToast', 
                    type: 'success',
                    message: 'Pesanan berhasil dibatalkan.'
                );
            } else {
                $this->dispatch('showToast', 
                    type: 'warning',
                    message: 'Pesanan hanya bisa dibatalkan maksimal 3 hari sebelum tanggal pelaksanaan (H-3).'
                );
            }
        }

        $this->showCancelModal = false;
        $this->selectedPesananId = null;
    }
}