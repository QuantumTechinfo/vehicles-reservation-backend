<?php

namespace Vehicle\Traits;

use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

trait HasMediaTrait
{
    /**
     * Save media file and return the stored path.
     *
     * @param UploadedFile $file
     * @param string $type (e.g., 'blue_book', 'gallery')
     * @return string
     */
    public function saveVehicleMedia(UploadedFile $file, string $type): string
    {
        Log::info('Saving vehicle media', ['type' => $type]);

        // Determine the base folder based on the model's table name and media type
        $tableName = $this->getTable();
        $baseFolder = "uploads/{$tableName}/{$type}/";

        // Generate a unique file name
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . str_replace(' ', '_', $originalFileName);

        // Store the original file
        $filePath = $file->storeAs(
            $baseFolder . 'original',
            $fileName,
            'public'
        );

        // If the file is an image, process it to create resized versions
        if ($this->isImage($file->getMimeType())) {
            $this->processImage($file, $baseFolder, $fileName);
        }

        // Return the public URL of the stored file
        return Storage::disk('public')->url($filePath);
    }

    /**
     * Process an image file to create resized versions.
     *
     * @param UploadedFile $file
     * @param string $baseFolder
     * @param string $fileName
     */
    private function processImage(UploadedFile $file, string $baseFolder, string $fileName): void
    {
        $sizes = [
            'thumbnail' => [150, 150],
            'medium' => [300, 300],
            'large' => [600, 600],
        ];

        $manager = new ImageManager(new GdDriver());

        foreach ($sizes as $size => $dimensions) {
            $image = $manager->read($file);
            $resizedImage = $image->cover($dimensions[0], $dimensions[1]);

            // Save the resized image
            $path = "{$baseFolder}{$size}/{$fileName}";
            Storage::disk('public')->put($path, $resizedImage->toJpg()->toString());
        }
    }

    /**
     * Check if a file is an image based on its MIME type.
     *
     * @param string $mimeType
     * @return bool
     */
    private function isImage(string $mimeType): bool
    {
        return str_starts_with($mimeType, 'image/');
    }

    /**
     * Delete media files associated with a specific type.
     *
     * @param string $filePath
     */
    public function deleteVehicleMedia(string $filePath): void
    {
        Log::info('Deleting vehicle media', ['file_path' => $filePath]);

        // Extract the relative path from the public URL
        $relativePath = str_replace(Storage::disk('public')->url(''), '', $filePath);

        // Delete the original file
        Storage::disk('public')->delete($relativePath);

        // If it's an image, delete resized versions
        if ($this->isImage(Storage::disk('public')->mimeType($relativePath))) {
            $baseFolder = dirname($relativePath);
            $fileName = basename($relativePath);

            $sizes = ['thumbnail', 'medium', 'large'];
            foreach ($sizes as $size) {
                $resizedPath = "{$baseFolder}/{$size}/{$fileName}";
                if (Storage::disk('public')->exists($resizedPath)) {
                    Storage::disk('public')->delete($resizedPath);
                }
            }
        }
    }
}