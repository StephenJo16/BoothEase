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
    return view('eventlist.index');
})->name('eventlist');
