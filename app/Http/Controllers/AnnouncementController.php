<?php

namespace App\Http\Controllers;

use App\Http\Resources\Announcement as ResourcesAnnouncement;
use App\Http\Resources\AnnouncementUser as ResourcesAnnouncementUser;
use App\Models\Announcement;
use App\Models\AnnouncementUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $announcements = Announcement::all();

        return Inertia::render('admin/announcements/Index', [
            'announcements' => ResourcesAnnouncement::collection($announcements),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('admin/announcements/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
            'status' => 'required'
        ]);

        $announcement = Announcement::create($request->all());

        $students = User::active()->get();

        $students->each(function ($std) use ($announcement) {
            $std->announcements()->attach($announcement);
        });

        return Redirect::route('announcements.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function show(Announcement $announcement)
    {
        return Inertia::render('admin/announcements/Show', [
            'announcement' => new ResourcesAnnouncement($announcement),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function edit(Announcement $announcement)
    {
        return Inertia::render('admin/announcements/Edit', [
            'announcement' => $announcement,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
            'status' => 'required'
        ]);

        $announcement->update($request->all());

        return Redirect::route('announcements.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        // return Redirect::route('announcements.index');
    }


    public function updateStatus(Request $request, Announcement $announcement)
    {
        $status = $request->status;

        if ($status == 1 || $status == 0) {
            $announcement->status = $status;
            $announcement->save();

            return response(new ResourcesAnnouncement($announcement), 200);
        }

        return response([], 404);
    }

    public function readBy(Announcement $announcement)
    {
        $au = AnnouncementUser::where(['announcement_id' => $announcement->id])->whereNotNull('read_at')->get();
        return ResourcesAnnouncementUser::collection($au);
    }
}
