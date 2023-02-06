<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Customer\AuthController\LoginRequest;
use App\Http\Requests\API\Customer\AuthController\RegisterRequest;
use App\Http\Resources\API\Customer\AuthenticationResource;
use App\Http\Resources\API\Customer\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Store a newly created Customer in database.
     *
     * @param  \App\Http\Requests\API\Customer\AuthController\RegisterRequest  $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $user = Customer::create($data);

        if ($user) {
            $response = [
                'message' => 'You are successfully registered within our API',
                'data' => CustomerResource::make($user),
            ];
            return response()->json($response, 201);
        }

        $response = [
            'message' => 'Something is wrong! Please try again later...',
            'data' => [],
        ];
        return response()->json($response, 500);
    }

    /**
     * login user in the application using laravel sanctum.
     *
     * @param  \App\Http\Requests\API\Customer\AuthController\LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $user = Customer::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
                'data' => [],
            ], 401);
        }

        $user->last_login = now();
        $user->save();

        $token = $user->createToken('customer_token');
        $response = [
            'message' => 'You are successfully logged in our API',
            'data' => AuthenticationResource::make($token),
        ];
        return response()->json($response, 200);
    }

    /**
     * sing out loggedIn user and delete related access token.
     *
     * @return JsonResponse
     */
    public function logout()
    {
        auth('sanctum')->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Successfully logged out.'
        ], 200);
    }
}
