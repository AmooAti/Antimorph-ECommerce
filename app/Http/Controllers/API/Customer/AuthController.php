<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Customer\AuthController\RegisterRequest;
use App\Http\Resources\API\Customer\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}
