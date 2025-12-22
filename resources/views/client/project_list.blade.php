@extends('layouts.main')
@section('title', 'Daftar Proyek')

@section('content')
<div class="container" style="margin-top: 50px; margin-bottom: 80px;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h2 style="font-weight: 800; color: #2c3e50; margin-bottom: 5px;">Proyek Saya</h2>
            <p style="color: #7f8c8d; margin: 0;">Pantau status pengajuan dan pembayaran proyek Anda di sini.</p>
        </div>
        <a href="{{ route('upload.create') }}" class="btn-new-project">
            + Ajukan Proyek Baru
        </a>
    </div>

    @if(session('success'))
    <div class="alert-box success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert-box error">⚠️ {{ session('error') }}</div>
    @endif

    <div class="table-card">
        <table class="styled-table">
            <thead>
                <tr>
                    <th style="width: 25%;">Jasa / Proyek</th>
                    <th style="width: 20%;">Budget Anda</th>
                    <th style="width: 20%;">Penawaran Direktur</th>
                    <th style="width: 15%;">Status</th>
                    <th style="width: 20%; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $p)
                <tr>
                    <td>
                        <div style="font-weight: bold; color: #34495e;">{{ $p->jasa }}</div>
                        <div style="font-size: 12px; color: #95a5a6;">
                            {{ $p->created_at->format('d M Y') }}
                        </div>
                    </td>

                    <td style="color: #7f8c8d;">
                        Rp {{ number_format($p->client_budget ?? 0, 0, ',', '.') }}
                    </td>

                    <td>
                        @if($p->penawaran && in_array($p->penawaran->status, ['sent', 'negotiating', 'accepted', 'converted']))
                        <span style="color: #27ae60; font-weight: bold; font-size: 16px;">
                            Rp {{ number_format($p->penawaran->admin_counter_offer ?? $p->penawaran->total, 0, ',', '.') }}
                        </span>
                        @if($p->penawaran->status == 'negotiating' && $p->penawaran->admin_counter_offer)
                        <div style="font-size: 11px; color: #e67e22; margin-top: 3px;">
                            <strong>Balasan Admin</strong>
                        </div>
                        @endif
                        @else
                        <span style="color: #bdc3c7; font-style: italic;">Belum ada penawaran</span>
                        @endif
                    </td>

                    <td>
                        @if(!$p->penawaran)
                        <span class="badge badge-warning">⏳ Menunggu Review</span>

                        @elseif($p->penawaran->status == 'sent')
                        <span class="badge badge-info">📢 Ada Penawaran Baru!</span>

                        @elseif($p->penawaran->status == 'negotiating')
                        <span class="badge badge-warning">💬 Sedang Negosiasi</span>

                        @elseif($p->penawaran->status == 'accepted')
                        <span class="badge badge-primary">✅ Menunggu Invoice Admin</span>

                        @elseif($p->penawaran->status == 'converted')
                        @php
                        $invoice = \App\Models\Invoice::where('quotation_id', $p->penawaran->id)->first();
                        @endphp

                        @if($invoice && $invoice->status == 'paid')
                        <span class="badge badge-success">🎉 Lunas / Proyek Jalan</span>
                        @else
                        <span class="badge badge-danger">💳 Belum Dibayar</span>
                        @endif
                        @endif
                    </td>

                    <td style="text-align: center;">
                        @if($p->penawaran && in_array($p->penawaran->status, ['sent', 'negotiating']))
                        <form action="{{ route('client.penawaran.accept', $p->penawaran->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn-action btn-accept" onclick="return confirm('Setuju dengan harga ini?')">
                                Setuju Harga
                            </button>
                        </form>
                        <button class="btn-action btn-negotiate" onclick="openNegotiateModal({{ $p->penawaran->id }}, {{ $p->penawaran->admin_counter_offer ?? $p->penawaran->total }})">
                            Nego Harga
                        </button>

                        @elseif($p->penawaran && $p->penawaran->status == 'converted')
                        @php
                        $invoice = \App\Models\Invoice::where('quotation_id', $p->penawaran->id)->first();
                        @endphp
                        @if($invoice && $invoice->status == 'pending')
                        <a href="{{ route('payment.pay', $invoice->id) }}" class="btn-action btn-pay">
                            Bayar Sekarang
                        </a>
                        @elseif($invoice && $invoice->status == 'paid')
                        <a href="{{ route('payment.receipt', $invoice->id) }}" class="btn-action btn-view">
                            Lihat Invoice
                        </a>
                        @endif
                        @else
                        <span style="color: #ccc;">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Belum ada proyek.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Negosiasi -->
    <div id="negotiateModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeNegotiateModal()">&times;</span>
            <h3 style="margin-top: 0; color: #2c3e50;">💬 Negosiasi Harga</h3>
            <form id="negotiateForm" method="POST">
                @csrf
                <div class="form-group">
                    <label>Harga Penawaran Admin:</label>
                    <input type="text" id="admin_price" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label>Counter Offer Anda: <span style="color: red;">*</span></label>
                    <input type="number" name="client_counter_offer" class="form-control" required min="0" step="1000" placeholder="Masukkan harga yang Anda tawarkan">
                </div>
                <div class="form-group">
                    <label>Catatan / Alasan (Opsional):</label>
                    <textarea name="client_notes" class="form-control" rows="4" placeholder="Jelaskan alasan negosiasi Anda..."></textarea>
                </div>
                <div style="text-align: right; margin-top: 20px;">
                    <button type="button" class="btn-cancel" onclick="closeNegotiateModal()">Batal</button>
                    <button type="submit" class="btn-submit">Kirim Counter Offer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .table-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        border: 1px solid #eee;
    }

    /* Table Style */
    .styled-table {
        width: 100%;
        border-collapse: collapse;
    }

    .styled-table th {
        background-color: #2c3e50;
        color: white;
        padding: 15px;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .styled-table td {
        padding: 15px;
        border-bottom: 1px solid #f1f1f1;
        font-size: 14px;
        vertical-align: middle;
    }

    .styled-table tr:hover {
        background-color: #f8f9fa;
    }

    .styled-table tr:last-child td {
        border-bottom: none;
    }

    /* Badges */
    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: bold;
        display: inline-block;
    }

    .badge-warning {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
    }

    .badge-info {
        background: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    .badge-primary {
        background: #cce5ff;
        color: #004085;
        border: 1px solid #b8daff;
    }

    .badge-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .badge-danger {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* Buttons */
    .btn-new-project {
        background: #2980b9;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        box-shadow: 0 4px 6px rgba(41, 128, 185, 0.2);
        transition: 0.3s;
    }

    .btn-new-project:hover {
        background: #3498db;
        transform: translateY(-2px);
    }

    .btn-action {
        padding: 8px 15px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: bold;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: 0.2s;
    }

    .btn-disabled {
        background: #eee;
        color: #aaa;
        cursor: not-allowed;
    }

    .btn-accept {
        background: #27ae60;
        color: white;
    }

    .btn-accept:hover {
        background: #219150;
    }

    .btn-reject {
        background: #e74c3c;
        color: white;
    }

    .btn-pay {
        background: #f39c12;
        color: white;
        animation: pulse 2s infinite;
    }

    .btn-view {
        background: #34495e;
        color: white;
    }

    .btn-negotiate {
        background: #9b59b6;
        color: white;
        margin-left: 5px;
    }

    .btn-negotiate:hover {
        background: #8e44ad;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 30px;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover {
        color: #000;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #34495e;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: #3498db;
    }

    .btn-cancel {
        background: #95a5a6;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        margin-right: 10px;
    }

    .btn-submit {
        background: #27ae60;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
    }

    .btn-submit:hover {
        background: #229954;
    }

    /* Alert Box */
    .alert-box {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #34d399;
    }

    .error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #f87171;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(243, 156, 18, 0.4);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(243, 156, 18, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(243, 156, 18, 0);
        }
    }
</style>

<script>
    function openNegotiateModal(penawaranId, adminPrice) {
        document.getElementById('negotiateModal').style.display = 'block';
        document.getElementById('admin_price').value = 'Rp ' + new Intl.NumberFormat('id-ID').format(adminPrice);
        document.getElementById('negotiateForm').action = `/penawaran/${penawaranId}/negotiate`;
    }

    function closeNegotiateModal() {
        document.getElementById('negotiateModal').style.display = 'none';
        document.getElementById('negotiateForm').reset();
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('negotiateModal');
        if (event.target == modal) {
            closeNegotiateModal();
        }
    }
</script>
@endsection