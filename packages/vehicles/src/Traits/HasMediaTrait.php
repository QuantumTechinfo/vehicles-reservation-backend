<?php

namespace Vehicle\Traits;

use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Import Log facade
use Vehicle\Models\Media;
use setasign\Fpdi\Fpdi;
trait HasMediaTrait
{
    public function saveMedia(UploadedFile $file, string $fieldName = 'image')
    {
        Log::info('Starting saveMedia process', ['field_name' => $fieldName]);

        // Create a file name with no spaces
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . str_replace(' ', '_', $originalFileName);
        $baseFolder = 'uploads/' . $this->getTable() . '/';
        $originalFilePath = $baseFolder . 'original/' . $fileName;

        // Get mime type and file size
        $mimeType = $file->getMimeType();
        $fileSize = $file->getSize();
        Log::info('File details', ['mime_type' => $mimeType, 'file_size' => $fileSize]);

        // Check if there's an existing media record
        $existingMedia = $this->media()->where('field_name', $fieldName)->where('mediable_id', $this->id)->first();

        if ($existingMedia) {
            Log::info('Existing media found, deleting old files', ['media_id' => $existingMedia->id]);
            $this->deleteExistingMediaFiles($existingMedia);
            $existingMedia->delete();
        }

        // Store the original file
        Storage::disk('public')->put($originalFilePath, file_get_contents($file));
        Log::info('Original file stored', ['path' => $originalFilePath]);

        $metadata = [];

        // Handle different file types
        if ($this->isImage($mimeType)) {
            Log::info('Processing image file');
            // Create and store different sizes for images
            $sizes = [
                'thumbnail' => [150, 150],
                'medium' => [300, 300],
                'large' => [600, 600],
            ];

            $resizedPaths = [];
            $manager = new ImageManager(new GdDriver());

            foreach ($sizes as $size => $dimensions) {
                $image = $manager->read($file);
                $resizedImage = $image->cover($dimensions[0], $dimensions[1]);
                $resizedFilePath = $baseFolder . $size . '/' . $fileName;
                Storage::disk('public')->put($resizedFilePath, $resizedImage->toJpg()->toString());
                $resizedPaths[$size] = $resizedFilePath;
                Log::info('Image resized and stored', ['size' => $size, 'path' => $resizedFilePath]);
            }

            $metadata = [
                'thumbnail_path' => $resizedPaths['thumbnail'],
                'medium_path' => $resizedPaths['medium'],
                'large_path' => $resizedPaths['large'],
                'file_type' => 'image'
            ];
        } elseif ($this->isPDF($mimeType)) {
            Log::info('Processing PDF file');
            $metadata = [
                'file_type' => 'pdf',
                'original_name' => $originalFileName,
                'page_count' => $this->getPDFPageCount($file->getPathname())
            ];
            Log::info('PDF metadata', $metadata);
        } else {
            Log::error('Unsupported file type', ['mime_type' => $mimeType]);
            throw new \Exception('Unsupported file type. Only images and PDFs are allowed.');
        }

        // Create new media record
        $media = $this->media()->create([
            'file_name' => $fileName,
            'file_path' => $originalFilePath,
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'field_name' => $fieldName,
            'metadata' => json_encode($metadata),
        ]);
        Log::info('Media record created', ['media_id' => $media->id]);

        return $media;
    }

    public function deleteExistingMediaFiles(Media $media)
    {
        Log::info('Deleting existing media files', ['media_id' => $media->id]);

        // Delete original file
        Storage::disk('public')->delete($media->file_path);

        // Delete additional files based on file type
        if ($media->metadata) {
            $metadata = json_decode($media->metadata, true);

            if (isset($metadata['file_type']) && $metadata['file_type'] === 'image') {
                // Delete resized images
                foreach (['thumbnail_path', 'medium_path', 'large_path'] as $path) {
                    if (isset($metadata[$path])) {
                        Storage::disk('public')->delete($metadata[$path]);
                        Log::info('Deleted resized image', ['path' => $metadata[$path]]);
                    }
                }
            }
        }
    }

    public function deleteMedia(string $media_url)
    {
        $filename = basename($media_url);
        Log::info('Deleting media by URL', ['media_url' => $media_url]);

        // Find the media record
        $media = Media::where('file_name', $filename)->first();

        if (!$media) {
            Log::warning("Media record not found for URL: $media_url");
            return false;
        }

        // Delete the files
        $this->deleteExistingMediaFiles($media);

        // Delete the media record from the database
        $media->delete();
        Log::info('Media record deleted', ['media_id' => $media->id]);

        return true;
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    private function isImage(string $mimeType): bool
    {
        return strpos($mimeType, 'image/') === 0;
    }

    private function isPDF(string $mimeType): bool
    {
        return $mimeType === 'application/pdf';
    }

    private function getPDFPageCount(string $filePath): int
    {
        try {
            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile($filePath);
            Log::info('PDF page count retrieved', ['page_count' => $pageCount]);
            return $pageCount;
        } catch (\Exception $e) {
            Log::warning("Could not get PDF page count: " . $e->getMessage());
            return 0;
        }
    }
}
