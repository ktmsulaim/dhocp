<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        // Order by order ASC
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('created_at', 'desc');
        });
    }


    public function scopePublished($query)
    {
        return $query->where('status', 1);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'announcement_users')
            ->using(AnnouncementUser::class)
            ->withPivot(['id', 'user_id', 'announcement_id', 'read_at']);
    }

    public function readUsers()
    {
        return $this->users()->wherePivotNotNull('read_at');
    }
}
