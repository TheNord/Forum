<?php

namespace App;

use App\Events\ThreadHasNewReply;
use App\Notifications\ThreadWasUpdated;
use Carbon\Carbon;
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
        $this->updateThreadUnViewedCache();

        event(new ThreadHasNewReply($this, $reply));

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

    public function hasUpdatedFor()
    {
        if (!auth()->check()) {
            return false;
        }

        $key = sprintf("users.%s.visits.%s", auth()->id(), $this->id);
        return $this->updated_at > cache($key);
    }

    public function updateThreadUnViewedCache()
    {
        if (auth()->check()) {
            $key = sprintf("users.%s.visits.%s", auth()->id(), $this->id);
            cache()->forever($key, Carbon::now());
        }
    }
}
