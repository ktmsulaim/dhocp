<?php

namespace App\Http\Controllers;

use App\Models\ItemGroup;
use App\Models\ItemUser;
use App\Models\Module;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Madnest\Madzipper\Madzipper;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modules = Module::withCount('items')->get();

        return Inertia::render('admin/modules/Index', [
            'modules' => $modules,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
            'description' => 'required',
            'status' => 'required|integer',
            'repeatable' => 'required|integer',
            'office_use' => 'required|integer',
        ]);

        Module::create($request->all());

        return Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function show(Module $module)
    {
        return Inertia::render('admin/modules/Show', [
            'module' => $module,
            'items' => $module->items,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function edit(Module $module)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Module $module)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required',
            'status' => 'required|integer',
            'repeatable' => 'required|integer',
            'office_use' => 'required|integer',
        ]);

        $module->update($request->all());

        return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function destroy(Module $module)
    {
        $module->delete();

        return Redirect::back();
    }

    public function updateStatus($id)
    {
        if (request()->has('status')) {
            $status = request()->get('status');

            $itemUser = ItemUser::findOrFail($id);
            $itemUser->is_valid = $status;
            $itemUser->admin_updated = Carbon::now();
            $itemUser->timestamps = false;

            if ($status == 2 && !empty($itemUser->valid_message)) {
                $itemUser->valid_message = null;
            }

            $itemUser->save();

            return Redirect::back();
        }
    }

    public function updateInvalidMessage(Request $request, $id)
    {
        $itemUser = ItemUser::findOrFail($id);
        $itemUser->valid_message = $request->invalidMessage;
        $itemUser->admin_updated = Carbon::now();
        $itemUser->timestamps = false;
        $itemUser->save();

        return Redirect::back();
    }

    public function updateItemGroupStatus($id)
    {
        if (request()->has('status')) {
            $status = request()->get('status');

            $ItemGroup = ItemGroup::findOrFail($id);
            $ItemGroup->is_valid = $status;
            $ItemGroup->admin_updated = Carbon::now();
            $ItemGroup->timestamps = false;

            if ($status == 2 && !empty($ItemGroup->valid_message)) {
                $ItemGroup->valid_message = null;
            }

            $ItemGroup->save();

            // Update items under it
            if ($ItemGroup->itemUsers()->exists()) {
                foreach ($ItemGroup->itemUsers as $itemUser) {
                    $itemUser->is_valid = $status;
                    $itemUser->admin_updated = Carbon::now();
                    $itemUser->timestamps = false;
                    $itemUser->save();
                }
            }

            return Redirect::back();
        }
    }

    public function updateIGInvalidMessage(Request $request, $id)
    {
        $ItemGroup = ItemGroup::findOrFail($id);
        $ItemGroup->valid_message = $request->invalidMessage;
        $ItemGroup->admin_updated = Carbon::now();
        $ItemGroup->timestamps = false;
        $ItemGroup->save();

        return Redirect::back();
    }


    public function downloadZip(Module $module, $user_id)
    {
        $files = $module->getDownloadableFiles($user_id);
        $student = User::findOrFail($user_id);
        $filename = $student->enroll_no . '.zip';

        if ($files) {
            $zipper = new Madzipper;
            $zipper->make(public_path('storage' . DIRECTORY_SEPARATOR . 'archived' . DIRECTORY_SEPARATOR . $filename));

            foreach ($files as $key => $file) {
                $zipper->add($file['file'], $file['newName']);
            }

            $zipper->close();

            return response()->download(public_path('storage' . DIRECTORY_SEPARATOR . 'archived' . DIRECTORY_SEPARATOR . $filename), $filename, ['Content-type:application/zip', $filename]);
        }
    }
}
