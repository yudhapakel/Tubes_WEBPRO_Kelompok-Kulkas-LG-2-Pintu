<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function create()
    {
        return view('client.upload');
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
            'jasa' => 'required',
            'kota' => 'required',
            'prov' => 'required',
            'negara' => 'required',
            'kodepos' => 'required',
            'client_budget' => 'required|numeric|min:0',
            'file' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
        ]);

        $path = $request->file('file')->store('documents', 'public');

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


            'client_budget' => $request->client_budget,

            'file_path' => $path,
            'status' => 'pending', 
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('show_modal', true);
    }
}
