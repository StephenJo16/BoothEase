<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BoothController;

use App\Models\User;

Route::get('/', function () {
    return view('landingpage.index');
})->name('home');

// --- GRUP ROUTE UNTUK USER YANG BELUM LOGIN (GUEST) ---
Route::middleware('guest')->group(function () {
    // Menampilkan halaman signup
    Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup');
    // Memproses data dari form signup
    Route::post('/signup', [AuthController::class, 'signup']);

    // Menampilkan halaman login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    // Memproses data dari form login
    Route::post('/login', [AuthController::class, 'login']);


    Route::get('/auth/google/redirect', [AuthController::class, 'googleRedirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [AuthController::class, 'googleCallback'])->name('google.callback');
});


// --- ROUTE UNTUK LOGOUT (HARUS SUDAH LOGIN) ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile', [UserController::class, 'show'])->name('profile');
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [UserController::class, 'updatePassword'])->name('profile.password');


    //onboarding after first oauth login
    Route::get('/onboarding', [AuthController::class, 'showOnboarding'])->name('onboarding.show');
    Route::post('/onboarding', [AuthController::class, 'saveOnboarding'])->name('onboarding.save');
});


// --- Route lainnya yang sudah ada ---
Route::get('/faq', function () {
    return view('faq.index');
})->name('faq');

Route::get('/events', function () {
    return view('events.index');
})->name('events');

Route::get('/events/details', function () {
    return view('events.details');
})->name('eventdetails');

Route::get('/my-bookings', function () {
    return view('my-bookings.index');
})->name('my-bookings');

Route::get('/my-bookings/details', function () {
    return view('my-bookings.details');
})->name('my-booking-details');

Route::get('/my-events', function () {
    return view('my-events.index');
})->name('my-events');

Route::get('/my-events/create', function () {
    return view('my-events.create');
})->name('create-event');

Route::get('/my-events/details', function () {
    return view('my-events.details');
})->name('my-event-details');

Route::get('/my-events/edit', function () {
    return view('my-events.edit');
})->name('my-event-edit');

Route::get('/request-refund', function () {
    return view('request-refund.index');
})->name('request-refund');

Route::get('refund-request', function () {
    return view('refund-request.index');
})->name('refund-request');

Route::get('/refund-request/details', function () {
    return view('refund-request.details');
})->name('refund-request-details');


Route::get('/booking-requests', function () {
    return view('booking-requests.index');
})->name('booking-requests');

Route::get('/booking-requests/details', function () {
    return view('booking-requests.details');
})->name('booking-request-details');

Route::get('/book-booth', function () {
    return view('book-booth.index');
})->name('book-booth');










Route::get('/testing-layout', function () {
    return view('testing-layout.index');
})->name('testing-layout');

Route::post('/testing-layout/save', [BoothController::class, 'store'])->name('testing-layout.save');
