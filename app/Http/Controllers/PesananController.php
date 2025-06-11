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
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PesananController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Gunakan scope atau query langsung
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
        // Authorization dengan @role di blade atau middleware
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

    private function getGedungGroup(string $namaGedung): array
    {
        // Normalisasi input ke huruf kecil untuk perbandingan yang konsisten
        $normalizedName = strtolower(trim($namaGedung));
        
        $grupGedungSatu = [
            'Gedung Utama', 
        ];

        $grupGedungDua = [
            'Gedung Resepsi',
        ];

        if (in_array($normalizedName, $grupGedungSatu)) {
            return $grupGedungSatu; 
        }

        if (in_array($normalizedName, $grupGedungDua)) {
            return $grupGedungDua;
        }

        return [$namaGedung];
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
    
    if ($request->status === 'disetujui') {
        $pesanan->load('jadwal', 'detailPesanan.paket');

        $tanggalAcara = $pesanan->jadwal->tanggal;
        $firstDetail = $pesanan->detailPesanan->first();

        if (!$firstDetail || !$firstDetail->paket) {
            return back()->with('error', 'Gagal memeriksa konflik: Detail paket tidak ditemukan pada pesanan ini.');
        }
        $namaGedung = $firstDetail->paket->nama_paket;

        $gedungGroup = $this->getGedungGroup($namaGedung);

        $conflict = Pesanan::where('status', 'disetujui')
            ->where('id', '!=', $pesanan->id)
            ->whereHas('jadwal', function ($query) use ($tanggalAcara) {
                $query->where('tanggal', $tanggalAcara);
            })
            ->whereHas('detailPesanan.paket', function ($query) use ($gedungGroup) {
                $query->whereIn('nama_paket', $gedungGroup);
            })
            ->exists();

        if ($conflict) {

        $notifications = [
        'message' => 'Konflik! Sudah ada pesanan lain yang disetujui untuk gedung dan tanggal ini.',
        'alert-type' => 'warning'
        ];

        return redirect()->back()->with($notifications);
        }
    }

    $pesanan->update([
        'status' => $request->status,
        'alasan_tolak' => $request->status === 'ditolak' ? $request->alasan_tolak : null
    ]);

    $notifications = [
        'message' => 'Status pesanan berhasil diperbarui!',
        'alert-type' => 'success'
    ];

    return redirect()->back()->with($notifications);
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

        $notifications = [
            'message' => 'Bukti transfer berhasil diperbarui.',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notifications);
    }

    public function cancel(Pesanan $pesanan)
    {
        $user = Auth::user();

        // Admin bisa batalkan kapan saja
        if ($user->hasRole('admin')) {
            $pesanan->update(['status' => 'dibatalkan']);
            $notifications = [
                'message' => 'Pesanan berhasil dibatalkan oleh Admin.',
                'alert-type' => 'success'
            ];
            return back()->with($notifications);
        }

        // Customer hanya bisa batalkan jika status menunggu/disetujui dan dia adalah pemilik pesanan
        if ($pesanan->user_id == $user->id && in_array($pesanan->status, ['menunggu', 'disetujui'])) {
            // Pastikan relasi jadwal dimuat untuk mendapatkan tanggal acara
            $pesanan->load('jadwal'); 

            // Periksa apakah jadwal ada untuk pesanan ini
            if (!$pesanan->jadwal) {
                $notifications = [
                    'message' => 'Informasi jadwal untuk pesanan ini tidak ditemukan.',
                    'alert-type' => 'error'
                ];
                return back()->with($notifications);
            }

            // Pastikan tanggal acara dan hari ini di awal hari untuk perbandingan yang akurat
            $tanggalAcara = Carbon::parse($pesanan->jadwal->tanggal)->startOfDay();
            $hariIni = Carbon::today()->startOfDay();

            // Logika H-3: Tanggal acara harus setelah 2 hari dari hari ini (yaitu, minimal H-3)
            // Contoh: Jika hari ini 27 Mei, acara harus setelah 29 Mei (yaitu mulai 30 Mei)
            if ($tanggalAcara->isAfter($hariIni->addDays(2))) {
                $pesanan->update(['status' => 'dibatalkan']);

                $notifications = [
                    'message' => 'Pesanan berhasil dibatalkan.',
                    'alert-type' => 'success'
                ];
                return back()->with($notifications);
            } else {
                $notifications = [
                    'message' => 'Pesanan hanya bisa dibatalkan maksimal 3 hari sebelum tanggal pelaksanaan (H-3).',
                    'alert-type' => 'warning'
                ];
                return back()->with($notifications);
            }
        }

        // Jika tidak memenuhi kriteria di atas (bukan admin, bukan pemilik, atau status tidak valid)
        $notifications = [
            'message' => 'Anda tidak dapat membatalkan pesanan ini karena bukan pemilik atau status tidak valid.',
            'alert-type' => 'alert'
        ];

        return back()->with($notifications);
    }

    public function history(Request $request)
    {
        $user = Auth::user();
        $statusFilter = $request->input('status'); // nilai bisa: 'selesai', 'dibatalkan', 'ditolak', atau null

        $pesanans = Pesanan::whereIn('status', ['selesai', 'dibatalkan', 'ditolak'])
            ->with(['user', 'jadwal']) // tambahkan 'jadwal' juga agar tidak N+1 di view
            ->when(!$user->hasRole('admin'), function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->when($statusFilter, function ($query) use ($statusFilter) {
                $query->where('status', $statusFilter);
            })
            ->orderBy('id', 'asc')
            ->paginate(10); // tambahkan pagination agar lebih rapi

        return view('pesanan.history', compact('pesanans', 'statusFilter'));
    }

    public function laporan(Request $request) 
    {
        $filter = $request->input('filter'); // minggu, bulan, tahun

        $query = Pesanan::with(['user', 'jadwal'])->where('status', 'selesai');

        if ($filter === 'minggu') {
            $query->whereHas('jadwal', function ($q) {
                $q->whereBetween('tanggal', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
            });
        } elseif ($filter === 'bulan') {
            $query->whereHas('jadwal', function ($q) {
                $q->whereMonth('tanggal', Carbon::now()->month)
                    ->whereYear('tanggal', Carbon::now()->year);
            });
        } elseif ($filter === 'tahun') {
            $query->whereHas('jadwal', function ($q) {
                $q->whereYear('tanggal', Carbon::now()->year);
            });
        }

        // Ambil semua data dengan urutan berdasarkan tanggal dari tabel jadwal
        $pesanans = $query->get()->sortBy(function ($item) {
            return $item->jadwal->tanggal;
        });

        $totalPendapatan = $pesanans->sum('total_keuntungan');

        return view('laporan.index', compact('pesanans', 'filter', 'totalPendapatan'));
    }

    public function detail(Pesanan $pesanan)
    {
        // Authorization dengan @role di blade atau middleware
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

        // Jumlah pesanan diproses (status menunggu)
        $pesananDiproses = Pesanan::where('user_id', $user->id)
            ->where('status', 'menunggu')
            ->count();

        // Jumlah pesanan selesai
        $pesananSelesai = Pesanan::where('user_id', $user->id)
            ->where('status', 'selesai')
            ->count();

        // Total pengeluaran user: jumlah semua harga paket * kuantitas dari detail pesanan pesanan user
        // Kita join detailPesanan dan paket
        $totalPengeluaran = Pesanan::where('user_id', $user->id)
            ->whereIn('status', ['menunggu', 'disetujui', 'selesai']) // status pesanan yang dihitung (sesuaikan)
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