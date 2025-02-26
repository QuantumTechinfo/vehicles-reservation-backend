<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class AuthGates
{
    // Role-Permission mapping
    protected $rolePermissions = [
        'admin' => [
            'user_access',
            'user_edit',
            'user_delete',
            'driver_access',
            'vehicle_management',
            'booking_management'
        ],
        'vehicle_owner' => [
            'vehicle_management',
            'booking_management'
        ],
        'user' => [
            'basic_booking'
        ]
    ];

    public function handle(Request $request, Closure $next)
    {
        Log::info('AuthGates middleware initialized');

        try {
            $user = Auth::user();

            if (!$user) {
                Log::info('No authenticated user detected');
                return $next($request);
                // return redirect()->route('/');
            }

            if ($user) {
                return response()->json([
                    'message' => 'Vehicle created successfully',
                    'data' => $user
                ], 201);
            }

            Log::info("Processing gates for user: {$user->id} with role: {$user->role}");

            // Get permissions for the user's role
            $permissions = $this->rolePermissions[$user->role] ?? [];

            Log::info("Permissions for {$user->role}: " . implode(', ', $permissions));

            // Define gates based on role permissions
            foreach ($permissions as $permission) {
                Gate::define($permission, function () use ($user, $permission) {
                    return in_array($permission, $this->rolePermissions[$user->role] ?? []);
                });
            }

            // Special admin override
            if ($user->role === 'admin') {
                Gate::before(function ($user) {
                    return $user->role === 'admin';
                });
            }

            Log::info('Gates definition completed');

        } catch (\Exception $e) {
            Log::error('AuthGates error: ' . $e->getMessage());
        }

        return $next($request);
    }
}