@extends('layouts.admin')

@section('title', 'Buat Penawaran')

@section('content')
<div class="page-header">
    <h1 class="page-title">Buat Penawaran</h1>
    <p class="page-subtitle">Request #{{ $document->id }} - {{ $document->user->name }}</p>
</div>

<div class="two-col">
    <div class="form-card">
        <h3>Request Details</h3>
        <hr>
        <table style="width: 100%; margin-top: 1rem;">
            <tr>
                <td style="padding: 0.5rem; font-weight: 600;">Client Name:</td>
                <td style="padding: 0.5rem;">{{ $document->nama }}</td>
            </tr>
            <tr>
                <td style="padding: 0.5rem; font-weight: 600;">Service:</td>
                <td style="padding: 0.5rem;">{{ $document->jasa }}</td>
            </tr>
            <tr>
                <td style="padding: 0.5rem; font-weight: 600;">Address:</td>
                <td style="padding: 0.5rem;">{{ $document->alamat }}</td>
            </tr>
            <tr>
                <td style="padding: 0.5rem; font-weight: 600;">Location:</td>
                <td style="padding: 0.5rem;">{{ $document->kota }}, {{ $document->prov }}, {{ $document->negara }}</td>
            </tr>
            <tr>
                <td style="padding: 0.5rem; font-weight: 600;">Phone:</td>
                <td style="padding: 0.5rem;">{{ $document->telepon }}</td>
            </tr>
            <tr>
                <td style="padding: 0.5rem; font-weight: 600;">Document:</td>
                <td style="padding: 0.5rem;">
                    <a href="{{ route('document.download', $document->id) }}" target="_blank" class="btn btn-sm btn-primary">Download</a>
                </td>
            </tr>
        </table>
    </div>

    <!-- Right: Quotation Form -->
    <div class="form-card">
        <h3>Form Penawaran</h3>
        <hr>
        <form action="{{ route('admin.penawarans.store') }}" method="POST">
            @csrf
            <input type="hidden" name="document_id" value="{{ $document->id }}">

            <div class="form-group">
                <label class="form-label">Description *</label>
                <textarea name="description" class="form-control" required placeholder="Deskripsi pekerjaan..."></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Items *</label>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th style="width: 150px;">Type</th>
                            <th>Item Name</th>
                            <th style="width: 80px;">Qty</th>
                            <th style="width: 130px">Unit Price</th>
                            <th style="width: 130px;">Subtotal</th>
                            <th style="width: 60px;"></th>
                        </tr>
                    </thead>
                    <tbody id="quotation-items">
                        <!-- Items will be added dynamically by JavaScript -->
                    </tbody>
                </table>
                <button type="button" id="add-item-btn" class="btn-add-item" style="margin-top: 0.5rem;">+ Add Item</button>
            </div>

            <div class="form-group">
                <label class="form-label">Tax Percentage (%) *</label>
                <input type="number" step="0.01" min="0" max="100" name="tax_percentage" id="tax-percentage" class="form-control" value="11" required>
            </div>

            <hr>

            <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 4px;">
                <table style="width: 100%; font-size: 1.1rem;">
                    <tr>
                        <td style="padding: 0.5rem;"><strong>Subtotal:</strong></td>
                        <td style="padding: 0.5rem; text-align: right;" id="subtotal-display">Rp 0</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem;"><strong>Tax:</strong></td>
                        <td style="padding: 0.5rem; text-align: right;" id="tax-display">Rp 0</td>
                    </tr>
                    <tr style="border-top: 2px solid #ddd;">
                        <td style="padding: 0.5rem; font-size: 1.3rem;"><strong>TOTAL:</strong></td>
                        <td style="padding: 0.5rem; text-align: right; font-size: 1.3rem; color: var(--primary);" id="total-display"><strong>Rp 0</strong></td>
                    </tr>
                </table>
            </div>

            <br>
            <button type="submit" class="btn btn-success" style="width: 100%;">Buat Penawaran</button>
        </form>
    </div>
</div>
@endsection
