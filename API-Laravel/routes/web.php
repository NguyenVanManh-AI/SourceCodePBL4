<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('auth/google', [CustomerAuthController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [CustomerAuthController::class, 'handleGoogleCallback']);