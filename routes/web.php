<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RequireGuardLogin;

Route::middleware(RequireGuardLogin::class)->group(function () {
    Route::livewire('/', 'dashboard')->name('dashboard');
    Route::livewire('/violations', 'violations');
});
Route::livewire('/login', 'auth.login')->name('login');
Route::get('/guard/logout', function () {
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/login');
})->name('guard.logout');
