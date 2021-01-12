<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as ResourcesUser;
use App\Models\Batch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $batch = request()->input('batch');

        if (!$batch) {
            $students = ResourcesUser::collection(User::all());
        } else {
            $batch = Batch::findOrFail($batch);
            $students = ResourcesUser::collection($batch->users);
        }

        $batches = Batch::all();

        return Inertia::render('admin/students/Index', [
            'students' => $students,
            'batches' => $batches,
            'link' => URL::route('students.index'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $batches = Batch::all();

        return Inertia::render('admin/students/Create', [
            'batches' => $batches,
        ]);
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
            'name' => 'required|string',
            'enroll_no' => 'required|numeric|unique:users,enroll_no',
            'dob' => 'required',
            'dob_password' => 'required',
            'batch_id' => 'required|integer',
            'active' => 'required'
        ]);

        User::create(array_merge($request->all(), ['dob' => $request->dob_password]));

        return Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = User::findOrFail($id);

        return Inertia::render('admin/students/Show', [
            'student' => new ResourcesUser($student),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student = User::findOrFail($id);

        $batches = Batch::all();

        return Inertia::render('admin/students/Edit', [
            'student' => new ResourcesUser($student),
            'batches' => $batches,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $student = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'enroll_no' => 'required|numeric|unique:users,enroll_no,' . $id,
            'dob' => 'required',
            'dob_password' => 'required',
            'batch_id' => 'required|integer',
            'active' => 'required'
        ]);

        $student->update(array_merge($request->all(), ['dob' => $request->dob_password]));

        return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $student = User::findOrFail($id);

        if ($student) {
            $student->delete();
        }

        return Redirect::back();
    }
}
