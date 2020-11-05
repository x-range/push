<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'image' => $this->image,
            'icon' => $this->icon,
            'badge' => $this->badge,
            'link' => $this->link,
            'notifications_count' => $this->notifications_count,
            'clicks_count' => $this->clicks_count,
            'closes' => $this->closes_count
        ];
    }
}
