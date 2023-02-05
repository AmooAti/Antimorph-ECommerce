<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Admin\AuthController\LoginRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $params = [
            'username' => $request->username,
            'password' => $request->password
        ];
        try {
            if (! Auth::guard('admin')->attempt($params)) {
                return response()->json([
                    'message' => 'Credentials do not match',
                ], 401);
            }
        } catch (\Throwable $th) {
            // Method Illuminate\Auth\RequestGuard::attempt does not exist.
            error_log(print_r($th, true), 3, '/tmp/log.log');
        }
        
    }
}
