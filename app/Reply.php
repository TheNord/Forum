<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use RecordsActivity;

    protected  $guarded = [];

    protected $with = ['owner', 'favorites'];

    protected $appends = ['isFavorited'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($reply) {
           $reply->thread->increment('replies_count');
        });

        static::deleted(function ($reply) {
           $reply->thread->decrement('replies_count');
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function favorite($userId)
    {
        $attributes = ['user_id' => $userId];

        if ($this->favorites()->where($attributes)->exists()) {
            throw new \LogicException('You have already marked this post as favorited');
        }

        if ($this->isOwner()) {
            throw new \LogicException('Can not rate your own post');
        }

        return $this->favorites()->create($attributes);

    }

    public function unFavorite($userId)
    {
        $attributes = ['user_id' => $userId];

        return $this->favorites()->where($attributes)->get()->each(function ($favorite) {
            $favorite->delete();
        });

    }

    public function getIsFavoritedAttribute()
    {
        return !! $this->favorites->where('user_id', auth()->id())->count();
    }

    public function isOwner()
    {
        return $this->user_id == auth()->id();
    }
}
