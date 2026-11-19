<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Format;
use Intervention\Image\Laravel\Facades\Image;

class AvatarService
{
    public const AVATAR_DIMENSION = 256;

    public const DIRECTORY = 'avatars';

    /**
     * Decode, center-crop/resize into a display-ready square thumbnail, encode cleanly,
     * and store on the public disk. Returns only the relative storage path.
     */
    public function prepareAndStore(UploadedFile $file): string
    {
        $extension = $this->resolveSafeExtension($file);

        // Read binary bytes instead of passing SplFileInfo so shared hosts with non-listable
        // upload_tmp_dir (e.g. /home/uploads) do not fail Intervention's directory readability check.
        $image = Image::decode($file->get())
            ->cover(self::AVATAR_DIMENSION, self::AVATAR_DIMENSION);

        $encoded = match ($extension) {
            'png' => $image->encodeUsingFormat(Format::PNG),
            'webp' => $image->encodeUsingFormat(Format::WEBP, quality: 85),
            default => $image->encodeUsingFormat(Format::JPEG, quality: 85),
        };

        $relativePath = self::DIRECTORY.'/'.Str::uuid().'.'.$extension;

        Storage::disk('public')->put($relativePath, (string) $encoded);

        return $relativePath;
    }

    /**
     * Delete an existing avatar file from the public filesystem disk if present.
     */
    public function delete(?string $relativePath): bool
    {
        if (empty($relativePath)) {
            return false;
        }

        if (! Storage::disk('public')->exists($relativePath)) {
            return false;
        }

        return Storage::disk('public')->delete($relativePath);
    }

    /**
     * Normalize validated image extensions so stored files match their encoded output.
     */
    private function resolveSafeExtension(UploadedFile $file): string
    {
        $guessed = strtolower((string) ($file->guessExtension() ?: $file->extension()));

        return match ($guessed) {
            'png' => 'png',
            'webp' => 'webp',
            default => 'jpg',
        };
    }
}
