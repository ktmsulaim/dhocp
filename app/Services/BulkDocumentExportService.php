<?php

namespace App\Services;

use App\Services\DocumentExport\DocumentExportManifest;
use App\Services\DocumentExport\DocumentFileCollector;
use ZipStream\Option\Archive as ArchiveOptions;
use ZipStream\Option\File as FileOptions;
use ZipStream\Option\Method;
use ZipStream\ZipStream;

class BulkDocumentExportService
{
    protected $collector;
    protected $manifest;

    public function __construct(DocumentFileCollector $collector, DocumentExportManifest $manifest)
    {
        $this->collector = $collector;
        $this->manifest = $manifest;
    }

    /**
     * Count the files an export would contain without building the archive.
     *
     * @return array students_count, records_count, files_count, missing_count, total_bytes, download_name
     */
    public function summary(array $options): array
    {
        iterator_count($this->collector->collect($options));

        return $this->collector->getStats() + ['download_name' => $this->buildDownloadName()];
    }

    /**
     * Download file name for the selected student filter.
     */
    public function downloadName(array $options): string
    {
        $this->collector->studentQuery($options['students'] ?? 'active');

        return $this->buildDownloadName();
    }

    /**
     * Stream the zip archive straight to the output buffer.
     */
    public function stream(array $options): void
    {
        $archiveOptions = new ArchiveOptions();
        $archiveOptions->setSendHttpHeaders(false);
        $archiveOptions->setZeroHeader(true);
        $archiveOptions->setEnableZip64(true);
        $archiveOptions->setFlushOutput(true);

        $zip = new ZipStream(null, $archiveOptions);

        foreach ($this->collector->collect($options) as $entry) {
            // Documents and images are already compressed, so store them as-is.
            $fileOptions = new FileOptions();
            $fileOptions->setMethod(Method::STORE());

            try {
                $zip->addFileFromPath($entry['zip_path'], $entry['real_path'], $fileOptions);
            } catch (\Throwable $e) {
                $this->collector->recordFailure($entry, 'Unreadable file skipped (' . $e->getMessage() . ')');
            }
        }

        $zip->addFile('export_summary.txt', $this->buildManifest($options));
        $zip->finish();
    }

    protected function buildManifest(array $options): string
    {
        $stats = $this->collector->getStats();

        return $this->manifest->generate(
            $this->collector->getBatchName(),
            $options['group_by'] ?? 'student_module',
            $options['naming_format'] ?? 'enroll_document',
            $stats['students_count'],
            $stats['files_count'],
            $stats['missing_count'],
            $this->collector->getMissingLog()
        );
    }

    protected function buildDownloadName(): string
    {
        return $this->collector->getBatchName() . '_documents.zip';
    }
}
