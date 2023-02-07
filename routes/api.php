<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\API\Customer\AuthController;

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
    });
});


Route::name("admin.")->prefix('admin')->group(function () {

    Route::resource("customers", \App\Http\Controllers\API\Customer\CustomerController::class);
});
