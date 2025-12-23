<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

        Document::create([
            'user_id' => Auth::id(),
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
        ]);

        return redirect()->back()->with('show_modal', true);
    }

    public function download($id)
    {
        $document = Document::findOrFail($id);
        
        // Check if user is owner or admin
        if (Auth::id() !== $document->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access to this document');
        }
        
        // Check if file exists
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found');
        }
        
        return Storage::disk('public')->download($document->file_path);
    }
}
