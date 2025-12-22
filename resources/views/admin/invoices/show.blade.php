@extends('layouts.admin')

@section('title', 'Invoice Details')

@section('content')
<div class="page-header">
    <h1 class="page-title">{{ $invoice->invoice_number }}</h1>
    <p class="page-subtitle">Client: {{ $invoice->user->name }} | Status: <span class="badge {{ $invoice->status }}">{{ $invoice->status }}</span></p>
</div>

<div class="two-col">
    <!-- Left: Invoice Details -->
    <div class="form-card">
        <h3>Invoice Information</h3>
        <hr>
        
        <table style="width: 100%; margin-top: 1rem;">
            <tr>
                <td style="padding: 0.5rem; font-weight: 600; width: 40%;">Invoice Number:</td>
                <td style="padding: 0.5rem;">{{ $invoice->invoice_number }}</td>
            </tr>
            <tr>
                <td style="padding: 0.5rem; font-weight: 600;">Quotation #:</td>
                <td style="padding: 0.5rem;">
                    <a href="{{ route('admin.penawarans.show', $invoice->quotation_id) }}">{{ $invoice->penawaran->quotation_number }}</a>
                </td>
            </tr>
            <tr>
                <td style="padding: 0.5rem; font-weight: 600;">Client Name:</td>
                <td style="padding: 0.5rem;">{{ $invoice->user->name }}</td>
            </tr>
            <tr>
                <td style="padding: 0.5rem; font-weight: 600;">Email:</td>
                <td style="padding: 0.5rem;">{{ $invoice->user->email }}</td>
            </tr>
            <tr>
                <td style="padding: 0.5rem; font-weight: 600;">Issue Date:</td>
                <td style="padding: 0.5rem;">{{ $invoice->issue_date->format('d M Y') }}</td>
            </tr>
            <tr>
                <td style="padding: 0.5rem; font-weight: 600;">Due Date:</td>
                <td style="padding: 0.5rem;">{{ $invoice->due_date->format('d M Y') }}</td>
            </tr>
            <tr>
                <td style="padding: 0.5rem; font-weight: 600;">Status:</td>
                <td style="padding: 0.5rem;"><span class="badge {{ $invoice->status }}">{{ $invoice->status }}</span></td>
            </tr>
        </table>

        <h4 style="margin-top: 2rem;">Items</h4>
        <table class="items-table" style="margin-top: 1rem;">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['qty'] }}</td>
                    <td>Rp {{ number_format($item['unit_price'], 0, ',', '.') }}</td>
                    <td><strong>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pricing Summary -->
        <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 4px; margin-top: 1rem;">
            <table style="width: 100%;">
                <tr>
                    <td style="padding: 0.3rem;"><strong>Subtotal:</strong></td>
                    <td style="padding: 0.3rem; text-align: right;">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding: 0.3rem;"><strong>Tax:</strong></td>
                    <td style="padding: 0.3rem; text-align: right;">Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</td>
                </tr>
                <tr style="border-top: 2px solid #ddd;">
                    <td style="padding: 0.5rem; font-size: 1.2rem;"><strong>TOTAL:</strong></td>
                    <td style="padding: 0.5rem; text-align: right; font-size: 1.2rem; color: var(--primary);"><strong>Rp {{ number_format($invoice->total, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Right: Payment Status (FULL PAYMENT) -->
    <div class="form-card">
        <h3>Payment Status</h3>
        <hr>

        <div style="background: #fff3cd; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; border-left: 4px solid #ffc107;">
            <h4 style="margin: 0 0 1rem 0; color: #856404;">FULL PAYMENT REQUIRED</h4>
            <p style="margin: 0; font-size: 1.3rem; font-weight: bold; color: var(--primary);">
                Rp {{ number_format($invoice->total, 0, ',', '.') }}
            </p>
            <p style="margin: 0.5rem 0 0 0; font-size: 0.9rem; color: #666;">
                User harus bayar 100% sekaligus
            </p>
        </div>

        @php
            $payment = $invoice->payments()->first();
        @endphp

        @if($payment)
            <div style="padding: 1.5rem; background: {{ $payment->status === 'verified' ? '#d4edda' : '#f8f9fa' }}; border-radius: 8px; border-left: 4px solid {{ $payment->status === 'verified' ? 'var(--success)' : '#ddd' }};">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <strong>Payment Status:</strong>
                    <span class="badge {{ $payment->status }}">{{ $payment->status }}</span>
                </div>

                @if($payment->status === 'verified')
                    <div style="color: #155724; margin-top: 1rem;">
                        <p><strong>PAID</strong></p>
                        <p style="font-size: 0.9rem; margin-top: 0.5rem;">
                            Verified on {{ $payment->verified_at->format('d M Y, H:i') }}<br>
                            by {{ $payment->verifier->name }}
                        </p>
                    </div>
                @elseif($payment->status === 'pending')
                    <div style="color: #856404; margin-top: 1rem;">
                        <p> <strong>Awaiting Verification</strong></p>
                        <p style="font-size: 0.9rem; margin-top: 0.5rem;">
                            Uploaded on {{ $payment->created_at->format('d M Y, H:i') }}
                        </p>
                        <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-success" style="width: 100%; margin-top: 1rem;">
                            Verify Payment
                        </a>
                    </div>
                @elseif($payment->status === 'rejected')
                    <div style="color: #721c24; margin-top: 1rem;">
                        <p> <strong>REJECTED</strong></p>
                        <p style="font-size: 0.9rem; margin-top: 0.5rem; background: #f8d7da; padding: 0.8rem; border-radius: 4px;">
                            {{ $payment->rejection_reason }}
                        </p>
                    </div>
                @endif
            </div>
        @else
            <div style="padding: 1.5rem; background: #f8f9fa; border-radius: 8px; text-align: center; color: #999;">
                <p> Payment not received yet</p>
                <p style="font-size: 0.9rem; margin-top: 0.5rem;">Waiting for client to upload payment proof</p>
            </div>
        @endif

        <hr style="margin: 2rem 0;">
        
        <a href="{{ route('admin.invoices.download', $invoice->id) }}" class="btn btn-primary" style="width: 100%; margin-bottom: 0.5rem;">
             Download PDF
        </a>
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-primary" style="width: 100%;">
            ← Back to Invoices
        </a>
    </div>
</div>
@endsection
