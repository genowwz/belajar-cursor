<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\AdminController;

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Complaint submission (public)
Route::get('/submit-complaint', [ComplaintController::class, 'create'])->name('complaints.create');
Route::post('/submit-complaint', [ComplaintController::class, 'store'])->name('complaints.store');

// Authentication routes
Auth::routes();

// Protected routes (require login)
Route::middleware(['auth'])->group(function () {
    // User dashboard
    Route::get('/dashboard', [ComplaintController::class, 'index'])->name('dashboard');
    Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/{complaint}', [ComplaintController::class, 'show'])->name('complaints.show');
    Route::get('/complaints/{complaint}/edit', [ComplaintController::class, 'edit'])->name('complaints.edit');
    Route::put('/complaints/{complaint}', [ComplaintController::class, 'update'])->name('complaints.update');
    Route::delete('/complaints/{complaint}', [ComplaintController::class, 'destroy'])->name('complaints.destroy');
    
    // Admin routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/complaints', [AdminController::class, 'complaints'])->name('admin.complaints');
        Route::put('/admin/complaints/{complaint}/status', [AdminController::class, 'updateStatus'])->name('admin.complaints.status');
        Route::put('/admin/complaints/{complaint}/notes', [AdminController::class, 'updateNotes'])->name('admin.complaints.notes');
    });
});
