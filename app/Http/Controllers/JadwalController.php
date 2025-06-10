<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Ambil jadwal yang terkait dengan pesanan disetujui
            // Eager load relasi yang diperlukan untuk mendapatkan nama gedung
            $data = Jadwal::with(['pesanan.detailPesanan.paket.kategori'])
                ->whereHas('pesanan', function($query) {
                    $query->where('status', 'disetujui');
                })
                ->whereDate('tanggal', '>=', now())
                ->get();

            $events = $data->map(function ($jadwal) {
                $namaGedung = null;
                // Iterasi melalui detail pesanan untuk menemukan paket dengan kategori Gedung
                if ($jadwal->pesanan) {
                    foreach ($jadwal->pesanan->detailPesanan as $detail) {
                        // Pastikan paket dan kategori ada, dan kategori_id adalah 1 (Gedung)
                        if ($detail->paket && $detail->paket->kategori && $detail->paket->kategori->id === 1) {
                            $namaGedung = $detail->paket->nama_paket; // Asumsi nama gedung ada di nama_paket
                            break; // Hentikan setelah menemukan gedung pertama
                        }
                    }
                }

                // Gunakan nama gedung jika ditemukan, jika tidak, kembali ke nama_acara atau default lain
                $title = $namaGedung ?: $jadwal->nama_acara;

                return [
                    'id'    => $jadwal->id,
                    'title' => $title, // Menggunakan nama gedung atau nama acara
                    'start' => $jadwal->tanggal,
                    'user_id' => $jadwal->user_id // Tambahkan ini jika dibutuhkan di frontend
                ];
            });
            
            return response()->json($events);
        }

        return view('jadwal.index');
    }

    public function ajax(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        switch ($request->type) {
            case 'update':
                $event = Jadwal::find($request->id);
                if ($event) {
                    // Jika Anda ingin mengizinkan admin untuk mengubah nama acara di kalender
                    // Dan nama acara ini sebenarnya adalah nama gedung, maka nama_acara di tabel jadwal
                    // akan menyimpan nama gedung tersebut.
                    $event->update(['nama_acara' => $request->nama_acara]); 
                    return response()->json([
                        'id' => $event->id,
                        'title' => $event->nama_acara,
                        'start' => $event->tanggal
                    ]);
                }
                return response()->json(['error' => 'Acara tidak ditemukan.'], 404);

            case 'delete':
                $event = Jadwal::find($request->id);
                if ($event && $event->pesanan) {
                    // Update status pesanan terkait menjadi dibatalkan
                    $event->pesanan->update(['status' => 'dibatalkan']);
                    return response()->json(['success' => true]);
                }
                return response()->json(['error' => 'Acara tidak ditemukan.'], 404);
        }
    }
}