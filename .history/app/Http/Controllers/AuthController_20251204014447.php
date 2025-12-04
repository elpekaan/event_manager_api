<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use League\Config\Exception\ValidationException;

class AuthController extends Controller
{
    /**
     * Yeni kullanıcı kaydı
     *
     * POST /api/register
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user,
                'access_token' => $token,
            ]
        ], 201);
    }

    /**
     * Kullanıcı girişi
     *
     * POST /api/login
     */
    public function login(Request $request): JsonResponse
    {

        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User logged in successfully',
            'data' => [
                'user' => $user,
                'token' => $token,
            ]
        ]);
    }

    /**
     * Kullanıcı çıkışı
     *
     * POST /api/logout
     */
    public function logout(Request $request): JsonResponse
    {

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'User logged out successfully',
        ]);
    }

    /**
     * Giriş yapmış kullanıcı bilgisi
     *
     * GET /api/user
     */
    
}
