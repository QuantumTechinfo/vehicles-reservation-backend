<?php

namespace User\Controllers;

use User\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(Gate::denies('permission_access'),  response()->json(['error' => 'Unauthorized.'], 403));
        $permissions = Permission::all();
        return response()->json(['data' => $permissions], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('permission_create'),  response()->json(['error' => 'Unauthorized.'], 403));
        try {
            DB::beginTransaction();
            $permissions = new Permission();
            $data = $request->validate([
                'title' => 'required|string|max:255|unique:permissions',
            ]);

            $permissions->title = $data['title'];
            $permissions->save();

            DB::commit();

            return response()->json(['message' => 'Permissions created successfully', 'data' => $permissions], 201);
        }
        catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
        catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Permissions creation failed: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort_if(Gate::denies('permission_access'),  response()->json(['error' => 'Unauthorized.'], 403));
        try{
            $permission = Permission::findOrFail($id);
            return response()->json(['data' => $permission], 200);
        }
        catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Permission not found',
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
        abort_if(Gate::denies('permission_edit'),  response()->json(['error' => 'Unauthorized.'], 403));
        try {
            DB::beginTransaction();

            $permission = Permission::findOrFail($id);

            $data = $request->validate([
                'title' => ['required', 'string', 'max:255', Rule::unique('permissions')->ignore($permission->id)],
            ]);

            $permission->update([
                'title' => $data['title'],
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Permission updated successfully',
                'data' => $permission->fresh()
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Permission not found',
                'error' => $e->getMessage()
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Permission update failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update Permission',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_if(Gate::denies('permission_delete'),  response()->json(['error' => 'Unauthorized.'], 403));

        try {
            DB::beginTransaction();
            $permission = Permission::findOrFail($id);
            $rolesDetached = $permission->roles()->detach();
            \Log::info('Roles detached: ' . json_encode($rolesDetached));

            $deleted = $permission->delete();
            \Log::info('Permission deleted: ' . $deleted);

            DB::commit();
            return response()->json(['message' => 'Permission deleted successfully', 'data' => $permission], 200);

        }
        catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Permission not found',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Permission deletion failed: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
}
