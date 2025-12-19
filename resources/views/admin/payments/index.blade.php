@extends('layouts.admin')

@section('title', 'Payment Verification')

@section('content')
<div class="page-header">
    <h1 class="page-title">Payment Verification</h1>
    <p class="page-subtitle">Verify client payment proofs</p>
</div>

<div class="data-table">
    <div class="table-header">
        <h3 class="table-title">Payments</h3>
        <div class="table-filters">
            <a href="{{ route('admin.payments.index') }}?status=pending" class="filter-tab {{ $status === 'pending' ? 'active' : '' }}">Pending</a>
            <a href="{{ route('admin.payments.index') }}?status=verified" class="filter-tab {{ $status === 'verified' ? 'active' : '' }}">Verified</a>
            <a href="{{ route('admin.payments.index') }}?status=rejected" class="filter-tab {{ $status === 'rejected' ? 'active' : '' }}">Rejected</a>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Invoice #</th>
                <th>Client</th>
                <th>Payment Type</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Uploaded</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
            <tr>
                <td>
                    <a href="{{ route('admin.invoices.show', $payment->invoice_id) }}">
                        {{ $payment->invoice->invoice_number }}
                    </a>
                </td>
                <td>{{ $payment->user->name }}</td>
                <td>
                    <span class="badge info">{{ strtoupper(str_replace('_', ' ', $payment->payment_type)) }}</span>
                </td>
                <td><strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></td>
                <td><span class="badge {{ $payment->status }}">{{ $payment->status }}</span></td>
                <td>{{ $payment->created_at->format('d M Y, H:i') }}</td>
                <td>
                    @if($payment->status === 'pending')
                        <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-success btn-sm">Verify</a>
                    @else
                        <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-primary btn-sm">View</a>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 2rem; color: #999;">
                    No {{ $status }} payments found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
