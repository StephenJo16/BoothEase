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

Route::get('/event/details', function () {
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
