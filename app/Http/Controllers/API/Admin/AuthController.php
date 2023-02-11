<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Admin\AuthController\LoginRequest;
use App\Http\Resources\API\Admin\AuthenticationResource;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $admin = Admin::where('email', $request->email)->first();
        
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
                'date' => [],
            ], 401);
        }

        $admin->last_login = now();
        $admin->save();

        $token = $admin->createToken('admin_token');
        // Log::debug('token: ', $token);
        $response = [
            'message' => 'You are successfully logged in our API',
            'data' => AuthenticationResource::make($token),
        ];
        return response()->json($response, 200);
    }
}
