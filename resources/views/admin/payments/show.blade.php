@extends('layouts.admin')

@section('title', 'Payment Verification')

@section('content')
<div class="page-header">
    <h1 class="page-title">Verify Payment</h1>
    <p class="page-subtitle">Payment from {{ $payment->user->name }}</p>
</div>

<div class="two-col">
    <!-- Left: Payment Details -->
    <div class="form-card">
        <h3>Payment Information</h3>
        <hr>
        
        <table style="width: 100%; margin-top: 1rem;">
            <tr>
                <td style="padding: 0.8rem; font-weight: 600; width: 40%;">Invoice:</td>
                <td style="padding: 0.8rem;">
                    <a href="{{ route('admin.invoices.show', $payment->invoice_id) }}">
                        {{ $payment->invoice->invoice_number }}
                    </a>
                </td>
            </tr>
            <tr>
                <td style="padding: 0.8rem; font-weight: 600;">Client Name:</td>
                <td style="padding: 0.8rem;">{{ $payment->user->name }}</td>
            </tr>
            <tr>
                <td style="padding: 0.8rem; font-weight: 600;">Email:</td>
                <td style="padding: 0.8rem;">{{ $payment->user->email }}</td>
            </tr>
            <tr>
                <td style="padding: 0.8rem; font-weight: 600;">Payment Type:</td>
                <td style="padding: 0.8rem;">
                    <span class="badge info">FULL PAYMENT (100%)</span>
                </td>
            </tr>
            <tr>
                <td style="padding: 0.8rem; font-weight: 600;">Amount:</td>
                <td style="padding: 0.8rem;"><strong style="font-size: 1.2rem; color: var(--primary);">Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td style="padding: 0.8rem; font-weight: 600;">Status:</td>
                <td style="padding: 0.8rem;"><span class="badge {{ $payment->status }}">{{ $payment->status }}</span></td>
            </tr>
            <tr>
                <td style="padding: 0.8rem; font-weight: 600;">Upload Date:</td>
                <td style="padding: 0.8rem;">{{ $payment->created_at->format('d M Y, H:i') }}</td>
            </tr>
            @if($payment->status === 'verified')
            <tr>
                <td style="padding: 0.8rem; font-weight: 600;">Verified By:</td>
                <td style="padding: 0.8rem;">{{ $payment->verifier->name }}</td>
            </tr>
            <tr>
                <td style="padding: 0.8rem; font-weight: 600;">Verified At:</td>
                <td style="padding: 0.8rem;">{{ $payment->verified_at->format('d M Y, H:i') }}</td>
            </tr>
            @endif
            @if($payment->status === 'rejected')
            <tr>
                <td style="padding: 0.8rem; font-weight: 600;">Rejected By:</td>
                <td style="padding: 0.8rem;">{{ $payment->verifier->name }}</td>
            </tr>
            <tr>
                <td style="padding: 0.8rem; font-weight: 600;">Rejection Reason:</td>
                <td style="padding: 0.8rem;">
                    <div style="background: #f8d7da; padding: 0.8rem; border-radius: 4px; color: #721c24;">
                        {{ $payment->rejection_reason }}
                    </div>
                </td>
            </tr>
            @endif
        </table>
    </div>

    <!-- Right: Payment Proof & Actions -->
    <div class="form-card">
        <h3>Payment Proof</h3>
        <hr>
        
        <div style="text-align: center; margin: 2rem 0;">
            <img src="{{ Storage::url($payment->proof_file) }}" 
                 alt="Payment Proof" 
                 style="max-width: 100%; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); cursor: pointer;"
                 onclick="window.open('{{ Storage::url($payment->proof_file) }}', '_blank')">
            <p style="margin-top: 1rem; color: #666; font-size: 0.9rem;">
                <em>Click image to view full size</em>
            </p>
        </div>

        @if($payment->status === 'pending')
        <hr>
        <h4>Verification Actions</h4>
        
        <!-- Verify Form -->
        <form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST" style="margin-bottom: 1rem;">
            @csrf
            <button type="submit" class="btn btn-success" style="width: 100%; font-size: 1.1rem; padding: 1rem;" data-confirm="Verify payment ini?">
                ✅ Verify Payment
            </button>
        </form>

        <!-- Reject Form -->
        <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Rejection Reason *</label>
                <textarea name="rejection_reason" class="form-control" rows="3" placeholder="Alasan rejection..." required></textarea>
            </div>
            <button type="submit" class="btn btn-danger" style="width: 100%;" data-confirm="Reject payment ini?">
                ❌ Reject Payment
            </button>
        </form>
        @else
        <div class="alert {{ $payment->status === 'verified' ? 'alert-success' : 'alert-error' }}">
            @if($payment->status === 'verified')
                ✅ Payment sudah diverifikasi
            @else
                ❌ Payment ditolak
            @endif
        </div>
        @endif

        <hr style="margin: 2rem 0;">
        
        <a href="{{ route('admin.invoices.show', $payment->invoice_id) }}" class="btn btn-primary" style="width: 100%; margin-bottom: 0.5rem;">
            View Invoice
        </a>
        <a href="{{ route('admin.payments.index') }}" class="btn btn-primary" style="width: 100%;">
            ← Back to Payments
        </a>
    </div>
</div>
@endsection
