<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'user_code' => 'required_without:email|string',
                'email' => 'required_without:user_code|email',
                'password' => 'required_with:email|string|min:6',
            ]);

            if ($request->has('user_code')) {
                return $this->handleUserLogin($request);
            } else {
                return $this->handleAdminLogin($request);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Failed to Login: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to Login',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function handleUserLogin(Request $request): JsonResponse
    {
        $user = \App\Models\User::where('user_code', $request->user_code)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $role = $user->role; // Use the direct role field

        // Only allow login for users with role "user"
        if ($role !== 'user') {
            return response()->json(['message' => 'Not allowed for non-user accounts.'], 403);
        }

        Auth::login($user);
        $token = JWTAuth::fromUser($user);

        return $this->loginResponse($user, $role, $token);
    }

    private function handleAdminLogin(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::guard('api')->user();
        $role = $user->role; // Use the direct role field

        // Only allow login for admin accounts
        if ($role !== 'admin') {
            return response()->json(['message' => 'Not allowed for non-admin accounts.'], 403);
        }

        return $this->loginResponse($user, $role, $token);
    }

    private function loginResponse($user, $role, $token): JsonResponse
    {
        return response()->json([
            'message' => 'Successfully logged in',
            'user' => $user,
            'role' => $role,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function setToken(Request $request)
    {
        try {
            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'fcm_token' => 'required|string|max:255',
                'user_id' => 'required|integer|exists:users,id',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // Get the validated data
            $validatedData = $validator->validated();

            // Find the user
            $user = \App\Models\User::findOrFail($validatedData['user_id']);

            // Check if the authenticated user is trying to update their own token
            if ($request->user()->id !== $user->id) {
                return response()->json([
                    'message' => 'Unauthorized action'
                ], 403);
            }

            // Update the user's FCM token
            $user->update([
                'fcm_token' => $validatedData['fcm_token']
            ]);

            \Log::info('FCM Token updated for user: ' . $user->id);

            return response()->json([
                'message' => 'Successfully Updated FCM Token',
                'user_id' => $user->id
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Failed to set user FCM token: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to set user FCM token',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
