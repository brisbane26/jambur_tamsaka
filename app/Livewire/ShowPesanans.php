<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;

class ShowPesanans extends Component
{
    use WithPagination;

    public function render()
    {
        if (Auth::user()->hasRole('admin')) {
            // Admin melihat semua pesanan kecuali yang dibatalkan dan ditolak
            $pesanans = Pesanan::whereNotIn('status', ['dibatalkan', 'ditolak'])
                            ->with(['user', 'jadwal'])
                            ->paginate(10);
        } else {
            // Customer melihat pesanannya sendiri, kecuali yang dibatalkan dan ditolak
            $pesanans = Pesanan::where('user_id', Auth::id())
                            ->whereNotIn('status', ['dibatalkan', 'ditolak'])
                            ->with(['user', 'jadwal'])
                            ->paginate(10);
        }

        return view('livewire.show-pesanans', compact('pesanans'));
    }
}
