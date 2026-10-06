<?php

namespace App\Services\DocumentExport;

use App\Models\User;

class DocumentExportNaming
{
    const SEPARATORS = [
        'underscore' => '_',
        'hyphen' => '-',
        'spaced_dash' => ' - ',
    ];

    /**
     * Registry of in-zip paths already used during the current export.
     *
     * @var array
     */
    protected $usedZipPaths = [];

    /**
     * Clear the used-path registry before starting a new export.
     */
    public function reset(): void
    {
        $this->usedZipPaths = [];
    }

    /**
     * Resolve a separator key (underscore, hyphen, spaced_dash) to its character.
     */
    public function resolveSeparator(string $key): string
    {
        return self::SEPARATORS[$key] ?? '_';
    }

    /**
     * Build in-zip folder structure based on grouping mode.
     */
    public function buildFolderPath(string $groupBy, User $student, string $moduleName, string $fieldLabel): string
    {
        $enrollClean = $this->sanitizePathSegment((string)$student->enroll_no);
        $moduleClean = $this->sanitizePathSegment($moduleName);
        $fieldClean = $this->sanitizePathSegment($fieldLabel);

        switch ($groupBy) {
            case 'flat':
                return '';
            case 'student':
                return $enrollClean;
            case 'student_module_field':
                return $enrollClean . '/' . $moduleClean . '/' . $fieldClean;
            case 'module_student':
                return $moduleClean . '/' . $enrollClean;
            case 'module_field':
                return $moduleClean . '/' . $fieldClean;
            case 'student_module':
            default:
                return $enrollClean . '/' . $moduleClean;
        }
    }

    /**
     * Build file name based on naming convention.
     */
    public function buildFileName(string $namingFormat, string $separator, User $student, string $docType, string $ext): string
    {
        $enrollClean = $this->sanitizePathSegment((string)$student->enroll_no);
        $docClean = $this->sanitizePathSegment($docType);
        $nameClean = $this->sanitizePathSegment((string)$student->name);
        $extClean = ltrim(strtolower($this->sanitizePathSegment($ext)), '.');

        switch ($namingFormat) {
            case 'enroll_only':
                $base = $enrollClean;
                break;
            case 'document_only':
                $base = $docClean;
                break;
            case 'enroll_name_document':
                $base = implode($separator, array_filter([$enrollClean, $nameClean, $docClean]));
                break;
            case 'enroll_document':
            default:
                $base = implode($separator, array_filter([$enrollClean, $docClean]));
                break;
        }

        if (empty($base)) {
            $base = 'file_' . uniqid();
        }

        return $base . ($extClean ? '.' . $extClean : '');
    }

    /**
     * Ensure unique in-zip paths to prevent accidental overwrite collisions.
     */
    public function resolveUniqueZipPath(string $folderPath, string $fileName): string
    {
        $cleanFolder = trim(str_replace('\\', '/', $folderPath), '/');
        $fullPath = $cleanFolder !== '' ? $cleanFolder . '/' . $fileName : $fileName;

        if (!isset($this->usedZipPaths[strtolower($fullPath)])) {
            $this->usedZipPaths[strtolower($fullPath)] = true;
            return $fullPath;
        }

        $pathInfo = pathinfo($fileName);
        $name = $pathInfo['filename'];
        $ext = !empty($pathInfo['extension']) ? '.' . $pathInfo['extension'] : '';

        $counter = 1;
        do {
            $indexedFileName = "{$name}_{$counter}{$ext}";
            $fullPath = $cleanFolder !== '' ? $cleanFolder . '/' . $indexedFileName : $indexedFileName;
            $counter++;
        } while (isset($this->usedZipPaths[strtolower($fullPath)]));

        $this->usedZipPaths[strtolower($fullPath)] = true;
        return $fullPath;
    }

    /**
     * Sanitize folder or filename segments to prevent invalid zip paths.
     */
    public function sanitizePathSegment(string $value): string
    {
        // Replace forbidden characters: \ / : * ? " < > | and control characters
        $clean = preg_replace('~[\\\\/:*?"<>|\x00-\x1F\x7F]+~u', '_', $value);
        $clean = preg_replace('~\s+~', ' ', $clean);
        $clean = trim($clean, ". \t\n\r\0\x0B");

        return $clean !== '' ? $clean : 'document';
    }
}
