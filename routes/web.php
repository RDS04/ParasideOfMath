<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('/informasi/index');
});
Route::get('/login', function () {
    return view('/login');
});

