<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('users')
    ->controller(AuthController::class)
    ->name('users.')
    ->group(function () {
        Route::post('/', 'register')->name('register');
        Route::post('/login', 'login')->name('login');
    });

Route::prefix('articles')
    ->controller(ArticleController::class)
    ->name('articles.')
    ->group(function () {
        Route::get('/{slug}', 'show')->name('show');

        Route::middleware('auth:api')->group(function () {
            Route::post('/', 'store')->name('store');
            Route::put('/{slug}', 'update')->name('update');
            Route::delete('/{slug}', 'delete')->name('delete');
        });
    });

Route::prefix('articles/{slug}/favorite')
    ->controller(FavoriteController::class)
    ->name('favorite.')
    ->group(function () {
        Route::middleware('auth:api')->group(function () {
            Route::post('/', 'store')->name('store');
            Route::delete('/', 'delete')->name('delete');
        });
    });
