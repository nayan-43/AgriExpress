<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Shared by every admin controller that stores an image (products,
 * categories, brands). Keeps the Supabase-specific bits — the S3-compatible
 * disk name and how a public URL is built — in one place instead of
 * repeating them per controller.
 */
trait UploadsToSupabase
{
    /**
     * Upload one file to Supabase Storage and return the stored path
     * (not the URL — store the path in the DB, resolve the URL on read
     * with supabaseUrl() so a bucket rename only means changing .env).
     */
    protected function uploadToSupabase(UploadedFile $file, string $folder): string
    {
        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
        $path = trim($folder, '/').'/'.$filename;

        Storage::disk('supabase')->put($path, file_get_contents($file->getRealPath()), 'public');

        return $path;
    }

    /**
     * Upload several files at once (product gallery images). Returns the
     * stored paths in the same order the files were given.
     *
     * @param  UploadedFile[]  $files
     * @return string[]
     */
    protected function uploadManyToSupabase(array $files, string $folder): array
    {
        return array_map(fn (UploadedFile $file) => $this->uploadToSupabase($file, $folder), $files);
    }

    /**
     * Build the public URL for a stored path. Delegates to App\Support\Supabase
     * so models and controllers build URLs the exact same way.
     */
    protected function supabaseUrl(?string $path): ?string
    {
        return \App\Support\Supabase::url($path);
    }

    /**
     * Delete a previously uploaded file. Safe to call with null/empty paths
     * (e.g. a product that never had a main image).
     */
    protected function deleteFromSupabase(?string $path): void
    {
        if ($path) {
            Storage::disk('supabase')->delete($path);
        }
    }
}
