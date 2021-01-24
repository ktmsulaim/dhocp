<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'api_token' => $this->api_token,
            'id' => $this->id,
            'name' => $this->name,
            'enroll_no' => $this->enroll_no,
            'dob' => $this->dob,
            'dob_formatted' => $this->dob ? Carbon::createFromFormat('dmY', $this->dob)->format('d/m/Y') : '',
            'active' => $this->active,
            'batch_name' => $this->batch->name,
            'batch' => $this->batch,
            'profile' => $this->profile(),
        ];
    }
}
