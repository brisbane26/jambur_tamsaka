<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pesanan;

class ShowPesanans extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.show-pesanans', [
            'pesanans' => Pesanan::paginate(10),
        ]);
    }
}
