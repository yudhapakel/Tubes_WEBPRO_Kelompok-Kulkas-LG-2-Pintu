<?php

use App\Http\Controllers\PaymentController as ClientPaymentController; // 🔥 PENTING: Kasih nama beda biar gak bentrok sama Admin
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController; // Panggil Controller barunya
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;

// Halaman Login (Tampilan Awal)
Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::post('/register', [RegisterController::class, 'store'])->name('register.process');


Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::post('/', [LoginController::class, 'authenticate'])->name('login.process');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/upload', [DocumentController::class, 'create'])->name('upload.create');
    Route::post('/upload', [DocumentController::class, 'store'])->name('upload.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/payment', [ClientPaymentController::class, 'index'])->name('payment.index');
    
    Route::post('/penawaran/{id}/accept', [ClientPaymentController::class, 'acceptPenawaran'])->name('client.penawaran.accept');
    
    Route::get('/payment/pay/{id}', [ClientPaymentController::class, 'pay'])->name('payment.pay');
    
    Route::post('/payment/process/{id}', [ClientPaymentController::class, 'process'])->name('payment.process');
    
    Route::get('/invoice/{id}', [ClientPaymentController::class, 'receipt'])->name('payment.receipt');
});

// rute admin
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminRequestController;
use App\Http\Controllers\Admin\PenawaranController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PaymentController;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/requests', [AdminRequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/{id}', [AdminRequestController::class, 'show'])->name('requests.show');
    Route::post('/requests/{id}/status', [AdminRequestController::class, 'updateStatus'])->name('requests.status');
    Route::get('/penawarans', [PenawaranController::class, 'index'])->name('penawarans.index');
    Route::get('/penawarans/create/{documentId}', [PenawaranController::class, 'create'])->name('penawarans.create');
    Route::post('/penawarans', [PenawaranController::class, 'store'])->name('penawarans.store');
    Route::get('/penawarans/{id}', [PenawaranController::class, 'show'])->name('penawarans.show');
    Route::post('/penawarans/{id}/send', [PenawaranController::class, 'sendToClient'])->name('penawarans.send');
    Route::post('/penawarans/{id}/negotiate', [PenawaranController::class, 'handleNegotiation'])->name('penawarans.negotiate');
    Route::post('/penawarans/{id}/accept', [PenawaranController::class, 'accept'])->name('penawarans.accept');
    Route::post('/penawarans/{id}/convert', [PenawaranController::class, 'convertToInvoice'])->name('penawarans.convert');
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{id}/download', [InvoiceController::class, 'download'])->name('invoices.download');
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{id}', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{id}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
    Route::post('/payments/{id}/reject', [PaymentController::class, 'reject'])->name('payments.reject');
});

