<?php

namespace Vehicle\Controllers;

use Vehicle\Models\Vehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Vehicle\Traits\HasMediaTrait;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;

class VehicleController extends Controller
{
    use HasMediaTrait;

    /**
     * List all vehicles.
     */
    public function index()
    {
        // Uncomment the following line if you wish to use gate authorization:
        // abort_if(Gate::denies('vehicle_access'), response()->json(['error' => 'Unauthorized.'], 403));

        $vehicles = Vehicle::all();
        return response()->json(['data' => $vehicles], 200);
    }

    /**
     * Store a newly created vehicle.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $token = $request->header('Authorization');

        // Log whether the token and user are present.
        if ($user) {
            \Log::info('Authenticated user detected', [
                'uploader_id' => $user->id,
                'token' => $token
            ]);
        } else {
            \Log::warning('No authenticated user found. Token received:', ['token' => $token]);
        }


        try {
            DB::beginTransaction();

            $data = $request->validate([
                'vehicle_name' => ['required', 'string'],
                'vehicle_number' => ['required', 'string', 'unique:vehicles,vehicle_number'],
                'vehicle_description' => ['nullable', 'string'],
                'blue_book_photo' => 'nullable|image|max:2048',
                'drivers' => 'required|array',
                'drivers.*.name' => 'required|string',
                'drivers.*.contact_number' => 'required|string',
                'drivers.*.license_no' => 'required|string',
                'vehicle_images.*' => 'nullable|image|max:2048',
            ]);

            // Create vehicle record with uploader's id from authenticated user.
            $vehicle = Vehicle::create([
                'uploader_id' => $user->id, // vehicle owner
                'vehicle_name' => $data['vehicle_name'],
                'vehicle_number' => $data['vehicle_number'],
                'vehicle_description' => $data['vehicle_description'] ?? null,
            ]);

            // Handle blue book photo upload.
            if ($request->hasFile('blue_book_photo')) {
                $blueBookPath = $this->saveMedia($request->file('blue_book_photo'), 'blue_book');
                $vehicle->update(['blue_book' => $blueBookPath]);
            }

            // Save driver details as JSON.
            $vehicle->update(['drivers' => json_encode($data['drivers'])]);

            // Handle multiple vehicle images upload.
            if ($request->hasFile('vehicle_images')) {
                $images = [];
                foreach ($request->file('vehicle_images') as $image) {
                    $imagePath = $this->saveMedia($image, 'vehicle_images');
                    $images[] = $imagePath;
                }
                $vehicle->update(['vehicle_images' => json_encode($images)]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Vehicle created successfully',
                'data' => $vehicle
            ], 201);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Vehicle creation failed: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display a specific vehicle.
     */
    public function show(string $id)
    {
        abort_if(Gate::denies('vehicle_access'), response()->json(['error' => 'Unauthorized.'], 403));

        try {
            $vehicle = Vehicle::findOrFail($id);
            return response()->json(['data' => $vehicle], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Vehicle not found',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a specific vehicle.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            $vehicle = Vehicle::findOrFail($id);

            $data = $request->validate([
                'vehicle_name' => ['sometimes', 'string'],
                'license_no' => ['sometimes', 'string', Rule::unique('vehicles')->ignore($vehicle->id)],
                'vehicle_description' => ['nullable', 'string'],
                'blue_book_photo' => 'sometimes|image|max:2048',
                'drivers' => ['sometimes', 'array'],
                'drivers.*.name' => ['required_with:drivers', 'string'],
                'drivers.*.contact_number' => ['required_with:drivers', 'string'],
                'drivers.*.license_no' => ['required_with:drivers', 'string'],
                'vehicle_images' => ['sometimes', 'array'],
                'vehicle_images.*' => 'sometimes|image|max:2048',
            ]);

            $updateData = [];

            // Update only if new values are provided
            if (isset($data['vehicle_name'])) {
                $updateData['vehicle_name'] = $data['vehicle_name'];
            }

            if (isset($data['license_no'])) {
                $updateData['license_no'] = $data['license_no'];
            }

            if (isset($data['vehicle_description'])) {
                $updateData['vehicle_description'] = $data['vehicle_description'];
            }

            // Handle blue book photo update
            if ($request->hasFile('blue_book_photo')) {
                $updateData['blue_book'] = $this->saveMedia(
                    $request->file('blue_book_photo'),
                    'blue_book'
                );
            }

            // Update drivers if provided
            if (isset($data['drivers'])) {
                $updateData['drivers'] = $data['drivers'];
            }

            // Handle vehicle images update
            if ($request->hasFile('vehicle_images')) {
                $images = [];
                foreach ($request->file('vehicle_images') as $image) {
                    $images[] = $this->saveMedia($image, 'vehicle_images');
                }
                $updateData['vehicle_images'] = $images;
            }

            // Debug: log the update data if needed
            // Log::info('Update Data: ', $updateData);

            $vehicle->update($updateData);
            DB::commit();

            return response()->json([
                'message' => 'Vehicle updated successfully',
                'data' => $vehicle->fresh()
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Vehicle not found'], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Vehicle update failed: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Delete a specific vehicle.
     */
    public function destroy(string $id)
    {
        // abort_if(Gate::denies('vehicle_delete'), response()->json(['error' => 'Unauthorized.'], 403));

        try {
            DB::beginTransaction();
            $vehicle = Vehicle::findOrFail($id);
            // Optionally, remove associated media files if needed.
            $vehicle->delete();
            DB::commit();
            return response()->json([
                'message' => 'Vehicle deleted successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Vehicle not found',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Vehicle deletion failed: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
