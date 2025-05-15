<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Jadwal::whereDate('tanggal', '>=', now())
                ->get(['id', 'nama_acara as title', 'tanggal as start', 'user_id', 'status']);
            return response()->json($data);
        }

        return view('jadwal.index');
    }

    public function ajax(Request $request)
    {
        switch ($request->type) {
            case 'add':
                $tanggalSudahDipesan = Jadwal::where('tanggal', $request->tanggal)
                    ->where('status', 'dipesan')
                    ->exists();

                if (!$tanggalSudahDipesan) {
                    $event = Jadwal::create([
                        'tanggal' => $request->tanggal,
                        'nama_acara' => $request->nama_acara,
                        'user_id' => $request->user_id,
                        'status' => 'dipesan',
                    ]);

                    return response()->json([
                        'id' => $event->id,
                        'title' => $event->nama_acara,
                        'start' => $event->tanggal,
                        'user_id' => $event->user_id,
                        'status' => $event->status,
                    ]);
                } else {
                    return response()->json(['error' => 'Tanggal ini sudah dipesan.'], 400);
                }
                break;

            case 'update':
                $event = Jadwal::find($request->id);

                if ($event) {
                    if (auth()->user()->hasRole('admin') || auth()->user()->id == $event->user_id) {
                        $event->update(['nama_acara' => $request->nama_acara]);
                        return response()->json($event);
                    } else {
                        return response()->json(['error' => 'Anda tidak memiliki izin untuk mengedit acara ini.'], 403);
                    }
                }
                return response()->json(['error' => 'Acara tidak ditemukan.'], 404);
                break;

            case 'delete':
                $event = Jadwal::find($request->id);

                if ($event) {
                    if (auth()->user()->hasRole('admin') || auth()->user()->id == $event->user_id) {
                        $event->update(['status' => 'tersedia']);
                        $event->delete();
                        return response()->json($event);
                    } else {
                        return response()->json(['error' => 'Anda tidak memiliki izin untuk menghapus acara ini.'], 403);
                    }
                }
                return response()->json(['error' => 'Acara tidak ditemukan.'], 404);
                break;
        }
    }
}
