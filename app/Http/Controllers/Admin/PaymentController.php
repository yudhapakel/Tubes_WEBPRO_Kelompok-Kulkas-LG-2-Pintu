<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\NotificationHelper;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        
        $payments = Payment::with(['user', 'invoice'])
            ->where('status', $status)
            ->latest()
            ->paginate(15);
        
        return view('admin.payments.index', compact('payments', 'status'));
    }

    public function show($id)
    {
        $payment = Payment::with(['user', 'invoice'])->findOrFail($id);
        
        return view('admin.payments.show', compact('payment'));
    }

    public function verify($id)
    {
        DB::beginTransaction();
        try {
            $payment = Payment::with('invoice')->findOrFail($id);

            // Update payment status
            $payment->update([
                'status' => 'verified',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

            // Update invoice status - direct to PAID (full payment only)
            $invoice = $payment->invoice;
            $invoice->update(['status' => 'paid']);

            // Notify client
            NotificationHelper::paymentVerified(
                $payment->user_id,
                $invoice->id,
                $invoice->invoice_number
            );

            DB::commit();

            return redirect()->route('admin.payments.index')
                ->with('success', 'Payment verified! Invoice marked as PAID.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal verifikasi: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string'
        ]);

        $payment = Payment::findOrFail($id);
        
        $payment->update([
            'status' => 'rejected',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        // Notify client
        NotificationHelper::paymentRejected(
            $payment->user_id,
            $payment->id,
            $payment->invoice->invoice_number ?? 'N/A',
            $validated['rejection_reason']
        );

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment ditolak. Klien akan diberitahu.');
    }
}
