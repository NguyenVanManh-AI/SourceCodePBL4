<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Other (Product,Category,...)  
Route::prefix('products')->controller(ProductController::class)->group(function () {
    Route::middleware('auth:admin_api')->group(function () {
        Route::post('/', 'store');
        Route::post('update/{id}', 'update');
        Route::delete('/{id}', 'delete');
    });

    Route::middleware('auth:customer_api')->group(function () {
        Route::get('/', 'getAllProduct');
        Route::get('/{id}', 'show');
    });
});

Route::get('get-all', 'App\Http\Controllers\ProductController@getAll');
Route::post('add', 'App\Http\Controllers\ProductController@addp');

// Mặc định trong file api là localhost:8000/api/...
// Admin
Route::prefix('admin')->controller(AuthController::class)->group(function () { // các api có chung admin vào chung => /api/admin/...
    Route::post('login', 'login'); // localhost:8000/api/admin/login 
    Route::post('register', 'register');

    Route::post('reset-password', 'App\Http\Controllers\ResetPasswordController@sendMail');
    Route::put('reset-password/{token}', 'App\Http\Controllers\ResetPasswordController@reset');

    Route::middleware('auth:admin_api')->group(function () { // cứ nằm trong middleware là cần 
        Route::post('logout', 'logout');
        Route::post('me', 'me');
        Route::post('change-password', 'changePassword');// vẫn cần token , vẫn phải thông qua admin_api
        Route::patch('update-profile', 'updateProfile');
        Route::delete('detele-admin', 'deleteAdmin');
        Route::get('all-admin', 'allAdmins');
    });
});

// Customer 
Route::prefix('customer')->controller(CustomerAuthController::class)->group(function () {

    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::middleware('auth:admin_api')->group(function () {
        Route::post('logout', 'logout');
        Route::post('me', 'me');
    });
});