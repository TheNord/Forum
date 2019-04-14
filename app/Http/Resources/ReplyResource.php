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
            'isBest' => $this->isBest(),
            'bestReplyId' => $this->thread->best_reply_id,
            'thread_id' => $this->thread_id,
            'user_id' => $this->user_id,
            'owner' => [
                'name' => $this->owner->name,
                'avatar' => $this->getAvatar(),
            ],
        ];
    }

    public function getAvatar()
    {
        if ($this->owner->hasAvatar()) {
            return asset("storage/{$this->owner->avatar_path}");
        }

        return asset('images/default-avatar.png');
    }
}
