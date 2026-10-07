<?php

namespace App\Services;

use App\Models\TempatMenarik;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Throwable;

class TempatMenarikPhotoStorage
{
    public function url(string $path): ?string
    {
        try {
            return Storage::disk('s3')->temporaryUrl($path, now()->addMinutes(10));
        } catch (Throwable $exception) {
            report($exception);

            return null;
        }
    }

    /** @param array<string, mixed> $attributes */
    public function save(TempatMenarik $place, array $attributes, ?UploadedFile $photo): bool
    {
        $oldPath = $place->foto;
        $newPath = $photo ? $this->upload($photo) : null;

        try {
            DB::transaction(function () use ($place, $attributes, $newPath): void {
                $place->fill($attributes);
                if ($newPath) {
                    $place->foto = $newPath;
                }
                $place->save();
            });
        } catch (Throwable $exception) {
            if ($newPath) {
                try {
                    $this->deletePhoto($newPath);
                } catch (Throwable $cleanupException) {
                    report($cleanupException);
                }
            }
            throw $exception;
        }

        if ($newPath && $oldPath) {
            try {
                $this->deletePhoto($oldPath);
            } catch (Throwable $exception) {
                report($exception);

                return false;
            }
        }

        return true;
    }

    public function destroy(TempatMenarik $place): void
    {
        DB::transaction(function () use ($place): void {
            $place->delete();
            $this->deletePhoto($place->foto);
        });
    }

    private function upload(UploadedFile $photo): string
    {
        try {
            $path = $photo->store('bulungan/tempat-menarik', 's3');
            if ($path === false) {
                throw new RuntimeException('Photo upload failed.');
            }

            return $path;
        } catch (Throwable $exception) {
            report($exception);
            throw ValidationException::withMessages(['foto' => 'Foto belum berhasil diunggah. Silakan coba lagi.']);
        }
    }

    private function deletePhoto(string $path): void
    {
        try {
            if (! Storage::disk('s3')->delete($path)) {
                throw new RuntimeException('Photo deletion failed.');
            }
        } catch (Throwable $exception) {
            report($exception);
            throw ValidationException::withMessages(['foto' => 'Foto belum berhasil dihapus. Silakan coba lagi.']);
        }
    }
}
