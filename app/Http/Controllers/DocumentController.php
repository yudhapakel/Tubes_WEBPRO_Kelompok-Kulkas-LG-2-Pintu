<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Kita pake Query Builder dulu biar cepet

class DocumentController extends Controller
{
    // 1. Nampilin Form Upload
    public function create()
    {
        return view('client.upload'); // Nanti kita bikin view ini
    }

    // 2. Proses Simpan File

    public function store(Request $request)
    {
        // 1. Validasi SEMUA Input
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
            'jasa' => 'required',
            'kota' => 'required',
            'prov' => 'required',
            'negara' => 'required',
            'kodepos' => 'required',
            'file' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
        ]);

        // 2. Simpan File
        $path = $request->file('file')->store('documents', 'public');

        // 3. Simpan SEMUA Data ke Database
        // Kita pake DB facade biar cepet mappingnya
        \Illuminate\Support\Facades\DB::table('documents')->insert([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'jasa' => $request->jasa,
            'kota' => $request->kota,
            'prov' => $request->prov,
            'negara' => $request->negara,
            'kodepos' => $request->kodepos,
            'file_path' => $path,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('show_modal', true);
    }
}
