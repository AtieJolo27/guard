<?php

use App\Http\Controllers\AdminCaseController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuardReportController;
use App\Http\Controllers\OfficePageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'create'])->name('home');
Route::get('/login', [AuthController::class, 'create'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:login')->name('login.store');
Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
Route::prefix('guard')->middleware('svrms.role:guard')->name('guard.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'guard'])->name('dashboard');
    Route::get('/reports/create', [GuardReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [GuardReportController::class, 'store'])->middleware('throttle:case-write')->name('reports.store');
    Route::get('/students/{code}', [GuardReportController::class, 'lookup'])->where('code', '[0-9]{2}-[0-9]{5}')->middleware('throttle:student-lookup')->name('students.lookup');
});
Route::prefix('admin')->middleware('svrms.role:admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    Route::get('/cases', [AdminCaseController::class, 'index'])->name('cases.index');
    Route::get('/cases/{id}', [AdminCaseController::class, 'show'])->whereNumber('id')->name('cases.show');
    Route::patch('/cases/{id}', [AdminCaseController::class, 'update'])->whereNumber('id')->middleware('throttle:case-write')->name('cases.update');
});

Route::prefix('admin')->middleware('svrms.role:admin')->name('admin.')->group(function () {
    Route::get('/reports/create', [GuardReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [GuardReportController::class, 'store'])->middleware('throttle:case-write')->name('reports.store');
    Route::get('/students/lookup/{code}', [GuardReportController::class, 'lookup'])->where('code', '[0-9]{2}-[0-9]{5}')->middleware('throttle:student-lookup')->name('students.lookup');
    Route::get('/office/{page}', [OfficePageController::class, 'show'])->whereIn('page', ['students', 'categories', 'reports', 'audit', 'settings'])->name('office');
});
