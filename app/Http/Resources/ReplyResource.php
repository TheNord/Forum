<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReplyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'created_at' => $this->created_at->diffForHumans(),
            'favorites_count' => $this->favorites_count,
            'isFavorited' => $this->isFavorited,
            'thread_id' => $this->thread_id,
            'user_id' => $this->user_id,
            'owner' => ['name' => $this->owner->name],
        ];
    }
}
