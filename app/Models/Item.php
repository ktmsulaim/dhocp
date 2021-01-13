<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function setAddiotionalAttribute($value)
    {
        if ($value) {
            $this->attributes['additional'] = json_encode($value, JSON_FORCE_OBJECT);
        }
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function users()
    {
        return $this->belongsToMany(ItemUser::class);
    }
}
