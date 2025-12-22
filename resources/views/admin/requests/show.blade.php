@extends('layouts.admin')

@section('title', 'Request Details')

@section('content')
<div class="page-header">
    <h1 class="page-title">Request #{{ $document->id }}</h1>
    <p class="page-subtitle">From: {{ $document->user->name }}</p>
</div>

<div class="two-col">
    <!-- Left: Request Details -->
    <div class="form-card">
        <h3>Request Information</h3>
        <hr>
        <table style="width: 100%; margin-top: 1rem;">
            <tr>
                <td style="padding: 0.8rem; font-weight: 600; width: 40%;">Client Name:</td>
                <td style="padding: 0.8rem;">{{ $document->nama }}</td>
            </tr>
            <tr>
                <td style="padding: 0.8rem; font-weight: 600;">Email:</td>
                <td style="padding: 0.8rem;">{{ $document->user->email }}</td>
            </tr>
            <tr>
                <td style="padding: 0.8rem; font-weight: 600;">Service Type:</td>
                <td style="padding: 0.8rem;"><span class="badge info">{{ $document->jasa }}</span></td>
            </tr>
            <tr>
                <td style="padding: 0.8rem; font-weight: 600;">Phone:</td>
                <td style="padding: 0.8rem;">{{ $document->telepon }}</td>
            </tr>
            <tr>
                <td style="padding: 0.8rem; font-weight: 600;">Address:</td>
                <td style="padding: 0.8rem;">{{ $document->alamat }}</td>
            </tr>
            <tr>
                <td style="padding: 0.8rem; font-weight: 600;">City:</td>
                <td style="padding: 0.8rem;">{{ $document->kota }}</td>
            </tr>
            <tr>
                <td style="padding: 0.8rem; font-weight: 600;">Province:</td>
                <td style="padding: 0.8rem;">{{ $document->prov }}</td>
            </tr>
            <tr>
                <td style="padding: 0.8rem; font-weight: 600;">Country:</td>
                <td style="padding: 0.8rem;">{{ $document->negara }}</td>
            </tr>
            <tr>
                <td style="padding: 0.8rem; font-weight: 600;">Postal Code:</td>
                <td style="padding: 0.8rem;">{{ $document->kodepos }}</td>
            </tr>
            <tr>
                <td style="padding: 0.8rem; font-weight: 600;">Status:</td>
                <td style="padding: 0.8rem;"><span class="badge {{ $document->status }}">{{ $document->status }}</span></td>
            </tr>
            <tr>
                <td style="padding: 0.8rem; font-weight: 600;">Submitted:</td>
                <td style="padding: 0.8rem;">{{ $document->created_at->format('d M Y, H:i') }}</td>
            </tr>
            <tr>
                <td style="padding: 0.8rem; font-weight: 600;">Document:</td>
                <td style="padding: 0.8rem;">
                    <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="btn btn-primary btn-sm">
                        📄 Download Document
                    </a>
                </td>
            </tr>
        </table>
    </div>

    <!-- Right: Actions -->
    <div class="form-card">
        <h3>Actions</h3>
        <hr>
        
        @if($document->penawaran)
            <div class="alert alert-success">
                 Quotation sudah dibuat untuk request ini!
            </div>
            <a href="{{ route('admin.penawarans.show', $document->penawaran->id) }}" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">
                View Quotation
            </a>
        @else
            <p style="margin-bottom: 1.5rem; color: #666;">
                Request ini belum memiliki quotation. Buat quotation untuk request ini:
            </p>
            <a href="{{ route('admin.penawarans.create', $document->id) }}" class="btn btn-success" style="width: 100%; margin-bottom: 1rem;">
                 Buat Penawaran
            </a>
        @endif

        <hr style="margin: 2rem 0;">

        <h4 style="margin-bottom: 1rem;">Update Status</h4>
        <form action="{{ route('admin.requests.status', $document->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <select name="status" class="form-control">
                    <option value="pending" {{ $document->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="reviewed" {{ $document->status == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                    <option value="quotation_created" {{ $document->status == 'quotation_created' ? 'selected' : '' }}>Quotation Created</option>
                    <option value="completed" {{ $document->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="rejected" {{ $document->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Update Status</button>
        </form>
    </div>
</div>

<div style="margin-top: 2rem;">
    <a href="{{ route('admin.requests.index') }}" class="btn btn-primary">← Back to Requests</a>
</div>
@endsection
