<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController; 
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');


Route::post('/login', [LoginController::class, 'authenticate'])->name('login.process');


Route::post('/register', [RegisterController::class, 'store'])->name('register.process');


Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/upload', [DocumentController::class, 'create'])->name('upload.create');
    
    Route::post('/upload', [DocumentController::class, 'store'])->name('upload.store');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
    
    Route::post('/project/{id}/approve', [PaymentController::class, 'approveOffer'])->name('project.approve');

    Route::get('/payment/pay/{id}', [PaymentController::class, 'pay'])->name('payment.pay');
    

    Route::post('/payment/process/{id}', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/invoice/{id}', [PaymentController::class, 'receipt'])->name('payment.receipt');
});