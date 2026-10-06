<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentExportRequest;
use App\Models\Batch;
use App\Models\Module;
use App\Models\User;
use App\Services\BulkDocumentExportService;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class StudentDocumentExportController extends Controller
{
    protected $exportService;

    public function __construct(BulkDocumentExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    /**
     * Display the document export page.
     */
    public function index()
    {
        $batches = Batch::all();

        // Get modules that actually contain file items
        $modules = Module::whereHas('items', function ($query) {
            $query->where('type', 'file');
        })->with(['items' => function ($query) {
            $query->where('type', 'file');
        }])->get();

        $stats = [
            'totalStudents' => User::count(),
            'activeStudents' => User::active()->count(),
            'totalPhotos' => User::whereNotNull('image')->where('image', '!=', '')->count(),
        ];

        return Inertia::render('admin/students/ExportDocuments', [
            'batches' => $batches,
            'modules' => $modules,
            'stats' => $stats,
        ]);
    }

    /**
     * Pre-check how many files an export would contain.
     */
    public function summary(DocumentExportRequest $request)
    {
        try {
            $result = $this->exportService->summary($request->exportOptions());
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Export failed: ' . $e->getMessage()], 500);
        }

        if ($result['files_count'] === 0) {
            return response()->json($result + ['message' => $this->buildNoFilesMessage($result)], 422);
        }

        return response()->json($result + ['message' => $this->buildAvailableMessage($result)]);
    }

    /**
     * Stream the bulk document zip directly to the browser.
     */
    public function export(DocumentExportRequest $request)
    {
        $options = $request->exportOptions();

        try {
            $result = $this->exportService->summary($options);
        } catch (\Throwable $e) {
            return Redirect::back()->withErrors(['export' => 'Export failed: ' . $e->getMessage()]);
        }

        if ($result['files_count'] === 0) {
            return Redirect::back()->withErrors(['export' => $this->buildNoFilesMessage($result)]);
        }

        @set_time_limit(0);
        ignore_user_abort(false);

        return response()->streamDownload(function () use ($options) {
            while (ob_get_level() > 0) {
                ob_end_clean();
            }
            $this->exportService->stream($options);
        }, $result['download_name'], [
            'Content-Type' => 'application/zip',
            'X-Accel-Buffering' => 'no',
            'Cache-Control' => 'no-store',
        ]);
    }

    /**
     * Build the pre-check message for an export that has files available.
     */
    protected function buildAvailableMessage(array $result): string
    {
        $message = "{$result['files_count']} of {$result['records_count']} files are available for {$result['students_count']} students.";

        if ($result['missing_count'] > 0) {
            $message .= " {$result['missing_count']} missing files will be listed in export_summary.txt.";
        }

        return $message;
    }

    /**
     * Build an informative message for an export that produced no files.
     */
    protected function buildNoFilesMessage(array $result): string
    {
        $studentsCount = (int)($result['students_count'] ?? 0);
        $recordsCount = (int)($result['records_count'] ?? 0);

        if ($studentsCount === 0) {
            return 'No students matched the selected filter.';
        }

        if ($recordsCount === 0) {
            return "None of the {$studentsCount} selected students have uploaded the selected documents or photos.";
        }

        return "{$recordsCount} files are recorded for {$studentsCount} students, but none of them could be found in the server storage.";
    }
}
