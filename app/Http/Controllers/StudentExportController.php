<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Models\Batch;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class StudentExportController extends Controller
{
    public function index()
    {
        $batches = Batch::all();
        $modules = Module::all();

        return Inertia::render('admin/students/Export', [
            'batches' => $batches,
            'modules' => $modules,
        ]);
    }

    public function export(Request $request)
    {
        $modules = null;
        $students = User::active()->get();
        $name = 'Students';

        if (empty($request->students)) {
            return Redirect::back();
        }

        if ($request->has('modules')) {
            $moduleIds = explode(',', $request->modules[0]);

            if ($moduleIds && count($moduleIds) > 0) {
                $modules = Module::whereIn('id', $moduleIds)->with('items')->get();
            }
        } else {
            return Redirect::back();
        }

        if ($request->has('students')) {
            $std = $request->students;

            if ($std == 'all') {
                $students = User::all();
                $name = 'All students';
            } elseif ($std == 'active') {
                $students = User::active()->get();
                $name = 'Completed students';
            } else {
                $students = User::active()->where('batch_id', $std)->get();
                $batch = Batch::findOrFail($std);
                $name = $batch->name;
            }
        }


        // dd($request->all());
        return Excel::download(new UsersExport($modules, $students), $name . '.xlsx', null, [\Maatwebsite\Excel\Excel::XLSX]);
    }
}
