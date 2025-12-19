<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class AdminRequestController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = Document::with('user')->latest();
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $requests = $query->paginate(15);
        
        return view('admin.requests.index', compact('requests', 'status'));
    }

    public function show($id)
    {
        $document = Document::with(['user', 'penawaran'])->findOrFail($id);
        
        return view('admin.requests.show', compact('document'));
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,reviewed,completed,rejected'
        ]);

        $document = Document::findOrFail($id);
        $document->update($validated);

        return back()->with('success', 'Status berhasil diupdate!');
    }
}
