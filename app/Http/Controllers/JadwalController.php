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

                // Gunakan nama gedung untuk 'title' yang akan ditampilkan di kalender
                // Simpan nama acara asli di properti 'originalTitle' untuk modal
                return [
                    'id'            => $jadwal->id,
                    'title'         => $namaGedung ?: $jadwal->nama_acara, // Untuk tampilan kalender (nama gedung atau nama acara)
                    'originalTitle' => $jadwal->nama_acara, // Nama acara asli untuk modal
                    'start'         => $jadwal->tanggal,
                    'user_id'       => $jadwal->user_id
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
                    // Saat update, kita ingin mengupdate nama_acara yang sebenarnya di database
                    // Jadi, gunakan $request->nama_acara
                    $event->update(['nama_acara' => $request->nama_acara]); 
                    return response()->json([
                        'id' => $event->id,
                        'title' => $event->nama_acara, // Saat update, kirim nama acara yang baru sebagai title
                        'start' => $event->tanggal,
                        'originalTitle' => $event->nama_acara // Pastikan juga originalTitle terupdate
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