<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/* HALAMAN REGISTER */
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

/*Login*/
Route::get('/login', function () {
    return view('auth.login');
})->name('login');