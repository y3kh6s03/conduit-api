<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::prefix('api/users')
->controller(AuthController::class)
->name('users.')
->group(function(){
    Route::post('/','register')->name('register');
});