<?php

namespace App\Services\DocumentExport;

use App\Models\User;

class StudentFileResolver
{
    protected $paths;

    public function __construct(DocumentPathResolver $paths)
    {
        $this->paths = $paths;
    }

    /**
     * Resolve the existing photo and module documents of one student, logging missing ones.
     *
     * @param iterable $itemUsers ItemUser rows of this student
     * @param array $itemsMap Selected file items keyed by id
     */
    public function resolve(User $student, bool $includePhoto, iterable $itemUsers, array $itemsMap, DocumentExportStats $stats): array
    {
        $files = [];
        $who = "Student {$student->enroll_no} ({$student->name})";

        if ($includePhoto && !empty($student->image)) {
            $realPath = $this->paths->resolveProfilePhotoPath($student->image);
            if ($realPath) {
                $ext = pathinfo($realPath, PATHINFO_EXTENSION) ?: 'jpg';
                $label = "{$who}: Profile photo [{$student->image}]";
                $files[] = $this->fileData($realPath, 'Photo', 'Profile', 'Photo', $ext, $label);
            } else {
                $stats->recordMissing("{$who}: Missing profile photo [{$student->image}]");
            }
        }

        foreach ($itemUsers as $itemUser) {
            $item = $itemsMap[$itemUser->item_id] ?? null;
            $fileInfo = $item ? json_decode($itemUser->value_info, true) : null;
            if (empty($fileInfo) || empty($fileInfo['name'])) {
                continue;
            }

            $realPath = $this->paths->resolveModuleFilePath($fileInfo['name']);
            if (!$realPath) {
                $stats->recordMissing("{$who}: Missing file for [{$item->label}] ({$fileInfo['name']})");
                continue;
            }

            $ext = ($fileInfo['ext'] ?? pathinfo($realPath, PATHINFO_EXTENSION)) ?: 'dat';
            $fieldLabel = $item->label ?: 'Document';
            $label = "{$who}: File for [{$item->label}] ({$fileInfo['name']})";
            $files[] = $this->fileData($realPath, $fieldLabel, $item->module->name ?? 'Documents', $fieldLabel, $ext, $label);
        }

        return $files;
    }

    protected function fileData(string $realPath, string $docType, string $moduleName, string $fieldLabel, string $ext, string $label): array
    {
        return [
            'real_path' => $realPath,
            'doc_type' => $docType,
            'module_name' => $moduleName,
            'field_label' => $fieldLabel,
            'ext' => $ext,
            'label' => $label,
        ];
    }
}
