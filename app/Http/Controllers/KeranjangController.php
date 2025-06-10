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

        $isDateLocked = Pesanan::where('status', 'disetujui') // Hanya cari pesanan yang sudah disetujui
            ->whereHas('jadwal', function ($query) use ($request) {
                $query->where('tanggal', $request->tanggal_acara);
            })
            ->exists(); // Cukup cek apakah ada (true/false), lebih efisien

        if ($isDateLocked) {
            // Jika tanggal sudah dikunci oleh pesanan yang disetujui, langsung kembalikan error
            return back()->withErrors([
                'tanggal_acara' => 'Mohon maaf, sudah ada pesanan yang disetujui pada tanggal ini.'
            ])->withInput(); // withInput() agar data form tidak hilang
        }

        $gedungUtamaId = Paket::where('nama_paket', 'Gedung Utama')->value('id');
        $gedungResepsiId = Paket::where('nama_paket', 'Gedung Resepsi')->value('id');

        // Ambil semua paket dari keranjang pengguna
        $keranjangItems = Keranjang::where('user_id', auth()->id())->pluck('paket_id')->toArray();

        $pesanGedungUtama = in_array($gedungUtamaId, $keranjangItems);
        $pesanGedungResepsi = in_array($gedungResepsiId, $keranjangItems);

        // Validasi bahwa minimal harus ada 1 gedung di keranjang
        if (!$pesanGedungUtama && !$pesanGedungResepsi) {
            return back()->withErrors([
                // Pesan error ini bisa disesuaikan atau ditampilkan di halaman keranjang
                'keranjang' => 'Anda harus memesan minimal 1 gedung (Gedung Utama atau Gedung Resepsi) untuk melanjutkan.'
            ])->withInput();
        }

        $buktiPath = null;
        if ($request->hasFile('bukti_transfer')) {
            $buktiPath = $request->file('bukti_transfer')->store('bukti_transfer', 'public');
        }

        $jadwal = Jadwal::create([
            'tanggal' => $request->tanggal_acara,
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
            'status' => 'Pending', // Status pembayaran bisa 'Pending' atau 'Lunas' nanti
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
    
    // Tentukan kuantitas default
    $quantity = 1;
    
    // Jika kategori mengandung 'Catering', set kuantitas ke 50
    if (str_contains($paket->kategori->nama_kategori, 'Catering')) {
        $quantity = 50;
    }

    // Cari keranjang yang sudah ada
    $keranjang = Keranjang::where([
        'user_id' => Auth::id(),
        'paket_id' => $request->paket_id,
    ])->first();

    // Jika ada, tambah kuantitas
    if ($keranjang) {
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
    $hasGedung = $this->keranjangs->contains(function ($item) {
        // Debug: log isi paket dan kategori
        \Log::debug('Paket:', [
            'nama' => $item->paket->nama_paket,
            'kategori' => $item->paket->kategori->nama_kategori ?? 'null'
        ]);
        
        return $item->paket->kategori->nama_kategori === 'Gedung';
    });

    if (!$hasGedung) {
        \Log::debug('Tidak ada paket gedung');
        $this->dispatch('show-toast', [
            'message' => 'Silakan pilih minimal satu paket gedung',
            'type' => 'error'
        ]);
        return;
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
