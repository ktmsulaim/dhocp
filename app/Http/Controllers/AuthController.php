<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AuthController extends Controller
{

    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function username()
    {
        return 'enroll_no';
    }

    public function viewLogin()
    {
        return Inertia::render('user/Login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'enroll_no' => 'required|numeric|exists:users,enroll_no',
            'dob' => 'required'
        ]);

        if (!Auth::attempt(['enroll_no' => $request->enroll_no, 'password' => $request->dob], $request->remember_me)) {
            return Redirect::back()->withErrors(['enroll_no' => 'Invalid login info!']);
        }

        return Redirect::route('user.index');
    }
}
