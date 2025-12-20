<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Document;
use App\Models\Penawaran; // ✅ JANGAN LUPA IMPORT INI
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    // 1. LIST PROJECT
    public function index()
    {
        // Ambil data dokumen user, urutkan dari yang terbaru
        $projects = Document::where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('client.project_list', compact('projects'));
    }

    // 2. CLIENT SETUJU HARGA (Pengganti approveOffer)
    public function acceptPenawaran($id)
    {
        // Cari data penawaran berdasarkan ID
        $penawaran = Penawaran::findOrFail($id);

        // Pastikan statusnya 'sent' (dikirim admin) atau 'negotiating'
        if ($penawaran->status == 'sent' || $penawaran->status == 'negotiating') {
            
            // Update status jadi accepted
            $penawaran->update(['status' => 'accepted']);

            return redirect()->back()->with('success', 'Harga disetujui! Harap tunggu Admin menerbitkan Invoice.');
        }

        return redirect()->back()->with('error', 'Penawaran tidak bisa disetujui saat ini.');
    }

    // 3. HALAMAN BAYAR
    public function pay($id)
    {
        $invoice = Invoice::findOrFail($id);
        
        // Cek kalau invoice bukan punya user yang login (Security)
        if ($invoice->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('client.payment', compact('invoice'));
    }

    // 4. PROSES BAYAR
    public function process(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        $request->validate([
            'payment_method' => 'required',
        ]);

        // Update status Invoice
        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_method' => $request->payment_method
        ]);

        // 🔥 LOGIC BARU: Update Status Dokumen jadi 'Completed/Paid' secara otomatis
        // Kita cari dokumen aslinya lewat relasi: Invoice -> Penawaran -> Document
        if ($invoice->quotation && $invoice->quotation->document) {
            $invoice->quotation->document->update(['status' => 'completed']);
        }
        
        // Cara lama lu (backup kalau relasi gagal)
        else {
            Document::where('user_id', Auth::id())
                ->where('status', 'approved_by_client') // Status lama
                ->latest()
                ->first()
                ?->update(['status' => 'paid']);
        }

        return redirect()->route('payment.receipt', $invoice->id)->with('success_payment', true);
    }

    // 5. HALAMAN RESI / BUKTI BAYAR
    public function receipt($id)
    {
        $invoice = Invoice::findOrFail($id);
        
        if ($invoice->user_id != Auth::id()) {
            abort(403);
        }

        return view('client.invoice', compact('invoice'));
    }
}