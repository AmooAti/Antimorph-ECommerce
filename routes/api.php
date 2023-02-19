<?php

use App\Http\Controllers\API\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\API\Customer\AuthController as CustomerAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::name('customer.')->prefix('customer')->group(function () {
    Route::post('register', [CustomerAuthController::class, 'register'])->name('register');
    Route::post('login', [CustomerAuthController::class, 'login'])->name('login');

    // protected routes :
    Route::middleware('auth:customer')->group(function () {
        Route::get('logout', [CustomerAuthController::class, 'logout'])->name('logout');
    });
});

Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login');
