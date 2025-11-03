<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {

    //---------------------------------------------------------
    //---------------------------------------------------------
    //USER
    Volt::route('login', 'auth.user.login')
        ->name('login');

    Volt::route('register', 'auth.user.register')
        ->name('register');

    Volt::route('forgot-password', 'auth.user.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'auth.user.reset-password')
        ->name('password.reset');

    //---------------------------------------------------------
    //---------------------------------------------------------
    //ADMIN
    Volt::route('admin/login', 'auth.admin.login')
        ->name('admin-login');
});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'auth.user.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'auth.user.confirm-password')
        ->name('password.confirm');
});

Route::post('logout', App\Livewire\Actions\Logout::class)
    ->name('logout');
