<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    protected  $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function ($builder) {
           $builder->withCount('replies');
        });
    }

    public function replies()
    {
        return $this->hasMany(Reply::class)->withCount('favorites');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }

    public function isOwner()
    {
        return $this->user_id == auth()->id();
    }

    public function hasReplies()
    {
        return !! $this->replies()->count();
    }
}
