@extends('layouts.admin')

@section('title', 'Detail Penawaran')

@section('content')
<div class="page-header">
    <h1 class="page-title">{{ $penawaran->quotation_number }}</h1>
    <p class="page-subtitle">Client: {{ $penawaran->user->name }} | Status: <span class="badge {{ $penawaran->status }}">{{ $penawaran->status }}</span></p>
</div>

<div class="two-col">
    <!-- Left: Quotation Details -->
    <div class="form-card">
        <h3>Informasi Penawaran</h3>
        <hr>
        
        <table style="width: 100%; margin-top: 1rem;">
            <tr>
                <td style="padding: 0.5rem; font-weight: 600; width: 40%;">Nomor Penawaran:</td>
                <td style="padding: 0.5rem;">{{ $penawaran->quotation_number }}</td>
            </tr>
            <tr>
                <td style="padding: 0.5rem; font-weight: 600;">Client Name:</td>
                <td style="padding: 0.5rem;">{{ $penawaran->user->name }}</td>
            </tr>
            <tr>
                <td style="padding: 0.5rem; font-weight: 600;">Email:</td>
                <td style="padding: 0.5rem;">{{ $penawaran->user->email }}</td>
            </tr>
            <tr>
                <td style="padding: 0.5rem; font-weight: 600;">Service:</td>
                <td style="padding: 0.5rem;">{{ $penawaran->document->jasa }}</td>
            </tr>
            <tr>
                <td style="padding: 0.5rem; font-weight: 600;">Status:</td>
                <td style="padding: 0.5rem;"><span class="badge {{ $penawaran->status }}">{{ $penawaran->status }}</span></td>
            </tr>
            <tr>
                <td style="padding: 0.5rem; font-weight: 600;">Created:</td>
                <td style="padding: 0.5rem;">{{ $penawaran->created_at->format('d M Y, H:i') }}</td>
            </tr>
        </table>

        <h4 style="margin-top: 2rem;">Description</h4>
        <p style="padding: 1rem; background: #f8f9fa; border-radius: 4px;">{{ $penawaran->description }}</p>

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
                @foreach($penawaran->items as $item)
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
                    <td style="padding: 0.3rem; text-align: right;">Rp {{ number_format($penawaran->subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding: 0.3rem;"><strong>Tax ({{ $penawaran->tax_percentage }}%):</strong></td>
                    <td style="padding: 0.3rem; text-align: right;">Rp {{ number_format($penawaran->tax_amount, 0, ',', '.') }}</td>
                </tr>
                <tr style="border-top: 2px solid #ddd;">
                    <td style="padding: 0.5rem; font-size: 1.2rem;"><strong>TOTAL:</strong></td>
                    <td style="padding: 0.5rem; text-align: right; font-size: 1.2rem; color: var(--primary);"><strong>Rp {{ number_format($penawaran->total, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>

        @if($penawaran->client_counter_offer || $penawaran->admin_counter_offer)
        <h4 style="margin-top: 2rem;">Negotiation History</h4>
        <div style="background: #fff3cd; padding: 1rem; border-radius: 4px; margin-top: 1rem;">
            @if($penawaran->client_counter_offer)
            <p><strong>Client Counter Offer:</strong> Rp {{ number_format($penawaran->client_counter_offer, 0, ',', '.') }}</p>
            @if($penawaran->client_notes)
            <p style="margin-top: 0.5rem;"><em>"{{ $penawaran->client_notes }}"</em></p>
            @endif
            @endif

            @if($penawaran->admin_counter_offer)
            <p style="margin-top: 1rem;"><strong>Admin Counter Offer:</strong> Rp {{ number_format($penawaran->admin_counter_offer, 0, ',', '.') }}</p>
            @if($penawaran->admin_notes)
            <p style="margin-top: 0.5rem;"><em>"{{ $penawaran->admin_notes }}"</em></p>
            @endif
            @endif
        </div>
        @endif
    </div>

    <!-- Right: Actions -->
    <div class="form-card">
        <h3>Actions</h3>
        <hr>

        @if($penawaran->status === 'pending')
            <form action="{{ route('admin.penawarans.send', $penawaran->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">
                    Kirim Penawaran ke Client
                </button>
            </form>
        @endif

        @if($penawaran->status === 'sent' || $penawaran->status === 'negotiating')
            <div class="alert alert-success" style="margin-bottom: 1rem;">
                Penawaran sudah dikirim ke client. Menunggu response...
            </div>

            <h4>Send Counter Offer</h4>
            <form action="{{ route('admin.penawarans.negotiate', $penawaran->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Counter Offer Amount (Rp)</label>
                    <input type="number" step="0.01" name="admin_counter_offer" class="form-control" placeholder="8000000" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Notes (Optional)</label>
                    <textarea name="admin_notes" class="form-control" rows="3" placeholder="Penjelasan counter offer..."></textarea>
                </div>
                <button type="submit" class="btn btn-warning" style="width: 100%;">Send Counter Offer</button>
            </form>

            <hr style="margin: 1.5rem 0;">

            <form action="{{ route('admin.penawarans.accept', $penawaran->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success" style="width: 100%;" data-confirm="Terima penawaran dengan harga final?">
                    Accept Current Offer
                </button>
            </form>
        @endif

        @if($penawaran->status === 'accepted')
            <div class="alert alert-success" style="margin-bottom: 1rem;">
                Penawaran telah diterima! Siap dikonversi ke invoice.
            </div>

            @if($penawaran->invoice)
                <div class="alert alert-success">
                    Invoice sudah dibuat!
                </div>
                <a href="{{ route('admin.invoices.show', $penawaran->invoice->id) }}" class="btn btn-primary" style="width: 100%;">
                    View Invoice
                </a>
            @else
                <form action="{{ route('admin.penawarans.convert', $penawaran->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success" style="width: 100%; font-size: 1.1rem; padding: 1rem;" data-confirm="Convert penawaran ini menjadi invoice?">
                        Convert to Invoice
                    </button>
                </form>

                <div style="margin-top: 1.5rem; padding: 1rem; background: #e7f3ff; border-radius: 4px; font-size: 0.9rem;">
                    <strong>Payment Policy:</strong>
                    <p style="margin-top: 0.5rem;">
                        User harus bayar <strong>FULL PAYMENT</strong> (100%)
                    </p>
                    <p style="margin-top: 0.5rem; font-size: 1.2rem; color: var(--primary);">
                        <strong>Total: Rp {{ number_format($penawaran->admin_counter_offer ?? $penawaran->total, 0, ',', '.') }}</strong>
                    </p>
                </div>
            @endif
        @endif

        @if($penawaran->status === 'converted')
            <div class="alert alert-success">
                Sudah dikonversi ke invoice
            </div>
            <a href="{{ route('admin.invoices.show', $penawaran->invoice->id) }}" class="btn btn-primary" style="width: 100%;">
                View Invoice
            </a>
        @endif

        @if($penawaran->status === 'rejected')
            <div class="alert alert-error">
                Penawaran ditolak oleh client
            </div>
        @endif

        <hr style="margin: 2rem 0;">
        <a href="{{ route('admin.penawarans.index') }}" class="btn btn-primary" style="width: 100%;">← Kembali ke Penawaran</a>
    </div>
</div>
@endsection

