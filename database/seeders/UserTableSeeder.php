<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $batch = Batch::create([
            'name' => 'Batch 1',
            'start' => '2018',
            'end' => '2020'
        ]);

        $batch->users()->create([
            'name' => 'ktmsulaim',
            'enroll_no' => '14042',
            'dob' => '13031997',
            'dob_password' => bcrypt('13031997'),
        ]);
    }
}
