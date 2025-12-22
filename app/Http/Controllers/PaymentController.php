<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Document;
use App\Models\Penawaran; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index()
    {
        $projects = Document::where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('client.project_list', compact('projects'));
    }

    public function acceptPenawaran($id)
    {
        $penawaran = Penawaran::findOrFail($id);

        if ($penawaran->status == 'sent' || $penawaran->status == 'negotiating') {
            
            $penawaran->update(['status' => 'accepted']);

            return redirect()->back()->with('success', 'Harga disetujui! Harap tunggu Admin menerbitkan Invoice.');
        }

        return redirect()->back()->with('error', 'Penawaran tidak bisa disetujui saat ini.');
    }

    public function negotiatePenawaran(Request $request, $id)
    {
        $validated = $request->validate([
            'client_counter_offer' => 'required|numeric|min:0',
            'client_notes' => 'nullable|string|max:1000',
        ]);

        $penawaran = Penawaran::findOrFail($id);

        // Hanya bisa nego jika status sent atau negotiating
        if (!in_array($penawaran->status, ['sent', 'negotiating'])) {
            return redirect()->back()->with('error', 'Penawaran tidak bisa dinegosiasi saat ini.');
        }

        // Update counter offer dari klien
        $penawaran->update([
            'client_counter_offer' => $validated['client_counter_offer'],
            'client_notes' => $validated['client_notes'],
            'status' => 'negotiating',
        ]);

        return redirect()->back()->with('success', 'Counter offer berhasil dikirim! Harap tunggu respon dari Admin.');
    }

    public function pay($id)
    {
        $invoice = Invoice::findOrFail($id);
        
        if ($invoice->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('client.payment', compact('invoice'));
    }

    public function process(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        $request->validate([
            'payment_method' => 'required',
        ]);

        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_method' => $request->payment_method
        ]);

        if ($invoice->quotation && $invoice->quotation->document) {
            $invoice->quotation->document->update(['status' => 'completed']);
        }
        
        else {
            Document::where('user_id', Auth::id())
                ->where('status', 'approved_by_client') 
                ->latest()
                ->first()
                ?->update(['status' => 'paid']);
        }

        return redirect()->route('payment.receipt', $invoice->id)->with('success_payment', true);
    }

    public function receipt($id)
    {
        $invoice = Invoice::findOrFail($id);
        
        if ($invoice->user_id != Auth::id()) {
            abort(403);
        }

        return view('client.invoice', compact('invoice'));
    }
}