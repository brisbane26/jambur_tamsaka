<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Paket;
use App\Models\Jadwal;
use App\Models\Bank;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\DetailPesanan;
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

public function checkout_index(){
    $keranjangs = Keranjang::with('paket')
        ->where('user_id', Auth::id())
        ->get();
        
    $totalHarga = $keranjangs->sum(function($item) {
        return $item->paket->harga_jual * $item->kuantitas;
    });

    $banks = Bank::all(); // Ambil data bank dari database

    return view('checkout.index', compact('keranjangs', 'totalHarga', 'banks'));
}

public function checkout_store(Request $request){
    $request->validate([
        'nama_acara' => 'required|string|max:255',
        'tanggal_acara' => 'required|date|after_or_equal:today',
        'metode_bayar' => 'required|in:cash,transfer',
        'bukti_transfer' => 'required_if:metode_bayar,transfer|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Check date availability
    $isBooked = Jadwal::where('tanggal', $request->tanggal_acara)->exists();

        
    if ($isBooked) {
        return redirect()->back()->withErrors([
            'tanggal_acara' => 'Tanggal ini sudah dipesan oleh orang lain.'
        ])->withInput();
    }


    // Handle bukti transfer upload
    $buktiPath = null;
    if ($request->hasFile('bukti_transfer')) {
        $buktiPath = $request->file('bukti_transfer')->store('bukti_transfer', 'public');
    }


    
    // Create jadwal
    $jadwal = Jadwal::create([
        'tanggal' => $request->tanggal_acara,
        'nama_acara' => $request->nama_acara,
        'user_id' => Auth::id(),
        'status' => 'menunggu',
    ]);

    // Create pesanan
    $pesanan = Pesanan::create([
        'user_id' => Auth::id(),
        'jadwal_id' => $jadwal->id,
        'nama_acara' => $request->nama_acara,
        'status' => 'menunggu',
        'total_harga' => $request->total_harga,
        'bukti_transaksi' => $buktiPath,
    ]);

    // Create pembayaran
    $pembayaran = Pembayaran::create([
        'pesanan_id' => $pesanan->id,
        'metode_bayar' => $request->metode_bayar,
        'status' => $request->metode_bayar == 'cash' ? 'Pending' : 'Pending',
    ]);

    // Attach pakets to pesanan
    $keranjangs = Keranjang::where('user_id', Auth::id())->get();
    foreach ($keranjangs as $keranjang) {
        DetailPesanan::create([
            'pesanan_id' => $pesanan->id,
            'paket_id' => $keranjang->paket_id,
            'kuantitas' => $keranjang->kuantitas,
            'harga' => $keranjang->paket->harga_jual,
        ]);
    }

    // Clear cart
    Keranjang::where('user_id', Auth::id())->delete();

    $notifications = [
            'message' => 'Paket berhasil dicheckout',
            'alert-type' => 'success'
        ];

        return redirect()->route('keranjang.index')->with($notifications);
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

        $notifications = [
            'message' => 'Paket berhasil ditambahkan ke keranjang',
            'alert-type' => 'success'
        ];

        return redirect()->route('keranjang.index')->with($notifications);
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

    public function checkout(){


    }

    public function destroy(Keranjang $keranjang)
    {
        $keranjang->delete();
        
        return redirect()->route('keranjang.index')
             ->layout('layouts.admin.master');
    }
}
