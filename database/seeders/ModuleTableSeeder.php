<?php

namespace Database\Seeders;

use App\Models\Module;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ModuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $personal = Module::create([
            'name' => 'Personal',
            'description' => 'Applicant\'s personal data!',
            'status' => 1
        ]);

        $educational = Module::create([
            'name' => 'Educational',
            'description' => 'Applicant\'s educational data in DH system',
            'status' => 1
        ]);

        $external = Module::create([
            'name' => 'External Education',
            'description' => 'Applicant\'s educational data in external stream.',
            'status' => 1
        ]);

        $job = Module::create([
            'name' => 'Job',
            'description' => 'Applicant\'s job details.',
            'status' => 1,
            'repeatable' => 1,
        ]);

        if ($job->items()->count() == 0) {
            $job->items()->insert([
                [
                    'module_id' => $job->id,
                    'label' => 'Designation',
                    'key' => Str::slug('Designation'),
                    'description' => 'Enter the position in the job.',
                    'required' => 1,
                    'type' => 'text',
                    'placeholder' => 'Position title',
                    'size' => '2',
                    'order' => 1,
                    'created_at' => Carbon::now(),
                ],
                [
                    'module_id' => $job->id,
                    'label' => 'Institution',
                    'key' => Str::slug('Institution'),
                    'description' => 'Name of the institution.',
                    'required' => 1,
                    'type' => 'text',
                    'placeholder' => 'Name of the institution',
                    'size' => '2',
                    'order' => 2,
                    'created_at' => Carbon::now(),
                ],
                [
                    'module_id' => $job->id,
                    'label' => 'Duration',
                    'key' => Str::slug('Duration'),
                    'description' => 'Enter the job Duration.',
                    'required' => 1,
                    'type' => 'text',
                    'placeholder' => 'The job Duration',
                    'size' => '2',
                    'order' => 3,
                    'created_at' => Carbon::now(),
                ],
                [
                    'module_id' => $job->id,
                    'label' => 'Reference person',
                    'key' => Str::slug('Reference person'),
                    'description' => 'Enter the name of reference person of the institution.',
                    'required' => 1,
                    'type' => 'text',
                    'placeholder' => 'Reference person of the institution',
                    'size' => '2',
                    'order' => 4,
                    'created_at' => Carbon::now(),
                ],
            ]);
        }

        $documents = Module::create([
            'name' => 'Documents Applying',
            'description' => 'Details of Documents applying for.',
            'status' => 1
        ]);

        $other = Module::create([
            'name' => 'Other details',
            'description' => 'Information about miscellaneous things.',
            'status' => 1
        ]);

        $uploads = Module::create([
            'name' => 'Documents Uploaded',
            'description' => 'Upload necessary documents.',
            'status' => 1
        ]);

        $byOffice = Module::create([
            'name' => 'Office use',
            'description' => 'Details updated by office use.',
            'status' => 1,
            'office_use' => 1,
        ]);
    }
}
