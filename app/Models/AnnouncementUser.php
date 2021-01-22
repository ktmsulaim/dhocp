<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AnnouncementUser extends Pivot
{
    use HasFactory;

    protected $table = 'announcement_users';
}
