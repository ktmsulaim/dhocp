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
        if (!$request->has('batch_id')) {
            return Redirect::back();
        }

        $batch_id = $request->get('batch_id');
        Excel::import(new UsersImport($batch_id), request()->file('file'));

        return Redirect::back();
    }
}
