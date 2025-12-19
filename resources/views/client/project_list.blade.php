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
                            Diajukan: {{ \Carbon\Carbon::parse($p->created_at)->format('d M Y') }}
                        </div>
                    </td>

                    <td style="color: #7f8c8d;">Rp {{ number_format($p->client_budget, 0, ',', '.') }}</td>
                    
                    <td>
                        @if($p->director_offer)
                            <span style="color: #27ae60; font-weight: bold; font-size: 16px;">
                                Rp {{ number_format($p->director_offer, 0, ',', '.') }}
                            </span>
                        @else
                            <span style="color: #bdc3c7; font-style: italic;">Belum ada penawaran</span>
                        @endif
                    </td>

                    <td>
                        @if($p->status == 'pending')
                            <span class="badge badge-warning">⏳ Menunggu Verifikasi</span>
                        @elseif($p->status == 'offered')
                            <span class="badge badge-info">📢 Ada Penawaran!</span>
                        @elseif($p->status == 'approved_by_client')
                            <span class="badge badge-primary">💳 Siap Bayar</span>
                        @elseif($p->status == 'paid')
                            <span class="badge badge-success">✅ Proyek Berjalan</span>
                        @elseif($p->status == 'rejected')
                            <span class="badge badge-danger">❌ Ditolak</span>
                        @endif
                    </td>

                    <td style="text-align: center;">
                        
                        @if($p->status == 'pending')
                            <button class="btn-action btn-disabled" disabled>Menunggu Admin</button>

                        @elseif($p->status == 'offered')
                            <div style="display: flex; gap: 5px; justify-content: center;">
                                <form action="{{ route('project.approve', $p->id) }}" method="POST">
                                    @csrf
                                    <button class="btn-action btn-accept" onclick="return confirm('Setuju dengan harga ini?')">
                                        Setuju & Bayar
                                    </button>
                                </form>
                                <button class="btn-action btn-reject" disabled title="Fitur Nego Segera Hadir">Nego</button>
                            </div>

                        @elseif($p->status == 'approved_by_client')
                            @php 
                                // Cari Invoice Unpaid terkait jasa ini
                                $inv = \App\Models\Invoice::where('service_name', 'LIKE', '%'.$p->jasa.'%')
                                                          ->where('status', 'unpaid')
                                                          ->latest()->first(); 
                            @endphp
                            
                            @if($inv)
                                <a href="{{ route('payment.pay', $inv->id) }}" class="btn-action btn-pay">
                                    💳 Bayar Sekarang
                                </a>
                            @else
                                <span style="font-size: 12px;">Memproses Invoice...</span>
                            @endif

                        @elseif($p->status == 'paid')
                             @php 
                                $invPaid = \App\Models\Invoice::where('service_name', 'LIKE', '%'.$p->jasa.'%')
                                                          ->latest()->first(); 
                            @endphp
                            <a href="{{ route('payment.receipt', $invPaid->id ?? 0) }}" class="btn-action btn-view">
                                📄 Lihat Invoice
                            </a>

                        @else
                            <span style="color: #ccc;">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 50px;">
                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486747.png" width="80" style="opacity: 0.5; margin-bottom: 20px;">
                        <p style="color: #95a5a6;">Belum ada pengajuan proyek.</p>
                        <a href="{{ route('upload.create') }}" style="color: #3498db; font-weight: bold; text-decoration: none;">Mulai Ajukan Sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .table-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
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
    .badge-warning { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
    .badge-info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
    .badge-primary { background: #cce5ff; color: #004085; border: 1px solid #b8daff; }
    .badge-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .badge-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

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
    .btn-new-project:hover { background: #3498db; transform: translateY(-2px); }

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
    .btn-disabled { background: #eee; color: #aaa; cursor: not-allowed; }
    .btn-accept { background: #27ae60; color: white; }
    .btn-accept:hover { background: #219150; }
    .btn-reject { background: #e74c3c; color: white; }
    .btn-pay { background: #f39c12; color: white; animation: pulse 2s infinite; }
    .btn-view { background: #34495e; color: white; }

    /* Alert Box */
    .alert-box { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; }
    .success { background: #d1fae5; color: #065f46; border: 1px solid #34d399; }
    .error { background: #fee2e2; color: #991b1b; border: 1px solid #f87171; }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(243, 156, 18, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(243, 156, 18, 0); }
        100% { box-shadow: 0 0 0 0 rgba(243, 156, 18, 0); }
    }
</style>
@endsection