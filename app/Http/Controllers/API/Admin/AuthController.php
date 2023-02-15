<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Admin\AuthController\LoginRequest;
use App\Http\Resources\API\Admin\AuthenticationResource;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $request->validated();

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
        $response = [
            'message' => 'You are successfully logged in our API',
            'data' => AuthenticationResource::make($token),
        ];
        return response()->json($response, 200);
    }
}
