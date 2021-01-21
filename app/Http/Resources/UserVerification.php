<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserVerification extends JsonResource
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
            'user_id' => $this->user_id,
            'verification_id' => $this->verification_id,
            'verification' => $this->verification,
            'status' => $this->status,
            'remarks' => $this->remarks,
            'created_at' => optional($this->created_at)->format('d-m-Y g:i:s A'),
            'updated_at' => optional($this->created_at)->format('d-m-Y g:i:s A'),
        ];
    }
}
