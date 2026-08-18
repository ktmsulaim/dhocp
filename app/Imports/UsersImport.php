<?php

namespace App\Imports;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;

class UsersImport implements OnEachRow
{
    protected $batch_id;
    protected $duplicateMode;

    public $created = 0;
    public $updated = 0;
    public $skipped = 0;

    public function __construct($batch_id, $duplicateMode = 'skip')
    {
        $this->batch_id = $batch_id;
        $this->duplicateMode = $duplicateMode === 'update' ? 'update' : 'skip';
    }

    public function onRow(Row $row)
    {
        try {
            $this->importRow($row->toArray());
        } catch (\Throwable $th) {
            $this->skipped++;
        }
    }

    private function importRow(array $row)
    {
        if (!$this->isValidRow($row)) {
            return;
        }

        $enrollNo = (string) intval($row[1]);
        $dobPassword = $this->parseDobPassword($row[2]);
        $existing = User::where('enroll_no', $enrollNo)->first();

        if ($existing) {
            if ($this->duplicateMode === 'skip') {
                $this->skipped++;
                return;
            }

            $existing->update($this->studentAttributes($row, $dobPassword));
            $this->updated++;
            return;
        }

        User::create(array_merge(
            $this->studentAttributes($row, $dobPassword),
            [
                'api_token' => Str::random(32),
                'enroll_no' => $enrollNo,
            ]
        ));
        $this->created++;
    }

    private function isValidRow(array $row)
    {
        for ($i = 0; $i < 4; $i++) {
            if (!array_key_exists($i, $row) || $row[$i] === null || $row[$i] === '') {
                return false;
            }
        }

        return is_numeric($row[1]);
    }

    private function parseDobPassword($value)
    {
        try {
            return Carbon::createFromFormat('d/m/Y', $value)->format('dmY');
        } catch (\Throwable $th) {
            return Carbon::now()->format('dmY');
        }
    }

    private function studentAttributes(array $row, $dobPassword)
    {
        return [
            'name' => $row[0],
            'batch_id' => $this->batch_id,
            'dob_password' => $dobPassword,
            'dob' => $dobPassword,
            'active' => $row[3],
        ];
    }
}
