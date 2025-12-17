<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController; // Panggil Controller barunya
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\profileController;

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

Route::middleware(['auth'])->group(function () {
    // Tampilkan form edit profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Proses update profile
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
