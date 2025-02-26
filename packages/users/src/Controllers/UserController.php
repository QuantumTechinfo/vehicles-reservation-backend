<?php

namespace User\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

$manager = new ImageManager(new Driver());

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_if(auth()->user()->role !== 'admin', response()->json(['error' => 'Unauthorized.'], 403));

        $query = User::query();

        // Search functionality
        if ($request->has('keyword') && !empty($request->keyword)) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('email', 'LIKE', "%{$keyword}%")
                    ->orWhere('user_code', 'LIKE', "%{$keyword}%");
            });
        }

        if ($request->has('page') || $request->has('perPage')) {
            $perPage = $request->input('perPage', 10);
            $users = $query->paginate($perPage);

            $data = $users->through(fn($user) => $this->formatUser($user));

            return response()->json(['data' => $data], 200);
        }

        // If no pagination, return all results
        $data = $query->get()->map(fn($user) => $this->formatUser($user));
        return response()->json(['data' => $data], 200);
    }

    private function formatUser($user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'user_code' => $user->user_code,
            'phone_no' => $user->phone_no,
            'image' => $user->image,
            'role' => $user->role,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
        ];
    }

    private function processImage($image, $oldImage = null)
    {
        $manager = new ImageManager(new Driver());

        $imageName = "Image-" . date('Ymdhis') . random_int(0, 1234) . "." . $image->getClientOriginalExtension();

        $thumbnailPath = public_path('/thumbnail');
        if (!File::exists($thumbnailPath)) {
            File::makeDirectory($thumbnailPath, 0777, true, true);
        }
        $manager->read($image->getRealPath())
            ->resize(100, null)
            ->save($thumbnailPath . '/' . $imageName);

        $mediumPath = public_path('/img');
        if (!File::exists($mediumPath)) {
            File::makeDirectory($mediumPath, 0777, true, true);
        }
        $manager->read($image->getRealPath())
            ->resize(500, null)
            ->save($mediumPath . '/' . $imageName);

        $originalPath = public_path('/images');
        $image->move($originalPath, $imageName);

        if ($oldImage) {
            $oldImagePaths = [
                public_path('thumbnail/' . $oldImage),
                public_path('img/' . $oldImage),
                public_path('images/' . $oldImage)
            ];

            foreach ($oldImagePaths as $path) {
                if (File::exists($path)) {
                    File::delete($path);
                }
            }
        }

        return $imageName;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', Rule::unique(User::class)],
                'user_code' => ['required', 'string', 'max:10', Rule::unique(User::class)],
                'phone_no' => ['required', 'string', 'max:10', Rule::unique(User::class)],
                'password' => ['required', 'confirmed', 'min:6'],
                'image' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
                'role' => ['nullable', 'string', Rule::in(['admin', 'user', 'driver'])],
            ]);

            if ($request->hasFile('image')) {
                $data['image'] = $this->processImage($request->file('image'));
                \Log::info("Image processing completed successfully");
            }

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'user_code' => $data['user_code'] ?? "",
                'password' => Hash::make($data['password']),
                'phone_no' => $data['phone_no'],
                'image' => $data['image'] ?? "",
                'role' => $data['role'] ?? 'user'
            ]);

            \Log::info('User created - ID: ' . $user->id . ', Role: ' . $user->role);

            return response()->json([
                'message' => 'User created successfully',
                'data' => $user
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('User creation failed: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort_if(Gate::denies('user_edit'), response()->json(['error' => 'Unauthorized.'], 403));

        try {
            $user = User::findOrFail($id);
            return response()->json(['data' => $this->formatUser($user)], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_if(Gate::denies('user_edit'), response()->json(['error' => 'Unauthorized.'], 403));

        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);

            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'role' => ['required', 'string', Rule::in(['admin', 'user', 'driver'])],
                'phone_no' => ['required', 'string', 'max:10', Rule::unique(User::class)->ignore($user->id)],
                'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
                'user_code' => ['required', 'string', 'max:10', Rule::unique(User::class)->ignore($user->id)],
                'image' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            ]);

            if ($request->hasFile('image')) {
                $data['image'] = $this->processImage($request->file('image'), $user->image);
            }

            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'user_code' => $data['user_code'],
                'phone_no' => $data['phone_no'],
                'role' => $data['role'],
                'image' => $data['image'] ?? $user->image
            ]);

            DB::commit();
            return response()->json([
                'message' => 'User updated successfully',
                'data' => $this->formatUser($user->fresh())
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found'], 404);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("User update failed: {$e->getMessage()}");
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_if(Gate::denies('user_delete'), response()->json(['error' => 'Unauthorized.'], 403));

        try {
            $user = User::findOrFail($id);

            if (!empty($user->image)) {
                return \Log::error("User deletion failed.");
            }

            $user->delete();
            return response()->json(['message' => 'User deleted successfully'], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found'], 404);
        } catch (\Exception $e) {
            \Log::error("User deletion failed: {$e->getMessage()}");
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getDriver(Request $request)
    {
        abort_if(Gate::denies('driver_access'), response()->json(['error' => 'Unauthorized.'], 403));

        $query = User::where('role', 'driver');

        // Search functionality
        if ($request->has('keyword') && !empty($request->keyword)) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('email', 'LIKE', "%{$keyword}%")
                    ->orWhere('user_code', 'LIKE', "%{$keyword}%");
            });
        }

        // Pagination handling
        if ($request->has('page') || $request->has('perPage')) {
            $perPage = $request->input('perPage', 10);
            $drivers = $query->paginate($perPage);
            $data = $drivers->through(fn($driver) => $this->formatUser($driver));
        } else {
            $data = $query->get()->map(fn($driver) => $this->formatUser($driver));
        }

        return response()->json(['data' => $data], 200);
    }
}
