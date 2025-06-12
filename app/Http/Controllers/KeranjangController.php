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
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class KeranjangController extends Controller
{
    public function index()
    {
        $keranjangs = Keranjang::with('paket')->where('user_id', Auth::id())->get();
        $totalHarga = $keranjangs->sum('total_harga');
        
        return view('keranjang.index', compact('keranjangs', 'totalHarga'));
    }

    public function checkout_index()
    {
        $keranjangs = Keranjang::with(['paket', 'paket.kategori'])
            ->where('user_id', Auth::id())
            ->get();
            
        // Validasi minimal satu paket gedung
        $hasGedung = $keranjangs->contains(function ($item) {
            return $item->paket->kategori->nama_kategori === 'Gedung';
        });

        if (!$hasGedung) {
            return redirect()->back()->with([
                'message' => 'Silakan pilih minimal satu paket gedung',
                'alert-type' => 'error'
            ]);
        }

        foreach ($keranjangs as $item) {
            $kategori = $item->paket->kategori->nama_kategori;
            $qty = $item->kuantitas;

            $batas = [
                'Catering' => ['min' => 50, 'max' => 2000],
                'Salon' => ['min' => 1, 'max' => 1],
                'Musik' => ['min' => 1, 'max' => 1],
                'Dekorasi' => ['min' => 1, 'max' => 1],
                'Gedung' => ['min' => 1, 'max' => 1],
                'Dokumentasi' => ['min' => 1, 'max' => 1],
                'Lainnya' => ['min' => 1, 'max' => 1],
            ];

            $min = $batas[$kategori]['min'] ?? 1;
            $max = $batas[$kategori]['max'] ?? 1;

            if ($qty < $min || $qty > $max) {
                return redirect()->back()->with([
                    'message' => "Kuantitas paket {$item->paket->nama_paket} harus antara {$min} sampai {$max}.",
                    'alert-type' => 'error'
                ]);
            }
        }

        $totalHarga = $keranjangs->sum(function($item) {
            return $item->paket->harga_jual * $item->kuantitas;
        });

        $banks = Bank::all();

        return view('checkout.index', compact('keranjangs', 'totalHarga', 'banks'));
    }

    public function checkout_store(Request $request)
    {
        // 1. Validasi input dari form
        $request->validate([
            'nama_acara' => 'required|string|max:255',
            'tanggal_acara' => 'required|date|after_or_equal:' . now()->addDays(3)->toDateString(),
            'metode_bayar' => 'required|in:cash,transfer',
            'bukti_transfer' => 'required_if:metode_bayar,transfer|image|mimes:jpeg,png,jpg|max:2048',
            'catatan' => 'nullable|string|max:1000'
        ], [
            // Pesan validasi kustom
            'tanggal_acara.after_or_equal' => 'Tanggal acara harus minimal 3 hari dari sekarang.',
            'tanggal_acara.required' => 'Kolom tanggal acara wajib diisi.',
            'nama_acara.required' => 'Kolom nama acara wajib diisi.',
            'metode_bayar.required' => 'Metode pembayaran wajib dipilih.',
            'bukti_transfer.required_if' => 'Bukti transfer wajib diunggah jika metode pembayaran adalah transfer.',
            'bukti_transfer.image' => 'File bukti transfer harus berupa gambar.',
            'bukti_transfer.mimes' => 'Format gambar yang diperbolehkan adalah jpeg, png, jpg.',
            'bukti_transfer.max' => 'Ukuran gambar bukti transfer tidak boleh lebih dari 2MB.',
        ]);

        $tanggalAcara = $request->tanggal_acara;
        $currentUser = Auth::user();

        // --- AWAL LOGIKA PEMBATASAN PESANAN 24 JAM UNTUK CUSTOMER ---
        if ($currentUser->hasRole('customer') && $request->metode_bayar === 'cash') {
            $lastOrder = Pesanan::where('user_id', $currentUser->id)
                                ->whereIn('status', ['menunggu', 'disetujui'])
                                ->whereHas('pembayaran', function($query) {
                                    $query->where('metode_bayar', 'cash'); // Hanya pesanan bayar di tempat
                                })
                                ->latest('created_at')
                                ->first();

            if ($lastOrder) {
                $timeAllowedForNextOrder = Carbon::parse($lastOrder->created_at)->addHours(24);
                $currentTime = Carbon::now();

                // Hitung sisa waktu dalam detik jika waktu saat ini masih di bawah waktu yang diizinkan untuk pemesanan berikutnya
                $remainingTimeInSeconds = $currentTime->diffInSeconds($timeAllowedForNextOrder, false); 
                // 'false' agar diffInSeconds mengembalikan nilai negatif jika $currentTime > $timeAllowedForNextOrder

                if ($remainingTimeInSeconds > 0) { // Jika masih ada sisa waktu positif
                    $hours = floor($remainingTimeInSeconds / 3600);
                    $minutes = floor(($remainingTimeInSeconds % 3600) / 60);
                    $seconds = $remainingTimeInSeconds % 60;
                    
                    $remainingTimeString = '';
                    if ($hours > 0) {
                        $remainingTimeString .= $hours . ' jam ';
                    }
                    if ($minutes > 0) {
                        $remainingTimeString .= $minutes . ' menit ';
                    }
                    // Tampilkan detik jika tidak ada jam atau menit, atau jika detik > 0
                    if ($seconds > 0 || ($hours == 0 && $minutes == 0)) { 
                        $remainingTimeString .= $seconds . ' detik';
                    }
                    $remainingTimeString = trim($remainingTimeString);

                    return back()->with([
                        'message' => "Mohon maaf, Anda hanya bisa memesan sekali dalam 24 jam. Sisa waktu: {$remainingTimeString}.",
                        'alert-type' => 'error'
                    ])->withInput();
                }
            }
        }
        // --- AKHIR LOGIKA PEMBATASAN PESANAN 24 JAM UNTUK CUSTOMER ---


        $gedungUtamaId = Paket::where('nama_paket', 'Gedung Utama')->value('id');
        $gedungResepsiId = Paket::where('nama_paket', 'Gedung Resepsi')->value('id');

        // Ambil semua paket dari keranjang pengguna
        $keranjangItems = Keranjang::where('user_id', auth()->id())->pluck('paket_id')->toArray();

        $pesanGedungUtama = in_array($gedungUtamaId, $keranjangItems);
        $pesanGedungResepsi = in_array($gedungResepsiId, $keranjangItems);

        // Validasi bahwa minimal harus ada 1 gedung di keranjang
        if (!$pesanGedungUtama && !$pesanGedungResepsi) {
            return back()->with([
                'message' => 'Anda harus memesan minimal 1 gedung (Gedung Utama atau Gedung Resepsi) untuk melanjutkan.',
                'alert-type' => 'error'
            ])->withInput();
        }

        // --- Logika validasi ketersediaan gedung yang diperbarui ---
        // Cari pesanan yang sudah disetujui pada tanggal yang sama
        $approvedOrdersOnDate = Pesanan::where('status', 'disetujui')
            ->whereHas('jadwal', function ($query) use ($tanggalAcara) {
                $query->where('tanggal', $tanggalAcara);
            })
            ->with('detailPesanan.paket')
            ->get();

        $isGedungUtamaApproved = false;
        $isGedungResepsiApproved = false;

        foreach ($approvedOrdersOnDate as $order) {
            foreach ($order->detailPesanan as $detail) {
                if ($detail->paket_id == $gedungUtamaId) {
                    $isGedungUtamaApproved = true;
                }
                if ($detail->paket_id == $gedungResepsiId) {
                    $isGedungResepsiApproved = true;
                }
            }
        }

        if ($isGedungUtamaApproved && $isGedungResepsiApproved) {
            if ($pesanGedungUtama || $pesanGedungResepsi) {
                return back()->with([
                    'message' => 'Kedua gedung sudah dipesan pada tanggal yang sama.',
                    'alert-type' => 'error'
                ])->withInput();
            }
        } elseif ($isGedungUtamaApproved) {
            if ($pesanGedungUtama) {
                return back()->with([
                    'message' => 'Gedung utama sudah dipesan pada tanggal yang sama.',
                    'alert-type' => 'error'
                ])->withInput();
            }
        } elseif ($isGedungResepsiApproved) {
            if ($pesanGedungResepsi) {
                return back()->with([
                    'message' => 'Gedung resepsi sudah dipesan pada tanggal yang sama.',
                    'alert-type' => 'error'
                ])->withInput();
            }
        }
        // --- Akhir logika validasi ketersediaan gedung yang diperbarui ---

        $buktiPath = null;
        if ($request->hasFile('bukti_transfer')) {
            $buktiPath = $request->file('bukti_transfer')->store('bukti_transfer', 'public');
        }

        $jadwal = Jadwal::create([
            'tanggal' => $tanggalAcara,
            'nama_acara' => $request->nama_acara,
            'user_id' => Auth::id(),
        ]);

        $pesanan = Pesanan::create([
            'user_id' => Auth::id(),
            'jadwal_id' => $jadwal->id,
            'status' => 'menunggu', // Status default saat dibuat
            'bukti_transaksi' => $buktiPath,
            'catatan' => $request->catatan, // Pindahkan catatan ke pesanan utama
        ]);

        Pembayaran::create([
            'pesanan_id' => $pesanan->id,
            'metode_bayar' => $request->metode_bayar,
        ]);

        foreach (Keranjang::where('user_id', Auth::id())->get() as $item) {
            DetailPesanan::create([
                'pesanan_id' => $pesanan->id,
                'paket_id' => $item->paket_id,
                'kuantitas' => $item->kuantitas,
                'harga' => $item->paket->harga_jual,
                // Kolom catatan per item tidak lagi relevan jika catatan ada di pesanan utama
            ]);
        }

        // Kosongkan keranjang setelah pesanan berhasil dibuat
        Keranjang::where('user_id', Auth::id())->delete();

        return redirect()->route('pesanan.index')->with([
            'message' => 'Pesanan berhasil dibuat dan sedang menunggu konfirmasi.',
            'alert-type' => 'success'
        ]);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'paket_id' => 'required|exists:pakets,id',
        ]);

        // Ambil data paket beserta kategorinya
        $paket = Paket::with('kategori')->findOrFail($request->paket_id);
        
        // Tentukan kuantitas default dan batas
        $quantity = 1;
        $kategoriNama = $paket->kategori->nama_kategori;

        $batas = [
            'Catering' => ['min' => 50, 'max' => 2000],
            'Salon' => ['min' => 1, 'max' => 1],
            'Musik' => ['min' => 1, 'max' => 1],
            'Dekorasi' => ['min' => 1, 'max' => 1],
            'Gedung' => ['min' => 1, 'max' => 1],
            'Dokumentasi' => ['min' => 1, 'max' => 1],
            'Lainnya' => ['min' => 1, 'max' => 1],
        ];

        $maxQty = $batas[$kategoriNama]['max'] ?? 1;

        // Jika kategori mengandung 'Catering', set kuantitas ke 50
        if (str_contains($kategoriNama, 'Catering')) {
            $quantity = 50;
        }

        // Cari keranjang yang sudah ada
        $keranjang = Keranjang::where([
            'user_id' => Auth::id(),
            'paket_id' => $request->paket_id,
        ])->first();

        // Jika ada, periksa apakah masih bisa ditambah kuantitasnya
        if ($keranjang) {
            // Periksa jika kuantitas saat ini sudah mencapai batas maksimum
            if ($keranjang->kuantitas >= $maxQty) {
                // Jika sudah mencapai batas, kembalikan dengan pesan error
                return back()->with([
                    'message' => "Kuantitas paket {$paket->nama_paket} sudah mencapai batas maksimum ({$maxQty}).",
                    'alert-type' => 'error'
                ]);
            }
            // Jika belum mencapai batas, tambahkan kuantitas
            $keranjang->increment('kuantitas', $quantity);
        } else {
            // Jika tidak, buat baru dengan kuantitas yang ditentukan
            Keranjang::create([
                'user_id' => Auth::id(),
                'paket_id' => $request->paket_id,
                'kuantitas' => $quantity,
            ]);
        }

        $notifications = [
            'message' => 'Paket berhasil ditambahkan ke keranjang',
            'alert-type' => 'success'
        ];

        return redirect()->route('paket.index')->with($notifications);
    }   


    public function update(Request $request, Keranjang $keranjang)
    {
        $request->validate([
            'kuantitas' => 'required|integer|min:0',
        ]);

        // Ambil data paket beserta kategorinya untuk mendapatkan batas maksimum
        $paket = Paket::with('kategori')->findOrFail($keranjang->paket_id);
        $kategoriNama = $paket->kategori->nama_kategori;

        $batas = [
            'Catering' => ['min' => 50, 'max' => 2000],
            'Salon' => ['min' => 1, 'max' => 1],
            'Musik' => ['min' => 1, 'max' => 1],
            'Dekorasi' => ['min' => 1, 'max' => 1],
            'Gedung' => ['min' => 1, 'max' => 1],
            'Dokumentasi' => ['min' => 1, 'max' => 1],
            'Lainnya' => ['min' => 1, 'max' => 1],
        ];

        $maxQty = $batas[$kategoriNama]['max'] ?? 1;

        // Validasi agar kuantitas tidak melebihi batas maksimum
        if ($request->kuantitas > $maxQty) {
            return back()->with([
                'message' => "Kuantitas paket {$paket->nama_paket} tidak bisa melebihi batas maksimum ({$maxQty}).",
                'alert-type' => 'error'
            ]);
        }


        if ($request->kuantitas == 0) {
            $keranjang->delete();
        } else {
            $keranjang->update(['kuantitas' => $request->kuantitas]);
        }

        return redirect()->route('keranjang.index');
    }

    public function checkout()
    {
        // Cek apakah ada paket gedung di keranjang
        $hasGedung = Keranjang::where('user_id', Auth::id())
            ->with('paket.kategori')
            ->get()
            ->contains(function ($item) {
                return $item->paket->kategori->nama_kategori === 'Gedung';
            });

        if (!$hasGedung) {
            return redirect()->back()->with([
                'message' => 'Silakan pilih minimal satu paket gedung',
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('checkout.index');
    }

    public function destroy(Keranjang $keranjang)
    {
        $keranjang->delete();
        
        return redirect()->route('keranjang.index')
             ->layout('layouts.admin.master');
    }
}