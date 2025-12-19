@extends('layouts.admin')

@section('title', 'Client Requests')

@section('content')
<div class="page-header">
    <h1 class="page-title">Client Requests</h1>
    <p class="page-subtitle">Manage all client document requests</p>
</div>

<div class="data-table">
    <div class="table-header">
        <h3 class="table-title">All Requests</h3>
        <div class="table-filters">
            <a href="{{ route('admin.requests.index', ['status' => 'all']) }}" class="filter-tab {{ $status == 'all' ? 'active' : '' }}">All</a>
            <a href="{{ route('admin.requests.index', ['status' => 'pending']) }}" class="filter-tab {{ $status == 'pending' ? 'active' : '' }}">Pending</a>
            <a href="{{ route('admin.requests.index', ['status' => 'quotation_created']) }}" class="filter-tab {{ $status == 'quotation_created' ? 'active' : '' }}">Quotation Created</a>
            <a href="{{ route('admin.requests.index', ['status' => 'completed']) }}" class="filter-tab {{ $status == 'completed' ? 'active' : '' }}">Completed</a>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Client Name</th>
                <th>Email</th>
                <th>Service</th>
                <th>Location</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requests as $request)
            <tr>
                <td>#{{ $request->id }}</td>
                <td>{{ $request->nama }}</td>
                <td>{{ $request->user->email }}</td>
                <td>{{ $request->jasa }}</td>
                <td>{{ $request->kota }}, {{ $request->prov }}</td>
                <td><span class="badge {{ $request->status }}">{{ $request->status }}</span></td>
                <td>{{ $request->created_at->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('admin.requests.show', $request->id) }}" class="btn btn-primary btn-sm">View Details</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 2rem; color: #999;">
                    No requests found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($requests->hasPages())
    <div style="padding: 1rem;">
        {{ $requests->links() }}
    </div>
    @endif
</div>
@endsection
