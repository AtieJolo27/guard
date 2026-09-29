<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'dashboard')->name('dashboard');
Route::livewire('/violations', 'violations');
Route::livewire('/login', 'auth.login');
Route::get('/guard/logout', function () {
    session()->forget(['guard_id', 'guard_name', 'guard_email']);
    return redirect('/login');
})->name('guard.logout');