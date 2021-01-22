<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        // Order by order ASC
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('order', 'asc');
        });
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_verifications')
        ->withPivot(['id', 'status', 'remarks', 'created_at', 'updated_at']);
    }

    public function scopeEnabled($query)
    {
        return $query->where('status', 1);
    }

    public function verified()
    {
        return $this->users()->wherePivot('status', 1)->count();
    }
}
