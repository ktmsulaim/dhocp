<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UsersExport implements FromView, ShouldAutoSize
{

    protected $modules;
    protected $students;

    public function __construct($modules, $students)
    {
        $this->modules = $modules;
        $this->students = $students;
    }

    public function view(): View
    {
        return view('exports.students', [
            'modules' => $this->modules,
            'students' => $this->students
        ]);
    }
}
