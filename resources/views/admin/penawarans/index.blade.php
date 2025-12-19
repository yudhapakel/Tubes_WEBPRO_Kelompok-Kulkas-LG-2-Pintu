@extends('layouts.admin')

@section('title', 'Quotations')

@section('content')
<div class="page-header">
    <h1 class="page-title">Penawaran</h1>
    <p class="page-subtitle">Kelola semua penawaran klien</p>
</div>

<div class="data-table">
    <div class="table-header">
        <h3 class="table-title">Semua Penawaran</h3>
        <div class="table-filters">
            <a href="{{ route('admin.penawarans.index') }}" class="filter-tab active">All</a>
            <a href="{{ route('admin.penawarans.index') }}?status=pending" class="filter-tab">Pending</a>
            <a href="{{ route('admin.penawarans.index') }}?status=sent" class="filter-tab">Sent</a>
            <a href="{{ route('admin.penawarans.index') }}?status=negotiating" class="filter-tab">Negotiating</a>
            <a href="{{ route('admin.penawarans.index') }}?status=accepted" class="filter-tab">Accepted</a>
            <a href="{{ route('admin.penawarans.index') }}?status=converted" class="filter-tab">Converted</a>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Quotation #</th>
                <th>Client</th>
                <th>Service</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penawarans as $penawaran)
            <tr>
                <td><strong>{{ $penawaran->quotation_number }}</strong></td>
                <td>{{ $penawaran->user->name }}</td>
                <td>{{ $penawaran->document->jasa }}</td>
                <td><strong>Rp {{ number_format($penawaran->total, 0, ',', '.') }}</strong></td>
                <td><span class="badge {{ $penawaran->status }}">{{ $penawaran->status }}</span></td>
                <td>{{ $penawaran->created_at->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('admin.penawarans.show', $penawaran->id) }}" class="btn btn-primary btn-sm">View Details</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 2rem; color: #999;">
                    Tidak ada penawaran
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
