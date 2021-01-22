<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AnnouncementUser extends Pivot
{
    use HasFactory;

    protected $table = 'announcement_users';

    protected $dates = ['read_at', 'updated_at', 'created_at'];

    // protected $dateFormat = 'd-m-Y g:i A';

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
