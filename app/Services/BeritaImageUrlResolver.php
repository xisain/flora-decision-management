<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaImageUrlResolver
{
    public function resolve(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (Str::startsWith($path, 'bulungan/berita/')) {
            return Storage::disk('s3')->Url($path);
        }

        return Storage::url($path);
    }
}
