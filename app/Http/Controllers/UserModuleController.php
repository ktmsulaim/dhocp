<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemGroup;
use App\Models\ItemUser;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;


class UserModuleController extends Controller
{
    public function index()
    {
        $modules = Module::active()->get();

        return Inertia::render('user/modules/Index', [
            'modules' => $modules,
        ]);
    }


    public function show(Module $module)
    {
        $items = $module->items;
        $user = Auth::user();

        // If the current user hasn't the item add
        if ($items && count($items) > 0 && !$module->isRepeatable()) {
            foreach ($items as $key => $item) {
                if (!$user->hasItem($item)) {
                    $user->items()->attach([
                        'item_id' => $item->id,
                    ]);
                }
            }
        }

        return Inertia::render('user/modules/Show', [
            'module' => $module->load('items'),
            'itemGroups' => optional($module->itemGroups())->with('itemUsers')->get(),
            'items' => $user->getItemsByModule($module->id),
        ]);
    }

    public function addRecord(Module $module)
    {
        return Inertia::render('user/modules/Create', [
            'module' => $module->load('items'),
        ]);
    }

    public function createUserItems(Request $request)
    {
        $user = Auth::user();
        $module = Module::find($request->module_id);

        if ($module->repeatable == 1 && !$request->has('item_group_id')) {
            $itemGroup = $module->itemGroups()->create(['user_id' => $user->id]);
        } else {
            $itemGroup = ItemGroup::find($request->item_group_id);
        }

        if ($request->has('data') && count($request->data) > 0) {
            foreach ($request->data as $key => $data) {
                $item = Item::find($key);

                if ($item->required == 1) {
                    if ($user->hasItem($item, $itemGroup)) {
                        $itemUser = ItemUser::where(['item_group_id' => $itemGroup->id, 'item_id' => $item->id])->first();
                        $itemUser->value = $data;
                        $itemUser->save();
                    } else {
                        $itemGroup->itemUsers()->create([
                            'item_id' => $item->id,
                            'user_id' => Auth::id(),
                            'value' => $data,
                        ]);
                    }
                }
            }
        }

        return Redirect::route('user.modules.show', $module->id);
    }


    public function updateUserItems(Request $request)
    {
        $user = Auth::user();


        if ($request->has('data') && count($request->data) > 0) {
            foreach ($request->data as $key => $data) {
                $item = Item::find($key);
                $module = $item->module;
                $itemGroup = null;


                if ($module->repeatable == 1 && !$request->has('item_group_id')) Redirect::back();

                if ($module->repeatable == 1 && $request->has('item_group_id')) {
                    $itemGroup = ItemGroup::find($request->item_group_id);
                }


                if ($user->hasItem($item, $itemGroup)) {
                    if ($itemGroup) {
                        $itemUser = ItemUser::where(['item_group_id' => $itemGroup->id, 'item_id' => $item->id])->first();
                    } else {
                        $itemUser = ItemUser::where(['item_id' => $item->id])->first();
                    }

                    $itemUser->value = $data;
                    $itemUser->save();
                } else {
                    $itemGroup->itemUsers()->create([
                        'item_id' => $item->id,
                        'user_id' => Auth::id(),
                        'value' => $data,
                    ]);
                }
            }
        }

        return Redirect::route('user.modules.show', $request->module_id);
    }

    public function editRecord(Module $module, $itemGroup = null)
    {
        $user = Auth::user();

        if ($module->isRepeatable() && !$itemGroup) Redirect::back();

        if ($module->isRepeatable()) {
            //Exact itemUser objects
            $ig = ItemGroup::find($itemGroup);
            $itemUsers = $ig->itemUsers;
        } else {
            // Items with pivot
            $itemUsers = $user->items()->where('module_id', $module->id)->get();
        }

        return Inertia::render('user/modules/Edit', [
            'module' => $module->load('items'),
            'itemUsers' => $itemUsers,
        ]);
    }

    public function deleteItemRecord(ItemGroup $itemGroup)
    {
        if (!$itemGroup) Redirect::back();

        $itemGroup->delete();

        return Redirect::route('user.modules.show', $itemGroup->module_id);
    }

    public function uploadFile(Request $request)
    {
        $item = Item::findOrFail($request->id);
        $user = Auth::user();


        $file = $request->file('file');
        $size = $file->getSize();

        $filename = time() . '.' . $request->ext;

        $file->move(public_path('uploads'), $filename);

        $itemUser = ItemUser::where(['user_id' => $user->id, 'item_id' => $item->id])->first();
        $itemUser->value = asset('uploads/' . $filename);
        $itemUser->value_info = json_encode([
            'name' => $filename,
            'size' => $size,
            'sizeText' => $request->size,
            'type' => $file->getClientMimeType(),
            'ext' => $request->ext,
            'url' => asset('uploads/' . $filename)
        ], JSON_FORCE_OBJECT);
        $itemUser->save();
    }

    public function deleteFile(Request $request)
    {
        if ($request->has('item_user_id')) {
            $itemUser = ItemUser::find($request->item_user_id);
            $file = json_decode($itemUser->value_info, true);

            $filename = public_path('uploads') . DIRECTORY_SEPARATOR . $file['name'];

            if (file_exists($filename)) {
                unlink($filename);
                $itemUser->value = null;
                $itemUser->value_info = null;
                $itemUser->save();
            }
        }
    }
}
