<?php

namespace App;

use App\Events\ThreadHasNewReply;
use App\Service\ThreadVisitsService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected $appends = ['isSubscribedTo'];

    protected $casts = [
        'locked' => 'boolean',
    ];

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

    /**
     * @param $reply
     * @return Reply
     * @throws \Throwable
     */
    public function addReply($reply)
    {
        throw_if($this->locked, '\RuntimeException', 'Thread is locked.', 422);

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

    public function getVisitsAttribute()
    {
        $threadVisits = new ThreadVisitsService();
        return $threadVisits->visits($this) ?? 0;
    }

    public function path()
    {
        return route('threads.show', [$this->channel->name, $this->slug]);
    }

    public function setSlugAttribute($slug)
    {
        $original = $slug;
        $count = 2;

        while (static::whereSlug($slug)->exists()) {
            $slug = "{$original}-" . $count++;
        }

        $this->attributes['slug'] = $slug;
    }

    public function lock()
    {
        $this->update(['locked' => true]);
    }

    public function unlock()
    {
        $this->update(['locked' => false]);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
