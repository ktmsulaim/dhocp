<?php

namespace App\Services\DocumentExport;

class DocumentExportStats
{
    const MISSING_LOG_LIMIT = 100;

    public $batchName = 'Students';
    public $studentsCount = 0;
    public $filesCount = 0;
    public $missingCount = 0;
    public $totalBytes = 0;

    /**
     * Missing-file lines kept for the manifest (capped, while missingCount keeps counting).
     *
     * @var array
     */
    protected $missingLog = [];

    public function addFile(int $size): void
    {
        $this->filesCount++;
        $this->totalBytes += $size;
    }

    public function recordMissing(string $line): void
    {
        $this->missingCount++;
        if (count($this->missingLog) < self::MISSING_LOG_LIMIT) {
            $this->missingLog[] = $line;
        }
    }

    /**
     * Move a previously counted file into the missing list.
     */
    public function recordFailure(int $size, string $line): void
    {
        $this->filesCount--;
        $this->totalBytes -= $size;
        $this->recordMissing($line);
    }

    public function getMissingLog(): array
    {
        return $this->missingLog;
    }

    public function toArray(): array
    {
        return [
            'students_count' => $this->studentsCount,
            'records_count' => $this->filesCount + $this->missingCount,
            'files_count' => $this->filesCount,
            'missing_count' => $this->missingCount,
            'total_bytes' => $this->totalBytes,
        ];
    }
}
