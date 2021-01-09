<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'api_token' => Str::random(32),
            'name' => 'Admin',
            'email' => 'admin@dhocp.in',
            'password' => bcrypt('admin@dhocp')
        ]);
    }
}
