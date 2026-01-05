<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = Invoice::with(['user', 'penawaran'])->latest();
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $invoices = $query->paginate(15);
        
        return view('admin.invoices.index', compact('invoices', 'status'));
    }

    public function show($id)
    {
        $invoice = Invoice::with(['user', 'penawaran', 'payments'])->findOrFail($id);
        
        return view('admin.invoices.show', compact('invoice'));
    }

    public function download($id)
    {
        return back()->with('info', 'PDF download akan ditambahkan.');
    }
}
