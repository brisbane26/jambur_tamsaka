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
    $request->validate([
        'nama_acara' => 'required|string|max:255',
        'tanggal_acara' => 'required|date|after_or_equal:' . now()->addDays(3)->toDateString(),
        'metode_bayar' => 'required|in:cash,transfer',
        'bukti_transfer' => 'required_if:metode_bayar,transfer|image|mimes:jpeg,png,jpg|max:2048',
        'catatan' => 'nullable|string|max:1000'
    ]);

    // Ambil ID gedung utama dan resepsi
    $gedungUtamaId = Paket::where('nama_paket', 'Gedung Utama')->value('id');
    $gedungResepsiId = Paket::where('nama_paket', 'Gedung Resepsi')->value('id');

    // Cek ketersediaan gedung di tanggal yang diminta
    $gedungUtamaTerpakai = DetailPesanan::whereHas('pesanan.jadwal', function($q) use ($request) {
            $q->where('tanggal', $request->tanggal_acara);
        })
        ->where('paket_id', $gedungUtamaId)
        ->exists();

    $gedungResepsiTerpakai = DetailPesanan::whereHas('pesanan.jadwal', function($q) use ($request) {
            $q->where('tanggal', $request->tanggal_acara);
        })
        ->where('paket_id', $gedungResepsiId)
        ->exists();

    // Cek gedung yang dipesan di keranjang
    $keranjangGedung = Keranjang::where('user_id', auth()->id())
        ->whereHas('paket.kategori', function($q) {
            $q->where('nama_kategori', 'Gedung');
        })
        ->pluck('paket_id')
        ->toArray();

    $pesanGedungUtama = in_array($gedungUtamaId, $keranjangGedung);
    $pesanGedungResepsi = in_array($gedungResepsiId, $keranjangGedung);

    // Validasi ketersediaan
    if ($gedungUtamaTerpakai && $gedungResepsiTerpakai) {
        if ($pesanGedungUtama || $pesanGedungResepsi) {
            return back()->withErrors([
                'tanggal_acara' => 'Gedung utama dan gedung resepsi sudah dipesan pada tanggal yang sama'
            ]);
        }
    } elseif ($gedungUtamaTerpakai && $pesanGedungUtama) {
        return back()->withErrors([
            'tanggal_acara' => 'Gedung utama sudah dipesan pada tanggal yang sama'
        ]);
    } elseif ($gedungResepsiTerpakai && $pesanGedungResepsi) {
        return back()->withErrors([
            'tanggal_acara' => 'Gedung resepsi sudah dipesan pada tanggal yang sama'
        ]);
    }

    // Validasi minimal 1 gedung dipesan
    if (!$pesanGedungUtama && !$pesanGedungResepsi) {
        return back()->withErrors([
            'tanggal_acara' => 'Anda harus memesan minimal 1 gedung'
        ]);
    }

    // Proses checkout
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
        'status' => 'menunggu',
        'bukti_transaksi' => $buktiPath,
    ]);

    Pembayaran::create([
        'pesanan_id' => $pesanan->id,
        'metode_bayar' => $request->metode_bayar,
        'status' => $request->metode_bayar == 'cash' ? 'Pending' : 'Pending',
    ]);

    foreach (Keranjang::where('user_id', Auth::id())->get() as $keranjang) {
        DetailPesanan::create([
            'pesanan_id' => $pesanan->id,
            'paket_id' => $keranjang->paket_id,
            'kuantitas' => $keranjang->kuantitas,
            'harga' => $keranjang->paket->harga_jual,
            'catatan' => Auth::user()->hasRole('admin') ? $request->catatan : null
        ]);
    }

    Keranjang::where('user_id', Auth::id())->delete();

    return redirect()->route('pesanan.index')->with([
        'message' => 'Pesanan berhasil dibuat',
        'alert-type' => 'success'
    ]);
}
    public function store(Request $request)
{
    $request->validate([
        'paket_id' => 'required|exists:pakets,id',
    ]);

    // Cari keranjang yang sudah ada
    $keranjang = Keranjang::where([
        'user_id' => Auth::id(),
        'paket_id' => $request->paket_id,
    ])->first();

    // Jika ada, tambah kuantitas
    if ($keranjang) {
        $keranjang->increment('kuantitas');
    } else {
        // Jika tidak, buat baru dengan kuantitas 1
        Keranjang::create([
            'user_id' => Auth::id(),
            'paket_id' => $request->paket_id,
            'kuantitas' => 1,
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
