<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminAppController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/Dashboard', [
            'user' => auth('admin')->user(),
        ]);
    }


    public function profile()
    {
        return Inertia::render('admin/Profile');
    }
}
