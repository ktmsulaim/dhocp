<?php

namespace App\Services\DocumentExport;

class DocumentExportManifest
{
    const LOG_LINES = 100;

    /**
     * Generate text manifest summarizing the export.
     *
     * @param array $missingFilesLog Capped list of missing-file lines; $missingFiles holds the full count
     */
    public function generate(
        string $batchName,
        string $groupBy,
        string $namingFormat,
        int $studentsProcessed,
        int $addedFiles,
        int $missingFiles,
        array $missingFilesLog
    ): string {
        $now = date('Y-m-d H:i:s');
        $content = "====================================================\n";
        $content .= " DHOCP Student Bulk Document & Photo Export Manifest\n";
        $content .= "====================================================\n";
        $content .= "Generated at: {$now}\n";
        $content .= "Target Filter: {$batchName}\n";
        $content .= "Folder Grouping: {$groupBy}\n";
        $content .= "File Naming Format: {$namingFormat}\n";
        $content .= "----------------------------------------------------\n";
        $content .= "Students Processed: {$studentsProcessed}\n";
        $content .= "Total Files Archived: {$addedFiles}\n";
        $content .= "Missing Files Skipped: {$missingFiles}\n";
        $content .= "----------------------------------------------------\n";

        if (!empty($missingFilesLog)) {
            $shownLines = array_slice($missingFilesLog, 0, self::LOG_LINES);
            $content .= "\nMissing File Details (Database records where disk file was not found):\n";
            foreach ($shownLines as $logEntry) {
                $content .= " - {$logEntry}\n";
            }
            $remaining = max(count($missingFilesLog), $missingFiles) - count($shownLines);
            if ($remaining > 0) {
                $content .= " ... and {$remaining} more missing files.\n";
            }
        }

        return $content;
    }
}
