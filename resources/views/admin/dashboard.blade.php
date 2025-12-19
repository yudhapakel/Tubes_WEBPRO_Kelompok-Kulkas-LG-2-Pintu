@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard Admin</h1>
    <p class="page-subtitle">Selamat datang, {{ Auth::user()->name }}!</p>
</div>

<div class="stats-grid">
    <div class="stat-card danger">
        <div class="stat-card-title">Pending Requests</div>
        <div class="stat-card-value">{{ $stats['pending_requests'] }}</div>
    </div>
    <div class="stat-card info">
        <div class="stat-card-title">Penawaran Aktif</div>
        <div class="stat-card-value">{{ $stats['active_quotations'] }}</div>
    </div>
    <div class="stat-card warning">
        <div class="stat-card-title">Pending Payments</div>
        <div class="stat-card-value">{{ $stats['pending_payments'] }}</div>
    </div>
    <div class="stat-card success">
        <div class="stat-card-title">Total Revenue</div>
        <div class="stat-card-value">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
    </div>
</div>

<div class="data-table">
    <div class="table-header">
        <h3 class="table-title">Recent Requests</h3>
        <a href="{{ route('admin.requests.index') }}" class="btn btn-primary btn-sm">View All</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Service</th>
                <th>Location</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recent_requests as $request)
            <tr>
                <td>#{{ $request->id }}</td>
                <td>{{ $request->user->name }}</td>
                <td>{{ $request->jasa }}</td>
                <td>{{ $request->kota }}, {{ $request->prov }}</td>
                <td><span class="badge {{ $request->status }}">{{ $request->status }}</span></td>
                <td>{{ $request->created_at->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('admin.requests.show', $request->id) }}" class="btn btn-primary btn-sm">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; color: #999;">No requests yet</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<br>
<div class="data-table">
    <div class="table-header">
        <h3 class="table-title">Payments Awaiting Verification</h3>
        <a href="{{ route('admin.payments.index') }}" class="btn btn-primary btn-sm">View All</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Client</th>
                <th>Invoice</th>
                <th>Payment Type</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recent_payments as $payment)
            <tr>
                <td>{{ $payment->user->name }}</td>
                <td>{{ $payment->invoice->invoice_number }}</td>
                <td>{{ strtoupper(str_replace('_', ' ', $payment->payment_type)) }}</td>
                <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
                <td>
                    <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-success btn-sm">Verify</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; color: #999;">No pending payments</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
