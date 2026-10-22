<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'halamanLogin']);
Route::get('/hello', function () {
    return view('hello');
});



