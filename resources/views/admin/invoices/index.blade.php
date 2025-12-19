@extends('layouts.admin')

@section('title', 'Invoices')

@section('content')
<div class="page-header">
    <h1 class="page-title">Invoices</h1>
    <p class="page-subtitle">Manage all client invoices and payments</p>
</div>

<div class="data-table">
    <div class="table-header">
        <h3 class="table-title">All Invoices</h3>
        <div class="table-filters">
            <a href="{{ route('admin.invoices.index') }}" class="filter-tab active">All</a>
            <a href="{{ route('admin.invoices.index') }}?status=pending" class="filter-tab">Pending</a>
            <a href="{{ route('admin.invoices.index') }}?status=paid" class="filter-tab">Paid</a>
            <a href="{{ route('admin.invoices.index') }}?status=overdue" class="filter-tab">Overdue</a>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Invoice #</th>
                <th>Client</th>
                <th>Quotation #</th>
                <th>Total Amount</th>
                <th>Payment Status</th>
                <th>Due Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
            @php
                $payment = $invoice->payments()->first();
                $paymentStatus = $payment ? $payment->status : 'not_submitted';
            @endphp
            <tr>
                <td><strong>{{ $invoice->invoice_number }}</strong></td>
                <td>{{ $invoice->user->name }}</td>
                <td>{{ $invoice->penawaran->quotation_number }}</td>
                <td><strong>Rp {{ number_format($invoice->total, 0, ',', '.') }}</strong></td>
                <td>
                    @if($invoice->status === 'paid')
                        <span class="badge verified">PAID</span>
                    @elseif($paymentStatus === 'pending')
                        <span class="badge pending">Awaiting Verification</span>
                    @elseif($paymentStatus === 'rejected')
                        <span class="badge rejected">Rejected</span>
                    @else
                        <span class="badge" style="background: #e0e0e0; color: #666;">⏺️ Not Paid</span>
                    @endif
                </td>
                <td>{{ $invoice->due_date->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn btn-primary btn-sm">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 2rem; color: #999;">
                    No invoices found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
