<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Module $module)
    {
        return Inertia::render('admin/modules/items/Create', [
            'module' => $module
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
            'module_id' => 'required|integer',
            'key' => '',
            'label' => 'required',
            'description' => 'required',
            'required' => 'required|integer',
            'type' => 'required|string',
            'placeholder' => '',
            'additional' => 'required_if:type,==,dropdown',
            'size' => '',
            'order' => '',
        ]);

        $item = new Item();
        $item->module_id = $request->module_id;
        $item->key = Str::slug($request->label);
        $item->label = $request->label;
        $item->description = $request->description;
        $item->required = $request->required;
        $item->type = $request->type;
        $item->placeholder = $request->placeholder;
        $item->additional = json_encode($request->additional, JSON_FORCE_OBJECT);
        $item->size = $request->size;
        $item->order = $request->order;
        $item->save();

        return Redirect::route('modules.show', $request->module_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        return Inertia::render('admin/modules/items/Edit', [
            'item' => $item,
            'module' => $item->module,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'module_id' => 'required|integer',
            'key' => '',
            'label' => 'required',
            'description' => 'required',
            'required' => 'required|integer',
            'type' => 'required|string',
            'placeholder' => '',
            'additional' => 'required_if:type,==,dropdown',
            'size' => '',
            'order' => '',
        ]);


        if ($item->type == 'dropdown' && $request->type != 'dropdown') {
            $item->additional = null;
        } else {
            $item->additional = json_encode($request->additional, JSON_FORCE_OBJECT);
        }

        $item->key = Str::slug($request->label);
        $item->label = $request->label;
        $item->description = $request->description;
        $item->required = $request->required;
        $item->type = $request->type;
        $item->placeholder = $request->placeholder;
        $item->size = $request->size;
        $item->order = $request->order;
        $item->save();

        return Redirect::route('modules.show', $item->module_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return Redirect::back();
    }

    public function updateOrder(Request $request)
    {
        $items = $request->items;

        // dd($items);
        if ($items && count($items) > 0) {
            foreach ($items as $key => $item) {
                $itemObj = Item::findOrFail($item['id']);
                $itemObj->order = $item['order'];
                $itemObj->save();
            }
        }
    }
}
