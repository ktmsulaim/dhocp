<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Batch;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminAppController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/Dashboard', [
            'user' => auth('admin')->user(),
            'batches' => count(Batch::all()),
            'users' => count(User::all()),
            'modules' => count(Module::all()),
            'admins' => count(Admin::all()),
        ]);
    }


    public function profile()
    {
        return Inertia::render('admin/Profile');
    }
}
