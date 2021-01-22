<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Announcement extends JsonResource
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
            'title' => $this->title,
            'body' => $this->body,
            'status' => $this->status,
            'viewed' => [
                'count' => $this->readUsers()->count(),
                'delivered' => $this->users()->count(),
            ],
            'created_at' => optional($this->created_at)->format('d-m-Y g:i A'),
            'updated_at' => optional($this->updated_at)->format('d-m-Y g:i A'),
            'last_modified' => optional($this->updated_at)->diffForHumans(),
        ];
    }
}
