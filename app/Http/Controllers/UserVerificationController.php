<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserVerification;
use App\Models\UserVerification as ModelsUserVerification;
use App\Models\Verification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserVerificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $verifications = Verification::enabled()->get();

        return Inertia::render('user/Progress', [
            'verifications' => $verifications,
            'status' => UserVerification::collection(ModelsUserVerification::where(['user_id' => $user->id])->get()),
            'isQualified' => $user->isQualified(),
        ]);
    }
}
