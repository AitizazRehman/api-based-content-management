<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\SessionTracker;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('auth_token')->plainTextToken;

        $session = app(SessionTracker::class);
        $session->startSession($request->user()->id, $request->attributes->get('country_code'));

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'session_id' => session()->getId(),
        ]);
    }

    public function logout(Request $request)
    {
        $sessionId = session()->getId();
        $sessionTracker = app(SessionTracker::class);
        $duration = $sessionTracker->endSession($request->user()->id);
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out',
            'session_id' => $sessionId,
            'session_duration' => $duration ?? 'unknown'
        ]);
    }
}
