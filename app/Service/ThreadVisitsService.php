<?php

namespace App\Service;


use App\Thread;
use Illuminate\Support\Facades\Redis;

class ThreadVisitsService
{
    public function recordVisit(Thread $thread)
    {
        Redis::incr($this->cacheKey($thread));
    }

    public function visits(Thread $thread)
    {
        return Redis::get($this->cacheKey($thread));
    }

    public function resetVisits(Thread $thread)
    {
        Redis::del($this->cacheKey($thread));
    }

    public function cacheKey($thread)
    {
        return app()->environment('testing') ? "testing_thread.{$thread->id}.visits" : "thread.{$thread->id}.visits";
    }
}