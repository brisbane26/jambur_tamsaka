<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Hanya tampilkan jadwal yang terkait dengan pesanan disetujui
            $data = Jadwal::whereHas('pesanan', function($query) {
                    $query->where('status', 'disetujui');
                })
                ->whereDate('tanggal', '>=', now())
                ->get(['id', 'nama_acara as title', 'tanggal as start', 'user_id']);
            
            return response()->json($data);
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