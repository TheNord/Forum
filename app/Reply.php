<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use RecordsActivity;

    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected  $guarded = [];


    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['owner', 'favorites'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['isFavorited'];

    /**
     * Boot the reply instance.
     */
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

    /**
     * A reply has an owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A reply can be favorited.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    /**
     * A reply belongs to a thread.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * Favorite the current reply.
     *
     * @return Model
     */
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

    /**
     * Unfavorite the current reply.
     */
    public function unFavorite($userId)
    {
        $attributes = ['user_id' => $userId];

        return $this->favorites()->where($attributes)->get()->each(function ($favorite) {
            $favorite->delete();
        });

    }

    /**
     * Get the number of favorites for the reply.
     *
     * @return integer
     */
    public function getIsFavoritedAttribute()
    {
        return !! $this->favorites->where('user_id', auth()->id())->count();
    }

    /**
     * Determine if the reply was own by current user.
     *
     * @return bool
     */
    public function isOwner()
    {
        return $this->user_id == auth()->id();
    }

    /**
     * Determine if the reply was just published a moment ago.
     *
     * @return bool
     */
    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    /**
     *
     * Determine if the current reply is marked as the best.
     *
     * @return bool
     */
    public function isBest()
    {
        return $this->thread->best_reply_id == $this->id;
    }

    /**
     * Access the body attribute.
     *
     * @param  string $body
     * @return string
     */
    public function getBodyAttribute($body)
    {
        return \Purify::clean($body);
    }

    /**
     * Set the body attribute.
     *
     * @param string $body
     */
    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace('/\@([\w\-]+)/', '<a href="/profile/$1">$0</a>', $body);
    }
}
