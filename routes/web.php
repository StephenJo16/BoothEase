<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landingpage.index');
})->name('home');

Route::get('/signup', function () {
    return view('signup.index');
});

Route::get('/login', function () {
    return view('login.index');
});

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
