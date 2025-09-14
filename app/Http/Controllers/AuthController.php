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
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::create($data);

        return response()->json([
            'success' => $user ? true : false,
            'user'    => $user
        ]);
    }

    /**
     * User login.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials  = $request->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'auth failed'
            ]);
        }

        $token = Auth::user()->createToken('token');

        return response()->json([
            'success' => true,
            'token' => $token->plainTextToken
        ]);
    }
}
