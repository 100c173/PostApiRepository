<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Register new User
     */
    public function register(RegisterRequest $request): JsonResponse
    {
       
        try {
            $user = $this->userRepository->create($request->validated());

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['token' => $token, 'message' => 'User registered successfully'], 201);

        } catch (\Exception $e) {
            Log::error('Error registering user: ' . $e->getMessage());
            return response()->json(['message' => 'Registration failed'], 500);
        }
    }

    /**
     * User Login
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                throw ValidationException::withMessages([
                    'email' => __('auth.failed'),
                ]);
            }

            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['token' => $token, 'message' => 'Logged in successfully'], 200);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error logging in user: ' . $e->getMessage());
            return response()->json(['message' => 'Login failed'], 500);
        }
    }

    /**
     * User logout 
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    /**
     * Get current user information
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json(['user' => $request->user()], 200);
    }
}