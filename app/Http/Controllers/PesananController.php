<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keranjang;
use App\Models\Paket;
use App\Models\Jadwal;
use App\Models\Bank;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    
    public function index()
{
    $user = auth()->user();
    
    $pesanans = Pesanan::with(['user', 'jadwal'])
        ->when($user->role !== 'admin', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->latest()
        ->get();

    return view('pesanan.index', [
        'pesanans' => $pesanans,
        'isAdmin' => $user->role === 'admin'
    ]);
}




   // PesananController.php

public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:menunggu,disetujui,ditolak,selesai,dibatalkan'
    ]);

    $pesanan = Pesanan::findOrFail($id);
    $pesanan->status = $request->status;
    $pesanan->save();

    return redirect()->back()->with('message', 'Status pesanan berhasil diperbarui.');
}


public function konfirmasi($id)
{
    $pesanan = Pesanan::findOrFail($id);
    $pesanan->update(['status' => 'disetujui']);

    return back()->with('message', 'Pesanan berhasil dikonfirmasi');
}

public function cancel($id)
{
    $pesanan = Pesanan::findOrFail($id);
    
    if (!in_array($pesanan->status, ['menunggu', 'disetujui'])) {
        return back()->with('error', 'Pesanan tidak dapat dibatalkan');
    }

    $pesanan->update(['status' => 'dibatalkan']);

    return back()->with('message', 'Pesanan berhasil dibatalkan');
}
    
}
