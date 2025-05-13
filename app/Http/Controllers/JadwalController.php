<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index(Request $request)
    {
  
        if($request->ajax()) {
       
             $data = Jadwal::whereDate('tanggal', '>=', $request->tanggal)
                       ->whereDate('user_id',   '<=', $request->user_id)
                       ->get(['id', 'nama_acara', 'tanggal', 'user_id']);
  
             return response()->json($data);
        }
  
        return view('jadwal.index');
    }
 
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function ajax(Request $request)
    {
 
        switch ($request->type) {
           case 'add':
              $event = Jadwal::create([
                  'tanggal' => $request->tanggal,
                  'nama_acara' => $request->nama_acara,
                  'user_id' => $request->user_id,
              ]);
 
              return response()->json($event);
             break;
  
           case 'update':
              $event = Jadwal::find($request->id)->update([
                  'nama_acara' => $request->nama_acara,
                  'tanggal' => $request->tanggal,
                  'user_id' => $request->user_id,
              ]);
 
              return response()->json($event);
             break;
  
           case 'delete':
              $event = Jadwal::find($request->id)->delete();
  
              return response()->json($event);
             break;
             
           default:
             # code...
             break;
        }
    }
}