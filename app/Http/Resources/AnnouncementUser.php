<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnnouncementUser extends JsonResource
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
            'announcement_id' => $this->announcement_id,
            'user_id' => $this->user_id,
            'user' => new User($this->user),
            'read_at' => optional($this->read_at)->format('d-m-Y g:i A'),
            'read_at_formatted' => optional($this->read_at)->diffForHumans(),
        ];
    }
}
