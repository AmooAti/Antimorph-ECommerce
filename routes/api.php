<?php

use App\Http\Controllers\API\Admin\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login');
