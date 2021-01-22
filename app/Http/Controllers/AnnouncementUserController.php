<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AnnouncementUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AnnouncementUserController extends Controller
{
    public function index()
    {
        return Inertia::render('user/announcements/Index', [
            'announcements' => auth()->user()->announcements()->where('status', 1)->get(),
        ]);
    }

    public function show($id)
    {
        $announcement = auth()->user()->announcements()->whereStatus(1)->where('announcement_id', $id)->first();

        if (!$announcement) {
            return Redirect::route('user.inbox');
        }

        $au = AnnouncementUser::find($announcement->pivot->id);

        if (empty($au->read_at)) {
            $au->read_at = Carbon::now();
            $au->save();
        }

        return Inertia::render('user/announcements/Show', [
            'announcement' => $announcement,
        ]);
    }
}
