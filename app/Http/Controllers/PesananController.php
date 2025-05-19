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
            ->latest()
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

        return view('pesanan.show', [
            'pesanan' => $pesanan,
            'isAdmin' => Auth::user()->hasRole('admin')
        ]);
    }

public function updateStatus(Request $request, Pesanan $pesanan)
{
    $request->validate([
        'status' => 'required|in:menunggu,disetujui,ditolak,selesai,dibatalkan'
    ]);

    $pesanan->update([
        'status' => $request->status,
        'alasan_tolak' => $request->status === 'ditolak' ? $request->alasan_tolak : null
    ]);

    // Jika status diubah ke disetujui, pastikan tidak ada konflik jadwal
    if ($request->status === 'disetujui') {
        $conflict = Pesanan::where('jadwal_id', $pesanan->jadwal_id)
            ->where('id', '!=', $pesanan->id)
            ->where('status', 'disetujui')
            ->exists();

        if ($conflict) {
            return back()->with('error', 'Sudah ada pesanan disetujui untuk tanggal ini');
        }
    }

    $notifications = [
            'message' => 'Status pesanan berhasil diperbarui',
            'alert-type' => 'success'
        ];

    return redirect()->back()->with($notifications);
}

    public function updateBukti(Request $request, Pesanan $pesanan)
    {
        // Pastikan hanya pemilik pesanan yang bisa update
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
            return back()->with('message', 'Pesanan berhasil dibatalkan');
        }
        
        // Customer hanya bisa batalkan jika status menunggu/disetujui
        if ($pesanan->user_id == $user->id && in_array($pesanan->status, ['menunggu', 'disetujui'])) {
            $pesanan->update(['status' => 'dibatalkan']);

            $notifications = [
            'message' => 'Pesanan berhasil dibatalkan',
            'alert-type' => 'success'
        ];

            return back()->with($notifications);
        }

        $notifications = [
            'message' => 'Anda tidak dapat membatalkan pesanan ini',
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
        ->latest()
        ->paginate(10); // tambahkan pagination agar lebih rapi

    return view('pesanan.history', compact('pesanans', 'statusFilter'));
}

}