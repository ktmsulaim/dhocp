<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentExportController extends Controller
{
    public function export()
    {
        $students = [];

        Inertia::render('admin/students/Export', [
            'students' => $students,
        ]);
    }
}
