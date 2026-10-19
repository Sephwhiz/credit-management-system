<?php

use App\Http\Controllers\Admin\AdminAnalyticsController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminLoanController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

// OTP Verification Routes (Accessible to users who just registered)
Route::get('/verify-otp', [RegisteredUserController::class, 'showOtpVerification'])
    ->name('verify-otp')
    ->middleware('guest');

Route::post('/verify-otp', [RegisteredUserController::class, 'confirmOtp'])
    ->name('verify-otp.confirm')
    ->middleware('guest');

// Authenticated Routes
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Loan Management (Phase 3: Core Business Logic)
    Route::resource('loans', LoanController::class);

    // Charts & Analytics (Module 11: Data Visualization)
    Route::get('/charts', [ChartController::class, 'index'])->name('charts.index');

    // Admin Panel Routes (Protected by AdminMiddleware)
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/loans', [AdminLoanController::class, 'index'])->name('admin.loans.index');
        Route::post('/loans/{loan}/approve', [AdminLoanController::class, 'approve'])->name('admin.loans.approve');
        Route::post('/loans/{loan}/reject', [AdminLoanController::class, 'reject'])->name('admin.loans.reject');
        Route::get('/analytics', [AdminAnalyticsController::class, 'index'])->name('admin.analytics');
    });
});

require __DIR__.'/auth.php';