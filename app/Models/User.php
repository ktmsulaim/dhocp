<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'batch_id',
        'name',
        'enroll_no',
        'dob_password',
        'dob',
        'active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'dob_password',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->dob_password;
    }

    public function setDobPasswordAttribute($value)
    {
        $this->attributes['dob_password'] = bcrypt($value);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
