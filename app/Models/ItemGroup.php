<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemGroup extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function itemUsers()
    {
        return $this->hasMany(ItemUser::class);
    }

    public function orderedItemUsers()
    {
        return $this->itemUsers()->join('items', 'item_user.item_id', '=', 'items.id')->orderBy('items.order')->select('item_user.*')->get();
    }
}
