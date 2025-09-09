<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landingpage');
})->name('home');

Route::get('/signup', function () {
    return view('signup');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/faq', function () {
    return view('faq');
})->name('faq');
