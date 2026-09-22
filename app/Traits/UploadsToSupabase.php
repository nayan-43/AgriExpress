<?php

namespace App\Traits;

use App\Support\Supabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

trait UploadsToSupabase
{
    /**
     * Upload one image to Supabase Storage.
     *
     * - Keeps the original file extension.
     * - Keeps the original image format.
     * - Does not re-encode images that don't need resizing.
     * - Resizes oversized images while keeping their original format.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param int $maxWidth
     * @return string
     */
    protected function uploadToSupabase(
        UploadedFile $file,
        string $folder,
        int $maxWidth = 1600
    ): string {
        /*
         * Get the extension from the actual uploaded file.
         *
         * Examples:
         * jpg  -> jpg
         * jpeg -> jpeg
         * png  -> png
         * webp -> webp
         * gif  -> gif
         */
        $extension = strtolower(
            $file->getClientOriginalExtension()
        );

        /*
         * Generate a unique filename while preserving
         * the original extension.
         */
        $filename = Str::uuid() . '.' . $extension;

        $path = trim($folder, '/') . '/' . $filename;

        /*
         * Get the original dimensions first.
         *
         * This allows us to avoid re-encoding an image when
         * it is already within the requested maximum width.
         */
        [$width] = getimagesize($file->getRealPath());

        /*
         * If the image is already small enough, upload the
         * ORIGINAL file bytes.
         *
         * This gives you the best possible preservation of:
         * - original quality
         * - original compression
         * - metadata
         * - original encoding
         */
        if ($width <= $maxWidth) {
            Storage::disk('supabase')->put(
                $path,
                file_get_contents($file->getRealPath()),
                'public'
            );

            return $path;
        }

        /*
         * Image is wider than the maximum width, so resize it.
         *
         * Intervention Image v4 uses decodePath() instead of read().
         */
        $image = Image::decodePath($file->getRealPath())
            ->scaleDown(width: $maxWidth);

        /*
         * encode() automatically encodes the image using
         * the original image format.
         *
         * For example:
         * JPG  -> JPG
         * PNG  -> PNG
         * WEBP -> WEBP
         * GIF  -> GIF
         */
        $contents = $image
            ->encode()
            ->toString();

        Storage::disk('supabase')->put(
            $path,
            $contents,
            'public'
        );

        return $path;
    }

    /**
     * Upload several images at once.
     *
     * @param UploadedFile[] $files
     * @return string[]
     */
    protected function uploadManyToSupabase(
        array $files,
        string $folder,
        int $maxWidth = 800
    ): array {
        return array_map(
            fn (UploadedFile $file) =>
                $this->uploadToSupabase(
                    $file,
                    $folder,
                    $maxWidth
                ),
            $files
        );
    }

    /**
     * Build the public Supabase URL.
     */
    protected function supabaseUrl(?string $path): ?string
    {
        return Supabase::url($path);
    }

    /**
     * Delete a previously uploaded file.
     */
    protected function deleteFromSupabase(?string $path): void
    {
        if ($path) {
            Storage::disk('supabase')->delete($path);
        }
    }
}