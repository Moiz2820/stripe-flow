<?php

use App\Http\Controllers\Seller\LoginController;
use App\Http\Controllers\Seller\RegisterController;
use App\Http\Controllers\Seller\StripeConnectController;
use Illuminate\Support\Facades\Route;


Route::prefix('seller')->name('seller.')->group(function () {

    // Guest routes (only for non-logged in sellers)
    Route::middleware('guest:seller')->group(function () {
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [LoginController::class, 'login']);

        Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('register', [RegisterController::class, 'register']);
    });

    // Authenticated routes (only for logged in sellers)
    Route::middleware('auth:seller')->group(function () {
        Route::get('/dashboard', [StripeConnectController::class, 'dashboard'])->name('dashboard');

        Route::post('/stripe/connect/create', [StripeConnectController::class, 'createConnectedAccount'])->name('stripe.create');
        Route::get('/stripe/connect/start', [StripeConnectController::class, 'generateOnboardingLink'])->name('stripe.onboarding.start');
        Route::get('/stripe/connect/refresh', [StripeConnectController::class, 'refreshOnboarding'])->name('stripe.onboarding.refresh');
        Route::get('/stripe/connect/return', [StripeConnectController::class, 'handleOnboardingReturn'])->name('stripe.onboarding.return');
        Route::get('/stripe/account/details', [StripeConnectController::class, 'accountDetails'])->name('stripe.details');
        Route::post('/stripe/account/disconnect', [StripeConnectController::class, 'disconnectAccount'])->name('stripe.disconnect');

        Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    });
});
