<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController; // Panggil Controller barunya
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DocumentController;

// Halaman Login (Tampilan Awal)
Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::post('/register', [RegisterController::class, 'store'])->name('register');


Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Proses Login (Nangani Form POST) -> Arahin ke LoginController fungsi 'authenticate'
Route::post('/', [LoginController::class, 'authenticate']);

Route::middleware(['auth'])->group(function () {
    // Rute buat nampilin form
    Route::get('/upload', [DocumentController::class, 'create'])->name('upload.create');
    
    // Rute buat proses simpan data
    Route::post('/upload', [DocumentController::class, 'store'])->name('upload.store');
});

// Halaman Dashboard (Wajib Login)
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');