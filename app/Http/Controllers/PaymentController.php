<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Document; 
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

    public function approveOffer($id)
    {
        $document = Document::findOrFail($id);

        if ($document->status == 'offered') {
            
            $document->update(['status' => 'approved_by_client']);

            $invoice = Invoice::create([
                'user_id' => Auth::id(),
                'invoice_code' => 'INV-' . strtoupper(Str::random(10)),
                'service_name' => 'Proyek: ' . ($document->jasa ?? 'Jasa Custom'), 
                'amount' => $document->director_offer, // Harga deal dari direktur
                'status' => 'unpaid',
                'due_date' => now()->addDays(7),
            ]);

            return redirect()->route('payment.pay', $invoice->id)
                             ->with('success', 'Harga disetujui! Silahkan lakukan pembayaran.');
        }

        return redirect()->back()->with('error', 'Dokumen belum bisa disetujui.');
    }

    public function pay($id)
    {
        $invoice = Invoice::findOrFail($id);
        
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

        Document::where('user_id', Auth::id())
                ->where('status', 'approved_by_client')
                ->latest()
                ->first()
                ?->update(['status' => 'paid']);

        return redirect()->route('payment.receipt', $invoice->id)->with('success_payment', true);
    }

    public function receipt($id)
    {
        $invoice = Invoice::findOrFail($id);
        return view('client.invoice', compact('invoice'));
    }
}