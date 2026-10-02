<?php

use App\Http\Controllers\LabController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LabController::class, 'index'])->name('home');
Route::get('/labs/{lab}', [LabController::class, 'show'])->name('labs.show');
Route::get('/api/labs/reservations', [LabController::class, 'getAllReservations'])->name('api.labs.all_reservations');
Route::get('/api/labs/{lab}/reservations', [LabController::class, 'getReservations'])->name('api.labs.reservations');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/labs/{lab}/book', [LabController::class, 'book'])->name('labs.book');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    
    // Operator / Admin only routes
    Route::middleware('can:manage-labs')->group(function () {
        Route::get('/admin/labs', [\App\Http\Controllers\AdminController::class, 'labsIndex'])->name('admin.labs.index');
        Route::get('/admin/schedules', [\App\Http\Controllers\AdminController::class, 'schedulesIndex'])->name('admin.schedules.index');
        Route::get('/admin/reservations/create', [ReservationController::class, 'create'])->name('admin.reservations.create');
        Route::post('/admin/reservations', [ReservationController::class, 'adminStore'])->name('admin.reservations.store');
        Route::get('/labs/{lab}/edit', [LabController::class, 'edit'])->name('labs.edit');
        Route::put('/labs/{lab}', [LabController::class, 'update'])->name('labs.update');
        Route::patch('/reservations/{reservation}/status', [ReservationController::class, 'updateStatus'])->name('reservations.updateStatus');
        Route::get('/reservations/{reservation}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
        Route::put('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
        Route::delete('/reservations/bulk-delete', [ReservationController::class, 'bulkDestroy'])->name('reservations.bulkDestroy');
        Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
    });

    // Super Admin only routes
    Route::middleware('can:manage-users')->group(function () {
        Route::get('/admin/users', [\App\Http\Controllers\AdminController::class, 'usersIndex'])->name('admin.users.index');
        Route::patch('/admin/users/{user}/role', [\App\Http\Controllers\AdminController::class, 'updateUserRole'])->name('admin.users.updateRole');
        
        Route::get('/admin/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('admin.reports.index');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
