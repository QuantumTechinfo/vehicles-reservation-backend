<?php

namespace User\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use User\Models\Role;
use User\Models\Permission;
use Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(Gate::denies('role_access'),  response()->json(['error' => 'Unauthorized.'], 403));
        $roles = Role::with('permissions')->get()->map(function ($role) {
            return [
                'id' => $role->id,
                'title' => $role->title,
                'permissions' => $role->permissions->pluck('title', 'id')
            ];
        });
        return response()->json(['data' => $roles], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('role_create'),  response()->json(['error' => 'Unauthorized.'], 403));
        try {
            DB::beginTransaction();

            // Validate incoming request
            $data = $request->validate([
                'title' => ['required', 'string', 'max:255', 'unique:roles'],
                'permissions' => ['required', 'array'], // Ensure 'permissions' is an array
                'permissions.*' => ['integer', 'exists:permissions,id'], // Ensure each permission is a valid ID
            ]);

            // Create role
            $role = Role::create(['title' => $data['title']]);
            if ($role) {
                // Sync permissions
                $role->permissions()->sync($data['permissions']);
            }

            // Log the role ID and permissions
            \Log::info('Role created with ID: ' . $role->id . ', Permissions: ' . json_encode($data['permissions']));

            DB::commit();

            return response()->json(['message' => 'Role created successfully', 'data' => $role], 201);
        }
        catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Role creation failed: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort_if(Gate::denies('role_edit'),  response()->json(['error' => 'Unauthorized.'], 403));
        try{
            $role = Role::with('permissions')->findOrFail($id);

            $roleData = [
                'id' => $role->id,
                'title' => $role->title,
                'permissions' => $role->permissions->pluck('title', 'id')
            ];
            return response()->json(['data' => $roleData], 200);
        }
        catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Role not found',
                'error' => $e->getMessage()
            ], 404);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_if(Gate::denies('role_edit'),  response()->json(['error' => 'Unauthorized.'], 403));
        try {
            DB::beginTransaction();

            $role = Role::findOrFail($id);

            $data = $request->validate([
                'title' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($role->id)],
                'permissions' => ['required', 'array'],
                'permissions.*' => ['exists:permissions,id'],
            ]);

            $role->update(['title' => $data['title']]);

            $role->permissions()->sync($data['permissions']);

            DB::commit();

            return response()->json([
                'message' => 'Role updated successfully',
                'data' => $role->fresh()
            ], 200);
        }
        catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Role not found',
                'error' => $e->getMessage()
            ], 404);
        }
        catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
        catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Role update failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update Role',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_if(Gate::denies('role_delete'),  response()->json(['error' => 'Unauthorized.'], 403));

        try {
            DB::beginTransaction();
            $role = Role::findOrFail($id);
            // Detach all permissions
            $role->permissions()->detach();

            // Detach all users
            $role->users()->detach();

            // Delete the role
            $role->delete();

            DB::commit();

            return response()->json(['message' => 'Role deleted successfully','data' => $role], 200);

        }catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Permission not found',
                'error' => $e->getMessage()
            ], 404);
        }  catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Role deletion failed: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
