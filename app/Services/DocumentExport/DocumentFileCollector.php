<?php

namespace App\Services\DocumentExport;

use App\Models\Batch;
use App\Models\Item;
use App\Models\ItemUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class DocumentFileCollector
{
    const CHUNK_SIZE = 100;

    protected $naming;
    protected $resolver;
    protected $stats;

    public function __construct(DocumentExportNaming $naming, StudentFileResolver $resolver)
    {
        $this->naming = $naming;
        $this->resolver = $resolver;
        $this->stats = new DocumentExportStats();
    }

    /**
     * Yield every existing file of the selected students as
     * ['real_path', 'zip_path', 'size', 'label'] entries, accumulating stats along the way.
     */
    public function collect(array $options): \Generator
    {
        $this->stats = new DocumentExportStats();
        $this->naming->reset();

        $includePhoto = (bool)($options['include_photo'] ?? false);
        $itemsMap = $this->preloadItems((array)($options['items'] ?? []));
        $groupBy = $options['group_by'] ?? 'student_module';
        $namingFormat = $options['naming_format'] ?? 'enroll_document';
        $separator = $this->naming->resolveSeparator((string)($options['separator'] ?? 'underscore'));

        $query = $this->studentQuery($options['students'] ?? 'active');
        $lastId = 0;

        do {
            $students = (clone $query)->where('id', '>', $lastId)->orderBy('id')->limit(self::CHUNK_SIZE)->get();
            $itemUsersGrouped = $this->loadItemUsers($students->pluck('id')->all(), $itemsMap);

            foreach ($students as $student) {
                $this->stats->studentsCount++;
                $lastId = $student->id;
                $itemUsers = $itemUsersGrouped[$student->id] ?? [];
                $files = $this->resolver->resolve($student, $includePhoto, $itemUsers, $itemsMap, $this->stats);

                foreach ($files as $file) {
                    $folder = $this->naming->buildFolderPath($groupBy, $student, $file['module_name'], $file['field_label']);
                    $fileName = $this->naming->buildFileName($namingFormat, $separator, $student, $file['doc_type'], $file['ext']);
                    $size = (int)@filesize($file['real_path']);
                    $this->stats->addFile($size);

                    yield [
                        'real_path' => $file['real_path'],
                        'zip_path' => $this->naming->resolveUniqueZipPath($folder, $fileName),
                        'size' => $size,
                        'label' => $file['label'],
                    ];
                }
            }
        } while ($students->count() === self::CHUNK_SIZE);
    }

    /**
     * Move a previously yielded entry into the missing list (e.g. unreadable while streaming).
     */
    public function recordFailure(array $entry, string $reason): void
    {
        $this->stats->recordFailure((int)$entry['size'], "{$entry['label']}: {$reason}");
    }

    public function getStats(): array
    {
        return $this->stats->toArray();
    }

    public function getMissingLog(): array
    {
        return $this->stats->getMissingLog();
    }

    public function getBatchName(): string
    {
        return $this->stats->batchName;
    }

    /**
     * Build the student query for the selected filter and remember its display name.
     */
    public function studentQuery($studentsFilter): Builder
    {
        $query = User::query();

        if ($studentsFilter === 'active') {
            $this->stats->batchName = 'Active_Students';
            return $query->active();
        }

        if ($studentsFilter === 'all') {
            $this->stats->batchName = 'All_Students';
            return $query;
        }

        $batch = Batch::find($studentsFilter);
        $this->stats->batchName = $batch ? Str::slug($batch->name, '_') : 'Students';

        return $query->active()->where('batch_id', $studentsFilter);
    }

    protected function preloadItems(array $itemIds): array
    {
        $itemIds = array_filter($itemIds);
        if (empty($itemIds)) {
            return [];
        }

        return Item::with('module')->whereIn('id', $itemIds)->where('type', 'file')->get()->keyBy('id')->all();
    }

    protected function loadItemUsers(array $studentIds, array $itemsMap): array
    {
        if (empty($itemsMap) || empty($studentIds)) {
            return [];
        }

        return ItemUser::whereIn('user_id', $studentIds)
            ->whereIn('item_id', array_keys($itemsMap))
            ->whereNotNull('value_info')
            ->get()
            ->groupBy('user_id')
            ->all();
    }
}
