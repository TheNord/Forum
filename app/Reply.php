<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected  $guarded = [];

    protected $with = ['owner', 'favorites'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite($userId)
    {
        $attributes = ['user_id' => $userId];

        if ($this->favorites()->where($attributes)->exists()) {
            throw new \LogicException('You have already marked this post as favorited');
        }

        if ($this->user_id == $userId) {
            throw new \LogicException('Can not rate your post');
        }

        return $this->favorites()->create($attributes);

    }

    public function isFavorited()
    {
        return !! $this->favorites->where('user_id', auth()->id())->count();
    }
}
