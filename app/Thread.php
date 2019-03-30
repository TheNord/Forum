<?php

namespace App;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected $appends = ['isSubscribedTo'];

    public function replies()
    {
        return $this->hasMany(Reply::class)
            ->withCount('favorites');
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

        /** @var Reply $reply */
        $reply = $this->replies()->create($reply);

        $this->subscriptions
            ->filter(function ($sub) use ($reply) {
                return $sub->user_id != $reply->user_id;
            })
            ->each->notify($this, $reply);

        return $reply;
    }

    public function isOwner()
    {
        return $this->user_id == auth()->id();
    }

    public function hasReplies()
    {
        return !!$this->replies()->count();
    }

    public function subscribe(User $user = null)
    {
        $user = $user ?: auth()->user();
        $this->subscriptions()->create([
            'user_id' => $user->id,
        ]);
    }

    public function unsubscribe(User $user = null)
    {
        $user = $user ?: auth()->user();
        $this->subscriptions()->where('user_id', $user->id)->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscriptions::class);
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }
}
