<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keranjang; // Tidak digunakan di PesananController, bisa dihapus jika tidak ada fungsi lain
use App\Models\Paket;
use App\Models\Jadwal;
use App\Models\Bank; // Tidak digunakan di PesananController, bisa dihapus jika tidak ada fungsi lain
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\User;
use App\Models\DetailPesanan; // Pastikan ini di-import
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PesananController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $pesanans = Pesanan::with(['user', 'jadwal', 'detailPesanan.paket'])
            ->when(!$user->hasRole('admin'), function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->orderBy('id', 'asc')
            ->get();

        return view('pesanan.index', [
            'pesanans' => $pesanans,
            'isAdmin' => $user->hasRole('admin')
        ]);
    }

    public function show(Pesanan $pesanan)
    {
        if (Auth::user()->hasRole('customer') && $pesanan->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $pesanan->load(['detailPesanan.paket', 'jadwal', 'pembayaran']);
        
        $availableOptions = [];

        switch ($pesanan->status) {
            case 'menunggu':
                $availableOptions = [
                    'menunggu' => 'Menunggu',
                    'disetujui' => 'Disetujui',
                    'ditolak' => 'Ditolak',
                ];
                break;

            case 'disetujui':
                $availableOptions = [
                    'disetujui' => 'Disetujui',
                    'selesai' => 'Selesai',
                    'ditolak' => 'Ditolak',
                    'dibatalkan' => 'Dibatalkan',
                ];
                break;

            case 'selesai':
            case 'dibatalkan':
            case 'ditolak':
                break;
        }

        return view('pesanan.show', [
            'pesanan' => $pesanan,
            'isAdmin' => Auth::user()->hasRole('admin'),
            'statusOptions' => $availableOptions, 
        ]);
    }

    // Fungsi helper ini tidak lagi digunakan secara langsung untuk validasi konflik,
    // karena kita akan mengambil semua gedung dari detail pesanan.
    // Tapi bisa tetap dipertahankan jika ada kebutuhan lain.
    private function getGedungGroup(string $namaGedung): array
    {
        $normalizedName = strtolower(trim($namaGedung));
        
        $grupGedungUtama = 'Gedung Utama'; 
        $grupGedungResepsi = 'Gedung Resepsi';

        if (strtolower($grupGedungUtama) === $normalizedName) {
            return [$grupGedungUtama];
        }
        if (strtolower($grupGedungResepsi) === $normalizedName) {
            return [$grupGedungResepsi];
        }
        return [$namaGedung]; // Fallback jika bukan gedung utama/resepsi
    }

    public function updateStatus(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'status' => 'required|in:menunggu,disetujui,ditolak,selesai,dibatalkan',
            'alasan_tolak' => 'required_if:status,ditolak|nullable|string|max:255',
        ]);

        if ($pesanan->status === 'disetujui' && $request->status === 'menunggu') {
            return back()->with('error', 'Status yang sudah disetujui tidak dapat diubah kembali menjadi menunggu.');
        }

        // --- AWAL BLOK LOGIKA PERSETUJUAN DAN PENOLAKAN OTOMATIS ---
        if ($request->status === 'disetujui') {
            $pesanan->load('jadwal', 'detailPesanan.paket.kategori'); // Load kategori juga

            $tanggalAcara = $pesanan->jadwal->tanggal;

            // Dapatkan ID paket gedung yang ada di pesanan ini
            $gedungIdsInThisOrder = $pesanan->detailPesanan
                                            ->filter(function ($detail) {
                                                return $detail->paket && $detail->paket->kategori->nama_kategori === 'Gedung';
                                            })
                                            ->pluck('paket_id')
                                            ->unique()
                                            ->toArray();

            // Jika tidak ada gedung di pesanan ini, langsung setujui saja jika tidak ada konflik tanggal
            if (empty($gedungIdsInThisOrder)) {
                // Masih perlu cek apakah tanggal acara sudah ada di pesanan lain yang disetujui
                $anyConflictOnDate = Pesanan::where('status', 'disetujui')
                                            ->where('id', '!=', $pesanan->id)
                                            ->whereHas('jadwal', function ($query) use ($tanggalAcara) {
                                                $query->where('tanggal', $tanggalAcara);
                                            })->exists();
                if ($anyConflictOnDate) {
                    return redirect()->back()->with([
                        'message' => 'Konflik! Tanggal acara ini sudah digunakan oleh pesanan lain yang disetujui, dan pesanan ini tidak mengandung gedung.',
                        'alert-type' => 'warning'
                    ]);
                }
                $pesanan->update(['status' => 'disetujui', 'alasan_tolak' => null]);
                return redirect()->back()->with([
                    'message' => 'Pesanan berhasil disetujui! (Tidak ada gedung yang terlibat)',
                    'alert-type' => 'success'
                ]);
            }
            
            // Dapatkan ID dari Gedung Utama dan Gedung Resepsi
            $gedungUtamaId = Paket::where('nama_paket', 'Gedung Utama')->value('id');
            $gedungResepsiId = Paket::where('nama_paket', 'Gedung Resepsi')->value('id');

            $isThisOrderForGedungUtama = in_array($gedungUtamaId, $gedungIdsInThisOrder);
            $isThisOrderForGedungResepsi = in_array($gedungResepsiId, $gedungIdsInThisOrder);
            $isThisOrderForBothBuildings = $isThisOrderForGedungUtama && $isThisOrderForGedungResepsi;


            // Cek konflik dengan pesanan yang sudah disetujui pada tanggal yang sama
            $approvedOrdersOnSameDate = Pesanan::where('status', 'disetujui')
                ->where('id', '!=', $pesanan->id) // Exclude current order
                ->whereHas('jadwal', function ($query) use ($tanggalAcara) {
                    $query->where('tanggal', $tanggalAcara);
                })
                ->with('detailPesanan.paket') // Load detail pesanan dari pesanan yang sudah disetujui
                ->get();
            
            $existingApprovedGedungUtama = false;
            $existingApprovedGedungResepsi = false;

            foreach ($approvedOrdersOnSameDate as $approvedOrder) {
                foreach ($approvedOrder->detailPesanan as $detail) {
                    if ($detail->paket_id == $gedungUtamaId) {
                        $existingApprovedGedungUtama = true;
                    }
                    if ($detail->paket_id == $gedungResepsiId) {
                        $existingApprovedGedungResepsi = true;
                    }
                }
            }

            // --- Logika Deteksi Konflik yang Ditingkatkan ---
            $conflictMessage = '';
            if ($isThisOrderForBothBuildings) {
                if ($existingApprovedGedungUtama || $existingApprovedGedungResepsi) {
                    $conflictMessage = 'Konflik! Pesanan ini memesan kedua gedung, tetapi salah satu atau kedua gedung sudah disetujui pada tanggal yang sama.';
                }
            } elseif ($isThisOrderForGedungUtama) {
                if ($existingApprovedGedungUtama) {
                    $conflictMessage = 'Konflik! Pesanan ini memesan Gedung Utama, tetapi Gedung Utama sudah disetujui pada tanggal yang sama.';
                }
            } elseif ($isThisOrderForGedungResepsi) {
                if ($existingApprovedGedungResepsi) {
                    $conflictMessage = 'Konflik! Pesanan ini memesan Gedung Resepsi, tetapi Gedung Resepsi sudah disetujui pada tanggal yang sama.';
                }
            }

            if ($conflictMessage) {
                return redirect()->back()->with([
                    'message' => $conflictMessage,
                    'alert-type' => 'warning'
                ]);
            }
            // --- Akhir Logika Deteksi Konflik yang Ditingkatkan ---


            // Setujui pesanan ini
            $pesanan->update(['status' => 'disetujui', 'alasan_tolak' => null]);

            // Tolak otomatis pesanan lain yang "menunggu" dan berkonflik
            // Ambil ID gedung dari pesanan yang baru disetujui
            $gedungIdsBeingApproved = $pesanan->detailPesanan
                                            ->filter(function ($detail) {
                                                return $detail->paket && $detail->paket->kategori->nama_kategori === 'Gedung';
                                            })
                                            ->pluck('paket_id')
                                            ->toArray();

            // Hanya proses jika ada gedung yang disetujui
            if (!empty($gedungIdsBeingApproved)) {
                // Temukan semua pesanan "menunggu" pada tanggal yang sama
                $pendingOrdersOnSameDate = Pesanan::where('status', 'menunggu')
                    ->where('id', '!=', $pesanan->id) // Jangan tolak pesanan yang baru disetujui
                    ->whereHas('jadwal', function ($query) use ($tanggalAcara) {
                        $query->where('tanggal', $tanggalAcara);
                    })
                    ->with('detailPesanan.paket')
                    ->get();
                
                foreach ($pendingOrdersOnSameDate as $pendingOrder) {
                    $hasConflict = false;
                    $pendingOrderGedungIds = $pendingOrder->detailPesanan
                                                        ->filter(function ($detail) {
                                                            return $detail->paket && $detail->paket->kategori->nama_kategori === 'Gedung';
                                                        })
                                                        ->pluck('paket_id')
                                                        ->toArray();
                    
                    // Cek apakah ada irisan (gedung yang sama) antara gedung yang disetujui
                    // dengan gedung di pesanan menunggu
                    foreach ($gedungIdsBeingApproved as $approvedGedungId) {
                        if (in_array($approvedGedungId, $pendingOrderGedungIds)) {
                            $hasConflict = true;
                            break;
                        }
                    }

                    // Jika pesanan yang baru disetujui melibatkan Gedung Utama DAN Gedung Resepsi,
                    // maka setiap pesanan menunggu pada tanggal itu yang memesan SALAH SATU dari gedung tersebut harus ditolak.
                    // Jika pesanan yang baru disetujui hanya satu gedung (misal Gedung Utama),
                    // maka pesanan menunggu yang memesan Gedung Utama juga ditolak.
                    
                    // Logika yang lebih spesifik:
                    // Jika pesanan disetujui adalah Gedung Utama, tolak semua pending yang pesan Gedung Utama
                    // Jika pesanan disetujui adalah Gedung Resepsi, tolak semua pending yang pesan Gedung Resepsi
                    // Jika pesanan disetujui adalah KEDUA GEDUNG, tolak semua pending yang pesan Gedung Utama ATAU Gedung Resepsi

                    $shouldReject = false;
                    if ($isThisOrderForBothBuildings) { // Jika yang disetujui adalah kedua gedung
                        if (in_array($gedungUtamaId, $pendingOrderGedungIds) || in_array($gedungResepsiId, $pendingOrderGedungIds)) {
                            $shouldReject = true;
                        }
                    } elseif ($isThisOrderForGedungUtama) { // Jika yang disetujui hanya Gedung Utama
                        if (in_array($gedungUtamaId, $pendingOrderGedungIds)) {
                            $shouldReject = true;
                        }
                    } elseif ($isThisOrderForGedungResepsi) { // Jika yang disetujui hanya Gedung Resepsi
                        if (in_array($gedungResepsiId, $pendingOrderGedungIds)) {
                            $shouldReject = true;
                        }
                    }
                    
                    if ($shouldReject) {
                        $pendingOrder->update([
                            'status' => 'ditolak',
                            'alasan_tolak' => 'Mohon maaf, admin sudah menyetujui pesanan lain dengan gedung yang sama pada tanggal ini.'
                        ]);
                    }
                }
            }
            
            return redirect()->back()->with([
                'message' => 'Pesanan berhasil disetujui! Pesanan yang berkonflik pada tanggal yang sama telah ditolak.',
                'alert-type' => 'success'
            ]);
        }
        // --- AKHIR BLOK LOGIKA PERSETUJUAN ---

        // Jika status yang diupdate BUKAN 'disetujui', jalankan logika standar
        $pesanan->update([
            'status' => $request->status,
            'alasan_tolak' => $request->status === 'ditolak' ? $request->alasan_tolak : null
        ]);

        return redirect()->back()->with([
            'message' => 'Status pesanan berhasil diperbarui!',
            'alert-type' => 'success'
        ]);
    }

    public function updateBukti(Request $request, Pesanan $pesanan)
    {
        if ($pesanan->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Hapus bukti lama jika ada
        if ($pesanan->bukti_transaksi) {
            Storage::disk('public')->delete($pesanan->bukti_transaksi);
        }

        $buktiPath = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        $pesanan->update(['bukti_transaksi' => $buktiPath]);

        return redirect()->back()->with([
            'message' => 'Bukti transfer berhasil diperbarui.',
            'alert-type' => 'success'
        ]);
    }

    public function cancel(Pesanan $pesanan)
    {
        $user = Auth::user();

        // Admin bisa batalkan kapan saja
        if ($user->hasRole('admin')) {
            $pesanan->update(['status' => 'dibatalkan']);
            return back()->with([
                'message' => 'Pesanan berhasil dibatalkan oleh Admin.',
                'alert-type' => 'success'
            ]);
        }

        // Customer hanya bisa batalkan jika status menunggu/disetujui dan dia adalah pemilik pesanan
        if ($pesanan->user_id == $user->id && in_array($pesanan->status, ['menunggu', 'disetujui'])) {
            $pesanan->load('jadwal'); 

            if (!$pesanan->jadwal) {
                return back()->with([
                    'message' => 'Informasi jadwal untuk pesanan ini tidak ditemukan.',
                    'alert-type' => 'error'
                ]);
            }

            $tanggalAcara = Carbon::parse($pesanan->jadwal->tanggal)->startOfDay();
            $hariIni = Carbon::today()->startOfDay();

            if ($tanggalAcara->isAfter($hariIni->addDays(2))) { // Logika H-3
                $pesanan->update(['status' => 'dibatalkan']);

                return back()->with([
                    'message' => 'Pesanan berhasil dibatalkan.',
                    'alert-type' => 'success'
                ]);
            } else {
                return back()->with([
                    'message' => 'Pesanan hanya bisa dibatalkan maksimal 3 hari sebelum tanggal pelaksanaan (H-3).',
                    'alert-type' => 'warning'
                ]);
            }
        }

        return back()->with([
            'message' => 'Anda tidak dapat membatalkan pesanan ini karena bukan pemilik atau status tidak valid.',
            'alert-type' => 'alert'
        ]);
    }

    public function history(Request $request)
    {
        $user = Auth::user();
        $statusFilter = $request->input('status');

        $pesanans = Pesanan::whereIn('status', ['selesai', 'dibatalkan', 'ditolak'])
            ->with(['user', 'jadwal'])
            ->when(!$user->hasRole('admin'), function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->when($statusFilter, function ($query) use ($statusFilter) {
                $query->where('status', $statusFilter);
            })
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('pesanan.history', compact('pesanans', 'statusFilter'));
    }

    public function laporan(Request $request) 
    {
        $filter = $request->input('filter');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Pesanan::with(['user', 'jadwal'])->where('status', 'selesai');

        $startDateForDisplay = null;
        $endDateForDisplay = null;
        $filterDescription = null;

        if ($filter === 'minggu') {
            $start = Carbon::now()->startOfWeek();
            $end = Carbon::now()->endOfWeek();
            $query->whereHas('jadwal', function ($q) use ($start, $end) {
                $q->whereBetween('tanggal', [$start, $end]);
            });
            $startDateForDisplay = $start;
            $endDateForDisplay = $end;
            $filterDescription = 'Minggu Ini';
        } elseif ($filter === 'bulan') {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
            $query->whereHas('jadwal', function ($q) use ($start, $end) {
                $q->whereBetween('tanggal', [$start, $end]);
            });
            $startDateForDisplay = $start;
            $endDateForDisplay = $end;
            $filterDescription = 'Bulan Ini';
        } elseif ($filter === 'tahun') {
            $start = Carbon::now()->startOfYear();
            $end = Carbon::now()->endOfYear();
            $query->whereHas('jadwal', function ($q) use ($start, $end) {
                $q->whereBetween('tanggal', [$start, $end]);
            });
            $startDateForDisplay = $start;
            $endDateForDisplay = $end;
            $filterDescription = 'Tahun Ini';
        } elseif ($filter === 'custom' && $startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
            $query->whereHas('jadwal', function ($q) use ($start, $end) {
                $q->whereBetween('tanggal', [$start, $end]);
            });
            $startDateForDisplay = $start;
            $endDateForDisplay = $end;
            $filterDescription = 'Rentang Tanggal Kustom';
        } else {
            $firstPesananDate = Pesanan::where('status', 'selesai')->orderBy('created_at', 'asc')->value('created_at');
            $lastPesananDate = Pesanan::where('status', 'selesai')->orderBy('created_at', 'desc')->value('created_at');

            if ($firstPesananDate && $lastPesananDate) {
                $startDateForDisplay = Carbon::parse($firstPesananDate)->startOfDay();
                $endDateForDisplay = Carbon::parse($lastPesananDate)->endOfDay();
                $filterDescription = 'Semua Waktu';
            } else {
                $filterDescription = 'Tidak ada data laporan.';
            }
        }

        $pesanans = $query->get()->sortBy(function ($item) {
            return $item->jadwal->tanggal;
        });

        $totalPesanan = $pesanans->count();
        $totalPendapatan = $pesanans->sum('total_keuntungan');

        return view('laporan.index', compact('pesanans', 'filter', 'totalPendapatan', 'totalPesanan', 'startDateForDisplay', 'endDateForDisplay', 'filterDescription'));
    }

    public function detail(Pesanan $pesanan)
    {
        if (Auth::user()->hasRole('customer') && $pesanan->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $pesanan->load(['detailPesanan.paket', 'jadwal', 'pembayaran']);

        return view('laporan.detail', [
            'pesanan' => $pesanan,
            'isAdmin' => Auth::user()->hasRole('admin')
        ]);
    }

    public function dashboardData()
    {
        $user = Auth::user();

        $pesananDiproses = Pesanan::where('user_id', $user->id)
            ->where('status', 'menunggu')
            ->count();

        $pesananSelesai = Pesanan::where('user_id', $user->id)
            ->where('status', 'selesai')
            ->count();

        $totalPengeluaran = Pesanan::where('user_id', $user->id)
            ->whereIn('status', ['menunggu', 'disetujui', 'selesai'])
            ->with('detailPesanan.paket')
            ->get()
            ->flatMap(function($pesanan) {
                return $pesanan->detailPesanan;
            })
            ->reduce(function($carry, $detail) {
                return $carry + ($detail->harga * $detail->kuantitas);
            }, 0);

        return compact('pesananDiproses', 'pesananSelesai', 'totalPengeluaran');
    }

    public function dashboard()
    {
        $data = $this->dashboardData();
        return view('customer.dashboard', $data);
    }
}