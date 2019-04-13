<?php

namespace App\Service;

use App\Thread;
use Illuminate\Support\Facades\Redis;

class TrendingService
{
    public function get()
    {
        return array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, 4));;
    }

    public function increment(Thread $thread)
    {
        Redis::zincrby($this->cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'path' => route('threads.show', [$thread->channel->name, $thread->id])
        ]));
    }

    public function clear()
    {
        Redis::del($this->cacheKey());
    }

    protected function cacheKey()
    {
        return app()->environment('testing') ? 'testing_trending_threads' : 'trending_threads';
    }
}