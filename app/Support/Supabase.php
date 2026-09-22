<?php

namespace App\Support;

/**
 * Turns a stored Supabase Storage path (what's saved on the model, e.g.
 * "products/ab12-cd34.jpg") into a public URL. Used by every model's
 * *_url accessor and by App\Traits\UploadsToSupabase.
 */
class Supabase
{
    public static function url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return rtrim(config('services.supabase.url'), '/')
            .'/storage/v1/object/public/'
            .config('services.supabase.bucket')
            .'/'.ltrim($path, '/');
    }
}
