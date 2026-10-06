<?php

namespace App\Services\DocumentExport;

use Illuminate\Support\Facades\Storage;

class DocumentPathResolver
{
    /**
     * Resolve the absolute disk path for a profile photo.
     */
    public function resolveProfilePhotoPath(string $imageName): ?string
    {
        $ds = DIRECTORY_SEPARATOR;

        return $this->firstExisting([
            Storage::disk('public')->path('uploads' . $ds . 'profile' . $ds . $imageName),
            public_path('uploads' . $ds . 'profile' . $ds . $imageName),
            public_path('storage' . $ds . 'uploads' . $ds . 'profile' . $ds . $imageName),
            storage_path('app' . $ds . 'public' . $ds . 'uploads' . $ds . 'profile' . $ds . $imageName),
            public_path('uploads' . $ds . $imageName),
        ]);
    }

    /**
     * Resolve the absolute disk path for an uploaded module document.
     */
    public function resolveModuleFilePath(string $fileName): ?string
    {
        $ds = DIRECTORY_SEPARATOR;

        return $this->firstExisting([
            Storage::disk('public')->path('uploads' . $ds . $fileName),
            public_path('uploads' . $ds . $fileName),
            public_path('storage' . $ds . 'uploads' . $ds . $fileName),
            storage_path('app' . $ds . 'public' . $ds . 'uploads' . $ds . $fileName),
        ]);
    }

    /**
     * Return the first candidate path that exists on disk.
     */
    protected function firstExisting(array $candidates): ?string
    {
        foreach ($candidates as $candidate) {
            if (file_exists($candidate)) {
                return $candidate;
            }
        }

        return null;
    }
}
