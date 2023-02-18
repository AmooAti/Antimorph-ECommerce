<?php

use App\Http\Controllers\API\Admin\AuthController;
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
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');

    // protected routes :
    Route::middleware('auth:customer')->group(function () {
        Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    });
});

Route::name("admin.")->middleware(['auth:admin'])->prefix('admin')->group(function () {
    Route::resource("customers", \App\Http\Controllers\Admin\Customer\CustomerController::class);
});

Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login');
