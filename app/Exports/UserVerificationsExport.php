<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UserVerificationsExport implements FromView, ShouldAutoSize
{
    protected $students;
    protected $verifications;


    
    public function __construct($students, $verifications) {
        $this->students = $students;
        $this->verifications = $verifications;
    }

    public function view(): View
    {
        return view('exports.verifications', [
            'verifications' => $this->verifications,
            'students' => $this->students
        ]);
    }
}
