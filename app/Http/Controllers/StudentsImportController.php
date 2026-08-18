<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class StudentsImportController extends Controller
{
    public function index()
    {
        $batches = Batch::all();

        return Inertia::render('admin/students/Import', [
            'batches' => $batches,
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'batch_id' => 'required',
            'file' => 'required|file',
            'duplicate_mode' => 'required|in:skip,update',
        ]);

        $import = new UsersImport(
            $request->get('batch_id'),
            $request->get('duplicate_mode')
        );

        try {
            Excel::import($import, $request->file('file'));
        } catch (\Throwable $th) {
            return Redirect::back()->with('import_result', [
                'status' => 'error',
                'message' => 'Import could not finish. Please check the file and try again.',
            ]);
        }

        return Redirect::back()->with('import_result', [
            'status' => 'success',
            'message' => sprintf(
                'Import complete. Created: %d, updated: %d, skipped: %d.',
                $import->created,
                $import->updated,
                $import->skipped
            ),
        ]);
    }
}
