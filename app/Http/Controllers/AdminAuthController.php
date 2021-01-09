<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AdminAuthController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('admin.guest', ['except' => 'logout']);
    }

    public function viewLogin()
    {
        return Inertia::render('admin/Login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email',
            'password' => 'required'
        ]);

        if (!Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember_me)) {
            return Redirect::back()->withErrors(['email' => 'Invalid login info!']);
        }

        return Redirect::route('admin.index');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        return Redirect::route('admin.login');
    }
}
