<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Penawaran;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenawaranController extends Controller
{
    public function index()
    {
        $penawarans = Penawaran::with(['user', 'document'])
            ->latest()
            ->get();

        return view('admin.penawarans.index', compact('penawarans'));
    }

    public function create($documentId)
    {
        $document = Document::with('user')->findOrFail($documentId);
        
        return view('admin.penawarans.create', compact('document'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_id' => 'required|exists:documents,id',
            'description' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|in:Labor,Material,Equipment,Services,Other',
            'items.*.name' => 'required|string',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'tax_percentage' => 'required|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            $items = collect($validated['items'])->map(function ($item) {
                $item['subtotal'] = $item['qty'] * $item['unit_price'];
                return $item;
            })->toArray();

            $subtotal = collect($items)->sum('subtotal');
            $taxAmount = ($subtotal * $validated['tax_percentage']) / 100;
            $total = $subtotal + $taxAmount;

            // Generate unique quotation number for current year
            $currentYear = date('Y');
            $latestPenawaran = Penawaran::where('quotation_number', 'like', "QOT-{$currentYear}-%")
                ->orderBy('quotation_number', 'desc')
                ->first();
            
            $number = $latestPenawaran ? intval(substr($latestPenawaran->quotation_number, -3)) + 1 : 1;
            $quotationNumber = 'QOT-' . $currentYear . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);

            $document = Document::findOrFail($validated['document_id']);

            $penawaran = Penawaran::create([
                'document_id' => $validated['document_id'],
                'user_id' => $document->user_id,
                'quotation_number' => $quotationNumber,
                'description' => $validated['description'],
                'items' => $items,
                'subtotal' => $subtotal,
                'tax_percentage' => $validated['tax_percentage'],
                'tax_amount' => $taxAmount,
                'total' => $total,
                'status' => 'pending',
            ]);

            $document->update(['status' => 'quotation_created']);

            DB::commit();

            return redirect()->route('admin.penawarans.show', $penawaran->id)
                ->with('success', 'Penawaran berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat penawaran: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $penawaran = Penawaran::with(['user', 'document', 'invoice'])->findOrFail($id);
        
        return view('admin.penawarans.show', compact('penawaran'));
    }

    public function sendToClient($id)
    {
        $penawaran = Penawaran::findOrFail($id);
        $penawaran->update(['status' => 'sent']);

        return back()->with('success', 'Penawaran berhasil dikirim ke klien!');
    }

    public function handleNegotiation(Request $request, $id)
    {
        $validated = $request->validate([
            'admin_counter_offer' => 'required|numeric|min:0',
            'admin_notes' => 'nullable|string',
        ]);

        $penawaran = Penawaran::findOrFail($id);
        
        $penawaran->update([
            'admin_counter_offer' => $validated['admin_counter_offer'],
            'admin_notes' => $validated['admin_notes'],
            'status' => 'negotiating',
        ]);

        return back()->with('success', 'Counter offer berhasil dikirim!');
    }

    public function accept($id)
    {
        $penawaran = Penawaran::findOrFail($id);
        
        $penawaran->update(['status' => 'accepted']);

        return back()->with('success', 'Penawaran diterima! Siap dikonversi ke invoice.');
    }

    public function convertToInvoice($id)
    {
        DB::beginTransaction();
        try {
            $penawaran = Penawaran::findOrFail($id);

            if ($penawaran->status !== 'accepted') {
                return back()->with('error', 'Penawaran harus diterima dulu sebelum dikonversi!');
            }

            if ($penawaran->invoice) {
                return redirect()->route('admin.invoices.show', $penawaran->invoice->id)
                    ->with('info', 'Penawaran sudah pernah dikonversi.');
            }

            $finalTotal = $penawaran->admin_counter_offer ?? $penawaran->total;

            // Generate unique invoice number for current year
            $currentYear = date('Y');
            $latestInvoice = Invoice::where('invoice_number', 'like', "INV-{$currentYear}-%")
                ->orderBy('invoice_number', 'desc')
                ->first();
            
            $number = $latestInvoice ? intval(substr($latestInvoice->invoice_number, -3)) + 1 : 1;
            $invoiceNumber = 'INV-' . $currentYear . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);

            $invoice = Invoice::create([
                'quotation_id' => $penawaran->id,
                'user_id' => $penawaran->user_id,
                'invoice_number' => $invoiceNumber,
                'items' => $penawaran->items,
                'subtotal' => $penawaran->subtotal,
                'tax_amount' => $penawaran->tax_amount,
                'total' => $finalTotal,
                'status' => 'pending',
                'issue_date' => now(),
                'due_date' => now()->addDays(30),
            ]);

            $penawaran->update(['status' => 'converted']);

            DB::commit();

            return redirect()->route('admin.invoices.show', $invoice->id)
                ->with('success', 'Invoice berhasil dibuat! User harus bayar FULL.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal konversi: ' . $e->getMessage());
        }
    }
}
