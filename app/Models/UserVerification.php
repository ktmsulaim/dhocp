<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserVerification extends Pivot
{
    use HasFactory;

    protected $table = 'user_verifications';

    public function verification()
    {
        return $this->belongsTo(Verification::class);
    }


    public function totalVerified($verification_id)
    {
        return $this->where(['verification_id' => $verification_id, 'status' => 1])->count();
    }
}
