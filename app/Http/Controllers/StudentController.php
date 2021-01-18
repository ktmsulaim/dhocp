<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as ResourcesUser;
use App\Models\Batch;
use App\Models\Item;
use App\Models\ItemGroup;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

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
            $students = [];
        } elseif ($batch == 'all') {
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

        User::create(array_merge(
            $request->all(),
            ['dob' => $request->dob_password, 'api_token' => Str::random(32)]
        ));

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
        $modules = Module::active()->get();

        return Inertia::render('admin/students/Show', [
            'student' => new ResourcesUser($student),
            'modules' => $modules,
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


    public function getModuleItems($student_id, $module_id)
    {
        $module = Module::findOrFail($module_id);
        $user = User::findOrFail($student_id);

        if ($module->isRepeatable()) {
            $items = [
                'items' => $module->items,
                'itemGroups' => ItemGroup::where(['module_id' => $module_id, 'user_id' => $user->id])->with('itemUsers')->get(),
            ];
        } else {
            if ($module->items()->exists()) {
                foreach ($module->items as $key => $item) {
                    if (!$user->hasItem($item)) {
                        $user->items()->attach([
                            'item_id' => $item->id,
                        ]);
                    }
                }
            }

            $items = $user->getItemsByModule($module_id);
        }

        return $items;
    }
}
