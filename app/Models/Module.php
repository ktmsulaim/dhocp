<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeOffice($query)
    {
        return $query->where('office_use', 1);
    }

    public function scopePublic($query)
    {
        return $query->where('office_use', 0);
    }

    public function itemGroups()
    {
        return $this->hasMany(ItemGroup::class);
    }

    public function isRepeatable()
    {
        return $this->repeatable == 1;
    }
}
