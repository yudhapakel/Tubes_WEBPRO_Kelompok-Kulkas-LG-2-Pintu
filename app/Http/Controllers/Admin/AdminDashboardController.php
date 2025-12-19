<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Penawaran;
use App\Models\Invoice;
use App\Models\Payment;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $stats = [
            'pending_requests' => Document::where('status', 'pending')->count(),
            'active_quotations' => Penawaran::whereIn('status', ['pending', 'sent', 'negotiating'])->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'total_revenue' => Invoice::where('status', 'paid')->sum('total'),
        ];

        // Get recent activities
        $recent_requests = Document::with('user')
            ->latest()
            ->take(10)
            ->get();

        $recent_payments = Payment::with(['user', 'invoice'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_requests', 'recent_payments'));
    }
}
