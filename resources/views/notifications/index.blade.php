@extends('layouts.main')
@section('title', 'Notifikasi')

@section('content')
<div class="container" style="margin-top: 50px; margin-bottom: 80px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h2 style="font-weight: 800; color: #2c3e50; margin-bottom: 5px;">Notifikasi</h2>
            <p style="color: #7f8c8d; margin: 0;">Pantau semua aktivitas dan update terbaru</p>
        </div>
        @if($unreadCount > 0)
        <form action="{{ route('notifications.readAll') }}" method="POST">
            @csrf
            <button type="submit" class="btn-mark-all">Tandai Semua Sudah Dibaca</button>
        </form>
        @endif
    </div>

    @if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="notifications-container">
        @forelse($notifications as $notif)
        <div class="notification-card {{ $notif->is_read ? 'read' : 'unread' }}">
            <div class="notif-icon">
                @if($notif->type == 'quotation_sent')
                    <span class="icon-circle" style="background: #3b82f6;">Q</span>
                @elseif($notif->type == 'quotation_negotiated')
                    <span class="icon-circle" style="background: #f59e0b;">N</span>
                @elseif($notif->type == 'quotation_accepted')
                    <span class="icon-circle" style="background: #10b981;">A</span>
                @elseif($notif->type == 'invoice_created')
                    <span class="icon-circle" style="background: #8b5cf6;">I</span>
                @elseif($notif->type == 'payment_uploaded')
                    <span class="icon-circle" style="background: #06b6d4;">P</span>
                @elseif($notif->type == 'payment_verified')
                    <span class="icon-circle" style="background: #10b981;">V</span>
                @elseif($notif->type == 'payment_rejected')
                    <span class="icon-circle" style="background: #ef4444;">R</span>
                @elseif($notif->type == 'admin_counter_offer')
                    <span class="icon-circle" style="background: #f59e0b;">C</span>
                @elseif($notif->type == 'new_request')
                    <span class="icon-circle" style="background: #3b82f6;">N</span>
                @else
                    <span class="icon-circle" style="background: #6366f1;">!</span>
                @endif
            </div>
            <div class="notif-content">
                <h4>{{ $notif->title }}</h4>
                <p>{{ $notif->message }}</p>
                <small>{{ $notif->created_at->diffForHumans() }}</small>
            </div>
            <div class="notif-actions">
                @if($notif->link)
                <a href="{{ $notif->link }}" class="btn-view">Lihat</a>
                @endif
                @if(!$notif->is_read)
                <form action="{{ route('notifications.read', $notif->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-mark-read">Tandai Dibaca</button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="empty-icon"><span class="icon-circle" style="background: #94a3b8; width: 64px; height: 64px; font-size: 32px;">!</span></div>
            <h3>Belum Ada Notifikasi</h3>
            <p>Notifikasi Anda akan muncul di sini</p>
        </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
    <div class="pagination-container">
        {{ $notifications->links() }}
    </div>
    @endif
</div>

<style>
    .notifications-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .notification-card {
        display: flex;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid #f1f1f1;
        transition: 0.2s;
    }

    .notification-card:hover {
        background: #f8f9fa;
    }

    .notification-card.unread {
        background: #e3f2fd;
        border-left: 4px solid #2196F3;
    }

    .notif-icon {
        font-size: 32px;
        margin-right: 20px;
        min-width: 50px;
        text-align: center;
    }

    .icon-circle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        color: white;
        font-weight: bold;
        font-size: 16px;
    }

    .notif-content {
        flex: 1;
    }

    .notif-content h4 {
        margin: 0 0 8px 0;
        color: #2c3e50;
        font-size: 16px;
    }

    .notif-content p {
        margin: 0 0 8px 0;
        color: #555;
        font-size: 14px;
    }

    .notif-content small {
        color: #999;
        font-size: 12px;
    }

    .notif-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .btn-view, .btn-mark-read, .btn-mark-all {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: 0.2s;
    }

    .btn-view {
        background: #2196F3;
        color: white;
    }

    .btn-view:hover {
        background: #1976D2;
    }

    .btn-mark-read {
        background: #e0e0e0;
        color: #555;
    }

    .btn-mark-read:hover {
        background: #d0d0d0;
    }

    .btn-mark-all {
        background: #4CAF50;
        color: white;
    }

    .btn-mark-all:hover {
        background: #45a049;
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }

    .empty-icon {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.3;
        display: flex;
        justify-content: center;
    }

    .empty-state h3 {
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #999;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid #c3e6cb;
    }

    .pagination-container {
        padding: 20px;
        text-align: center;
    }
</style>
@endsection
