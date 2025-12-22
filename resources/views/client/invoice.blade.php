@extends('layouts.main')
@section('title', 'Invoice #'.$invoice->invoice_code)

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .invoice-container { max-width: 900px; margin: 40px auto; display: flex; box-shadow: 0 0 20px rgba(0,0,0,0.1); background: white; font-family: sans-serif; }
    .left-section { background: #2c3e50; color: white; width: 35%; padding: 30px; position: relative; }
    .right-section { width: 65%; padding: 40px; color: #333; }
    .status-box { margin: 20px 0; padding: 10px; border-radius: 5px; text-align: center; }
    .status-paid { background: #10b981; color: white; }
    .status-unpaid { background: #ef4444; color: white; }
    .info-grid { display: grid; grid-template-columns: 1fr; gap: 15px; margin-top: 20px; }
    .info-grid div p { font-size: 12px; opacity: 0.8; margin: 0; }
    .info-grid div strong { font-size: 16px; }
    .invoice-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .invoice-table th { text-align: left; background: #f3f4f6; padding: 10px; font-size: 12px; }
    .invoice-table td { padding: 10px; border-bottom: 1px solid #eee; font-size: 14px; }
    .total { text-align: right; margin-top: 20px; font-size: 18px; border-top: 2px solid #333; padding-top: 10px; }
    #printInvoice { background: #2c3e50; color: white; border: none; padding: 10px 20px; cursor: pointer; margin-top: 20px; }
</style>

<div class="invoice-container">

    <div class="left-section">
        <h2>INVOICE</h2>

        @if($invoice->status == 'paid')
            <div class="status-box status-paid">
                <h3 style="margin:0;">LUNAS (PAID)</h3>
            </div>
        @else
            <div class="status-box status-unpaid">
                <h3 style="margin:0;">BELUM LUNAS</h3>
                <span style="font-size: 12px;">Overdue</span>
            </div>
        @endif

        <div class="info-grid">
            <div>
                <p>Total Amount</p>
                <strong>Rp {{ number_format($invoice->total, 0, ',', '.') }}</strong>
            </div>
            <div>
                <p>Status</p>
                <strong>{{ strtoupper($invoice->status) }}</strong>
            </div>
            <div>
                <p>Payment Method</p>
                <strong>{{ $invoice->payment_method ?? '-' }}</strong>
            </div>
        </div>

        <div class="info-grid">
            <div>
                <p>Due Date</p>
                <strong>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</strong>
            </div>
            <div>
                <p>Paid On</p>
                <strong>{{ $invoice->paid_at ? \Carbon\Carbon::parse($invoice->paid_at)->format('d M Y') : '-' }}</strong>
            </div>
        </div>

        <h4 style="margin-top: 30px; border-bottom: 1px solid #ffffff50; padding-bottom: 5px;">Credit Note</h4>
        <div class="credit-box">
            <strong>{{ $invoice->invoice_code }}</strong>
            <p style="font-size: 12px; opacity: 0.7;">Created on: {{ $invoice->created_at->format('d/m/Y') }}</p>
        </div>
    </div>

    <div class="right-section">
        <p class="invoice-number" style="color: #888; text-align: right;">#{{ $invoice->invoice_code }}</p>

        <h2 style="color: #2c3e50;">TAGIHAN</h2>
        <p><strong>Date:</strong> {{ now()->format('d F, Y') }}</p>

        <div class="address-grid" style="display: flex; justify-content: space-between; margin-top: 20px;">
            <div>
                <strong>Billed to:</strong>
                <p style="font-size: 14px; line-height: 1.5;">
                    {{ Auth::user()->name }}<br>
                    {{ Auth::user()->address ?? 'Alamat belum diatur' }}<br>
                    {{ Auth::user()->email }}
                </p>
            </div>
            <div style="text-align: right;">
                <strong>From:</strong>
                <p style="font-size: 14px; line-height: 1.5;">
                    CV. TRILOKA SEJAHTERA<br>
                    Jl. Telekomunikasi No. 1<br>
                    admin@triloka.com
                </p>
            </div>
        </div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items ?? [] as $item)
                <tr>
                    <td>{{ $item['name'] ?? 'Service' }}</td>
                    <td>{{ $item['qty'] ?? 1 }}</td>
                    <td>Rp {{ number_format($item['unit_price'] ?? 0, 0, ',', '.') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($item['subtotal'] ?? 0, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            <strong>Total: Rp {{ number_format($invoice->total, 0, ',', '.') }}</strong>
        </div>

        <p style="margin-top: 20px; font-size: 14px;"><strong>Note:</strong> Terima kasih telah mempercayakan proyek Anda kepada Triloka Sejahtera!</p>

        <button id="printInvoice" onclick="window.print()">🖨️ Cetak / Download PDF</button>
        <a href="{{ url('/dashboard') }}" style="display: inline-block; margin-left: 10px; text-decoration: none; color: #555;">Kembali ke Dashboard</a>
    </div>

</div>

@if(session('success_payment'))
<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            icon: 'success',
            title: 'Pembayaran Berhasil!',
            text: 'Terima kasih, pembayaran Anda telah kami terima.',
            confirmButtonText: 'Lihat Invoice',
            confirmButtonColor: '#2c3e50',
            backdrop: `
                rgba(0,0,123,0.4)
                left top
                no-repeat
            `
        });
    });
</script>
@endif

@endsection