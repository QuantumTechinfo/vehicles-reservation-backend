<?php

namespace User\Controllers;

use Hash;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
class ProfileController extends Controller
{
    public function update_password(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            // abort_if(Gate::denies('profile'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $user = User::findOrFail($id);

            $request->validate([
                'password' => ['nullable', 'confirmed', 'min:6'],
                'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            ]);


            $user->password = Hash::make($request->new_password);
            if ($request->hasFile('image')) {
                $manager = new ImageManager(new Driver());
                $usersImage = public_path("thumbnail/{$request->image}");
                $usersImage1 = public_path("img/{$request->image}");
                $usersImage2 = public_path("images/{$request->image}");

                \Log::info("Processing image: {$request->image}");

                if (File::exists($usersImage)) {
                    unlink($usersImage);
                    File::delete($usersImage);
                    \Log::info("Deleted file: {$usersImage}");
                }
                if (File::exists($usersImage1)) {
                    unlink($usersImage1);
                    File::delete($usersImage1);
                    \Log::info("Deleted file: {$usersImage1}");
                }
                if (File::exists($usersImage2)) {
                    unlink($usersImage2);
                    File::delete($usersImage2);
                    \Log::info("Deleted file: {$usersImage2}");
                }

                $image = $request->file('image');
                $input['image_name'] = "Image-" . date('Ymdhis') . random_int(0, 1234) . "." . $request->image->getClientOriginalName();
                \Log::info("New image name: {$input['image_name']}");

                $path = public_path() . '/thumbnail';
                if (!File::exists($path)) {
                    File::makeDirectory($path, 0777, true, true);
                    \Log::info("Created directory: {$path}");
                }
                $destinationPath = public_path() . '/thumbnail';
                $img = $manager->read($image->getRealPath());
                $img->resize(100, null)
                   ->save($destinationPath . '/' . $input['image_name']);
                \Log::info("Saved thumbnail image: {$destinationPath}/{$input['image_name']}");

                $path = public_path() . '/img';
                if (!File::exists($path)) {
                    File::makeDirectory($path, 0777, true, true);
                    \Log::info("Created directory: {$path}");
                }
                $destinationPath = public_path() . '/img';
                $img = $manager->read($image->getRealPath());
                $img->resize(500, null)
                   ->save($destinationPath . '/' . $input['image_name']);
                \Log::info("Saved medium image: {$destinationPath}/{$input['image_name']}");

                $destinationPath = public_path('/images');
                $image->move($destinationPath, $input['image_name']);
                \Log::info("Moved original image: {$destinationPath}/{$input['image_name']}");

               $user->image = $input['image_name'];
                \Log::info("Image processing completed successfully");
            } else {
                \Log::info("No image file found in the request");
            }
            $user->save();

            DB::commit();

            return response()->json(['message' => 'User password updated successfully'], 200);
        }
        catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'User not found',
                'error' => $e->getMessage()
            ], 404);
        }
        catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
        catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Password update failed: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }
}
