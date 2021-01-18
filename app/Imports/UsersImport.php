<?php

namespace App\Imports;

use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;

class UsersImport implements ToModel
{
    protected $batch_id;

    public function __construct($batch_id)
    {
        $this->batch_id = $batch_id;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $dob = Carbon::createFromFormat('d/m/Y', $row[2]);
        $dob_password = $dob->format('dmY');
        return new User([
            'api_token' => $this->apiToken(),
            'name' => $row[0],
            'batch_id' => $this->batch_id,
            'enroll_no' => $row[1],
            'dob_password' => $dob_password,
            'dob' => $dob_password,
            'active' => $row[3],
        ]);
    }

    private function apiToken()
    {
        return Str::random(32);
    }
}
