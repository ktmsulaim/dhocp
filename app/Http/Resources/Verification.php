<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Verification extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'order' => $this->order,
            'verified' => $this->verified(),
            'created_at' => $this->created_at,
            'updated_at' => optional($this->updated_at)->format('d-m-Y g:i'),
        ];
    }
}
