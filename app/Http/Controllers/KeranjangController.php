<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    public function index()
    {
        $keranjangs = Keranjang::with('paket')->where('user_id', Auth::id())->get();
        $totalHarga = $keranjangs->sum('total_harga');
        
        return view('keranjang.index', compact('keranjangs', 'totalHarga'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'paket_id' => 'required|exists:pakets,id',
        ]);

        $keranjang = Keranjang::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'paket_id' => $request->paket_id,
            ],
            ['kuantitas' => 1]
        );

        return redirect()->route('keranjang.index');
    }

    public function update(Request $request, Keranjang $keranjang)
    {
        $request->validate([
            'kuantitas' => 'required|integer|min:0',
        ]);

        if ($request->kuantitas == 0) {
            $keranjang->delete();
        } else {
            $keranjang->update(['kuantitas' => $request->kuantitas]);
        }

        return redirect()->route('keranjang.index');
    }

    public function destroy(Keranjang $keranjang)
    {
        $keranjang->delete();
        return redirect()->route('keranjang.index')
             ->layout('layouts.admin.master');
    }
}
