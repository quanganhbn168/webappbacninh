<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * HasMediaUpload Trait
 * 
 * A convenience layer for handling file uploads in Laravel models.
 * Unlike Spatie's HasMedia which stores media in a separate 'media' table,
 * this trait provides simple upload methods that store paths directly in model fields.
 * 
 * Use this for:
 * - Simple uploads where you don't need Spatie's advanced features
 * - Legacy compatibility (storing paths in model columns)
 * - Quick prototyping
 * 
 * Use Spatie HasMedia for:
 * - Multiple images per field
 * - Auto-generated conversions (thumbnails)
 * - Media collections with metadata
 */
trait HasMediaUpload
{
    /**
     * Upload a file from request and return the path.
     *
     * @param Request $request
     * @param string $fieldName
     * @param string $directory
     * @param string $disk
     * @return string|null
     */
    public function uploadFromRequest(Request $request, string $fieldName, string $directory = 'uploads', string $disk = 'public'): ?string
    {
        if (!$request->hasFile($fieldName)) {
            return null;
        }

        return $this->uploadFile($request->file($fieldName), $directory, $disk);
    }

    /**
     * Upload a file and return the public path.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string $disk
     * @return string
     */
    public function uploadFile(UploadedFile $file, string $directory = 'uploads', string $disk = 'public'): string
    {
        $filename = $this->generateFilename($file);
        $path = $file->storeAs($directory, $filename, $disk);
        
        return 'storage/' . $path;
    }

    /**
     * Upload a file with resize (requires Intervention Image).
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param int $width
     * @param int $height
     * @param string $disk
     * @return string
     */
    public function uploadAndResize(UploadedFile $file, string $directory, int $width, int $height, string $disk = 'public'): string
    {
        $filename = $this->generateFilename($file);
        $path = $directory . '/' . $filename;
        
        // Use Intervention Image for resizing
        $image = \Intervention\Image\Facades\Image::make($file);
        $image->fit($width, $height);
        
        // Store the resized image
        $storagePath = storage_path('app/public/' . $path);
        $image->save($storagePath);
        
        return 'storage/' . $path;
    }

    /**
     * Upload from a URL (e.g., pasted image).
     *
     * @param string $url
     * @param string $directory
     * @param string $disk
     * @return string|null
     */
    public function uploadFromUrl(string $url, string $directory = 'uploads', string $disk = 'public'): ?string
    {
        try {
            $contents = file_get_contents($url);
            $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            $filename = Str::uuid() . '.' . $extension;
            $path = $directory . '/' . $filename;
            
            \Illuminate\Support\Facades\Storage::disk($disk)->put($path, $contents);
            
            return 'storage/' . $path;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Delete a previously uploaded file.
     *
     * @param string|null $path
     * @param string $disk
     * @return bool
     */
    public function deleteUpload(?string $path, string $disk = 'public'): bool
    {
        if (!$path) {
            return false;
        }

        // Remove 'storage/' prefix if present
        $storagePath = str_replace('storage/', '', $path);
        
        return \Illuminate\Support\Facades\Storage::disk($disk)->delete($storagePath);
    }

    /**
     * Generate a unique filename for upload.
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function generateFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        
        return $name . '-' . Str::random(8) . '.' . $extension;
    }
}
