<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Jadwal::with(['pesanan.detailPesanan.paket.kategori'])
                ->whereHas('pesanan', function($query) {
                    $query->where('status', 'disetujui');
                })
                ->whereDate('tanggal', '>=', now())
                ->get();

            $events = []; // Inisialisasi array kosong untuk menampung semua event

            foreach ($data as $jadwal) {
                $gedungDitemukan = false;
                
                if ($jadwal->pesanan) {
                    foreach ($jadwal->pesanan->detailPesanan as $detail) {
                        // Jika paket adalah gedung
                        if ($detail->paket && $detail->paket->kategori && $detail->paket->kategori->id === 1) {
                            $gedungDitemukan = true;
                            // Tambahkan event baru untuk setiap gedung
                            $events[] = [
                                'id'            => 'gedung-' . $jadwal->id . '-' . $detail->paket->id, // ID unik untuk setiap event gedung
                                'title'         => $detail->paket->nama_paket, // Nama gedung untuk tampilan kalender
                                'originalTitle' => $jadwal->nama_acara, // Nama acara asli untuk modal
                                'start'         => $jadwal->tanggal,
                                'user_id'       => $jadwal->user_id,
                                'jadwal_id'     => $jadwal->id // Simpan ID jadwal asli
                            ];
                        }
                    }
                }

                // Jika tidak ada gedung yang ditemukan untuk jadwal ini, 
                // tambahkan event dengan nama acara asli
                if (!$gedungDitemukan) {
                     $events[] = [
                        'id'            => $jadwal->id, 
                        'title'         => $jadwal->nama_acara, // Nama acara untuk tampilan kalender
                        'originalTitle' => $jadwal->nama_acara, // Nama acara asli untuk modal
                        'start'         => $jadwal->tanggal,
                        'user_id'       => $jadwal->user_id,
                        'jadwal_id'     => $jadwal->id // Simpan ID jadwal asli
                    ];
                }
            }
            
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
                // Ketika mengupdate, kita perlu tahu ID jadwal yang sebenarnya
                // Karena ID event di frontend bisa jadi 'gedung-IDJadwal-IDPaket'
                // Kita perlu mendapatkan ID jadwal dari request atau event itu sendiri
                $jadwalIdToUpdate = $request->input('jadwal_id') ?? $request->id; // Coba ambil dari jadwal_id, fallback ke id jika tidak ada

                $event = Jadwal::find($jadwalIdToUpdate);
                if ($event) {
                    $event->update(['nama_acara' => $request->nama_acara]); 
                    // Saat update, kita harus memicu refresh kalender
                    // Agar semua event yang terkait dengan jadwal ini terupdate.
                    // Atau, kita bisa mengembalikan event yang diupdate dengan title yang benar.
                    // Untuk kesederhanaan, mari kembalikan data yang cukup untuk refresh.
                    return response()->json([
                        'id' => $event->id,
                        'title' => $event->nama_acara, // Setelah diupdate, title bisa jadi nama_acara
                        'start' => $event->tanggal,
                        'originalTitle' => $event->nama_acara // Selalu kirim nama_acara asli untuk modal
                    ]);
                }
                return response()->json(['error' => 'Acara tidak ditemukan.'], 404);

            case 'delete':
                // Sama seperti update, pastikan kita mendapatkan ID jadwal yang benar
                $jadwalIdToDelete = $request->input('jadwal_id') ?? $request->id;

                $event = Jadwal::find($jadwalIdToDelete);
                if ($event && $event->pesanan) {
                    $event->pesanan->update(['status' => 'dibatalkan']);
                    return response()->json(['success' => true, 'jadwal_id' => $jadwalIdToDelete]); // Kirim jadwal_id
                }
                return response()->json(['error' => 'Acara tidak ditemukan.'], 404);
        }
    }
}