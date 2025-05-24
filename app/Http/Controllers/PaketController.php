<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paket;
use App\Models\Kategori;

class PaketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $query = Paket::with('kategori'); // eager loading kategori

    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where('nama_paket', 'like', "%{$search}%");
        // bisa juga cari di deskripsi, misal:
        // ->orWhere('deskripsi', 'like', "%{$search}%");
    }

    $paket = $query->get();

    $kategori = Kategori::all();
    return view('paket.index', compact('paket', 'kategori'));
}


public function dashboard(Request $request)
{
    $query = Paket::with('kategori');

    if ($request->has('kategori') && $request->kategori != '') {
        $query->where('kategori_id', $request->kategori);
    }

    $pakets = $query->get();
    $kategoris = Kategori::all();

    return view('welcome', compact('pakets', 'kategoris'));
}



    public function create()
    {
        $kategori = Kategori::all();
        return view('paket.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'deskripsi' => 'required|string|max:255',
            'modal' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2097152', // 2MB Max
        ]);

        // Handle file upload
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('uploads/gambar', 'public');
        }

        // Simpan data ke database
        Paket::create([
            'nama_paket' => $request->nama_paket,
            'kategori_id' => $request->kategori_id,
            'deskripsi' => $request->deskripsi,
            'modal' => $request->modal,
            'harga_jual' => $request->harga_jual,
            'gambar' => $gambarPath,
        ]);

        $notifications = [
            'message' => 'Data Paket Berhasil Ditambahkan',
            'alert-type' => 'success'
        ];

        return redirect()->route('paket.index')->with($notifications);
    }

    public function edit($id)
    {
        $paket = Paket::findOrFail($id);
        $kategori = Kategori::all();
        return view('paket.edit', compact('paket', 'kategori'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'deskripsi' => 'required|string|max:255',
            'modal' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2097152', // 2MB Max
        ]);

        // Handle file upload
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('uploads/gambar', 'public');
        }

        // Update data ke database
        $paket = Paket::findOrFail($id);
        $paket->update([
            'nama_paket' => $request->nama_paket,
            'kategori_id' => $request->kategori_id,
            'deskripsi' => $request->deskripsi,
            'modal' => $request->modal,
            'harga_jual' => $request->harga_jual,
            'gambar' => $gambarPath ? $gambarPath : $paket->gambar,
        ]);

        $notifications = [
            'message' => 'Data Paket Berhasil Diperbarui',
            'alert-type' => 'success'
        ];

        return redirect()->route('paket.index')->with($notifications);
    }

    public function destroy($id)
    {
        $paket = Paket::findOrFail($id);
        $paket->delete();

        $notifications = [
            'message' => 'Data Paket Berhasil Dihapus',
            'alert-type' => 'success'
        ];

        return redirect()->route('paket.index')->with($notifications);
    }

    public function search(Request $request)
{
    $query = $request->get('q', '');
    $result = Paket::where('nama_paket', 'like', "%{$query}%")
                   ->select('id', 'nama_paket')
                   ->limit(10)
                   ->get();

    return response()->json($result);
}

}
