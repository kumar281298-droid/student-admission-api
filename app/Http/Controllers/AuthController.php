<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Student;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'STUDENT',
            ]);

            $regNo = 'STU-' . date('Y') . '-' . str_pad((string) rand(100, 999), 3, '0', STR_PAD_LEFT);

            Student::create([
                'user_id' => $user->id,
                'registration_no' => $regNo,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'mobile' => $validated['mobile'] ?? null,
                'dob' => $validated['dob'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'address' => $validated['address'] ?? null,
                'status' => 'ACTIVE',
            ]);

            AuditLogService::log($user, 'USER_REGISTERED', 'User', $user->id);

            return $user;
        });

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'user' => $user->load('student'),
                'token' => $token,
            ],
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password credentials',
                'errors' => ['auth' => ['Invalid credentials']],
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        AuditLogService::log($user, 'USER_LOGIN', 'User', $user->id);

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user->load(['student', 'college']),
                'token' => $token,
            ],
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'User profile retrieved successfully',
            'data' => [
                'user' => $request->user()->load(['student', 'college']),
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        AuditLogService::log($request->user(), 'USER_LOGOUT', 'User', $request->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out',
            'data' => null,
        ]);
    }
}
