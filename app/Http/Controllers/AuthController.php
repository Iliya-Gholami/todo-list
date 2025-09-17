<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Creates new user in the database.
     * 
     * @param RegisterRequest $request
     * @param JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::create($data);

        return response()->json([
            'success' => $user ? true : false,
            'message' => $user ? 'User created successfully.' : 'User creation failed.',
            'user_id' => $user?->id,
        ], $user ? 201 : 500);
    }

    /**
     * User login.
     * 
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials  = $request->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Auth failed.',
            ], 401);
        }

        $token = Auth::user()->createToken('token');

        return response()->json([
            'success' => true,
            'token' => $token->plainTextToken,
        ]);
    }
}
