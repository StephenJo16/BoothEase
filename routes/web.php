<?php

use Illuminate\Support\Facades\Route;
// Import AuthController di bagian atas
use App\Http\Controllers\AuthController;

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
});

// --- ROUTE UNTUK LOGOUT (HARUS SUDAH LOGIN) ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Anda bisa menambahkan route lain yang memerlukan otentikasi di sini
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

// ... (sisa route Anda yang lain tidak perlu diubah)
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